<?php namespace ProviderMobileApi;
use ZhuChao\Framework\OpenApi\AbstractScript;
use App\ZhuChao\MessageMgr\Constant as MessageMgr_Constant;
use App\ZhuChao\Provider\Constant as Provider_Content;
use Cntysoft\Kernel;
/**
 * 询价单处理
 * 
 * @package ProviderFrontApi
 */
class Inquiry extends AbstractScript
{
   /**
    * 根据页数页码获得当前供应商询价单列表
    * @param type $params
    * @return type
    */
   public function getInquiryListByStatus($params)
   {
      $this->checkRequireFields($params, array('page', 'pageSize', 'status'));
      $page = (int) $params['page'];
      $pageSize = (int) $params['pageSize'];
      $page == 0 ? $page = 1 : '';
      $pageSize == 0 ? $pageSize = 5 : '';
      $offset = ($page - 1) * $pageSize;
      $provider = $this->appCaller->call(Provider_Content::MODULE_NAME, Provider_Content::APP_NAME, Provider_Content::APP_API_MGR, 'getCurUser');
      $cond = array(
         'status = ' . $params['status']. ' and providerId = ' .$provider->getId()
      );
      $info = $this->appCaller->call(MessageMgr_Constant::MODULE_NAME, MessageMgr_Constant::APP_NAME, MessageMgr_Constant::APP_API_OFFER, 'getInquiryList', array($cond, false, 'inputTime DESC', $pageSize, $offset));
      $ret = array();
      foreach ($info as $item) {
         $product = $item->getProduct();
         $item = $item->toarray();
         $item['pic'] = \Cntysoft\Kernel\get_image_cdn_url($product->getDefaultImage());
         unset($item['uid']);
         unset($item['gid']);
         unset($item['expireTime']);
         $ret[] = $item;
      }
      return $ret;
   }

   /**
    * 根据id获得询价单详情
    * @param type $params
    * @return type
    */
   public function getInquiryDetail($params)
   {
      $this->checkRequireFields($params, array('id'));
      $info = $this->appCaller->call(MessageMgr_Constant::MODULE_NAME, MessageMgr_Constant::APP_NAME, MessageMgr_Constant::APP_API_OFFER, 'getInquiryAndOffer', array($params['id']));
      $inquiry = $info['inquiry'];
      $product = $inquiry->getProduct();
      $user = $inquiry->getBuyer();
      $ret = $inquiry->toarray();
      $ret['pic'] = \Cntysoft\Kernel\get_image_cdn_url($product->getDefaultImage());
      $ret['price'] = $product->getPrice();
      $ret['title'] = $product->getTitle();
      $ret['name'] = $user->getName();
      $ret['phone'] = $user->getPhone();
      $offer = $info['offer'];
      if ($offer == '尚未报价') {
         $ret['reply'] = array();
      } else {
         $ret['reply'] = $offer->toarray();
      }
      unset($ret['uid']);
      unset($ret['gid']);
      unset($ret['expireTime']);
      unset($ret['inputTime']);
      return $ret;
   }

   /**
    * 回复询价单
    * @param type $params
    */
   public function replyInquiry($params)
   {
      $this->checkRequireFields($params, array('inquiryId', 'highPrice', 'lowPrice', 'content'));
      $provider = $this->appCaller->call(Provider_Content::MODULE_NAME, Provider_Content::APP_NAME, Provider_Content::APP_API_MGR, 'getCurUser');
      $info = $this->appCaller->call(MessageMgr_Constant::MODULE_NAME, MessageMgr_Constant::APP_NAME, MessageMgr_Constant::APP_API_OFFER, 'addOffer', array(array(
            'inquiryId'  => $params['inquiryId'],
            'supplierId' => $provider->getId(),
            'lowPrice'   => $params['lowPrice'],
            'highPrice'  => $params['highPrice'],
            'content'    => $params['content']
      )));
   }

}