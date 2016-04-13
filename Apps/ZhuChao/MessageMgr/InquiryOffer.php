<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author ZhiHui <liuyan2526@qq.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\ZhuChao\MessageMgr;
use Cntysoft\Kernel\App\AbstractLib;
use Cntysoft\Kernel;
use App\ZhuChao\MessageMgr\Model\Inquiry as InquiryModel;
use App\ZhuChao\MessageMgr\Model\Offer as OfferModel;
use App\ZhuChao\Provider\Constant as PROVIDER_CONST;
use Cntysoft\Framework\Cloud\Ali\Push\Request\CloudPushHighRequest;
use Cntysoft\Framework\Cloud\Ali\Push\PushClient;
class InquiryOffer extends AbstractLib
{
   /**
    * 添加询价单
    * 
    * @param array $params
    */
   public function addInquiry(array $params)
   {
      $this->checkRequireFields($params, array('gid', 'uid', 'expireTime', 'providerId', 'content'));
      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         $params['inputTime'] = time();
         $params['expireTime'] = (int) $params['expireTime'] * 24 * 3600 + time();
         $inquiry = new InquiryModel();
         $inquiry->assignBySetter($params);
         $inquiry->create();
         $this->pushInquiryNotice($params['providerId'], '客户询价通知！', '你有一条新的询价信息，请尽快处理！');
         return $db->commit();
      } catch (\Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex);
      }
   }

   /**
    * 添加报价单
    * 
    * @param array $params
    */
   public function addOffer(array $params)
   {
      $this->checkRequireFields($params, array('inquiryId', 'supplierId', 'lowPrice', 'highPrice'));
      $params['inputTime'] = time();
      $params['status'] = 1;
      if (array_key_exists('recommendGoods', $params)) {
         if (!is_array($params['recommendGoods'])) {
            unset($params['recommendGoods']);
         }
      }
      $offer = new OfferModel();
      $offer->assignBySetter($params);

      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         $inquiry = $this->getInquiryAndOffer($params['inquiryId']);
         $inquiry = $inquiry['inquiry'];
         $inquiry->setStatus(Constant::INQUIRY_STATUS_OFFERED);
         $inquiry->save();
         $offer->create();
         return $db->commit();
      } catch (\Exception $ex) {
         $db->rollback();

         Kernel\throw_exception($ex);
      }
   }

   /**
    * 获取询价单及其对应的报价单
    * 
    * @param type $inquiryId
    * @return type
    */
   public function getInquiryAndOffer($inquiryId)
   {
      $inquiry = InquiryModel::findFirst(array(
                 'id = ?1',
                 'bind' => array(
                    1 => $inquiryId
                 )
      ));
      if (!$inquiry) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_NO_THIS_RECORD'), $errorType->code('E_NO_THIS_RECORD')
         ));
      }
      $offer = $inquiry->getOffer();
      $ret = array(
         'inquiry' => $inquiry
      );
      if (!$offer) {
         $ret['offer'] = '尚未报价';
      } else {
         $ret['offer'] = $offer;
      }
      return $ret;
   }

   /**
    * 获取询价单列表
    * @param array $cond
    * @param boolean $total
    * @param string $orderBy
    * @param int $limit
    * @param int $offset
    * @return 
    */
   public function getInquiryList(array $cond, $total = false, $orderBy = 'inputTime DESC', $limit = 15, $offset = 0)
   {
      $bind = array(
         'order' => $orderBy,
         'limit' => array(
            'number' => $limit,
            'offset' => $offset
         )
      );
      $condition = array_merge($cond, $bind);
      $inquiries = InquiryModel::find($condition);
      if ($total) {
         return array(
            'total' => InquiryModel::count($cond),
            'item'  => $inquiries
         );
      }
      return $inquiries;
   }

   /**
    * 推送消息给供应商
    * 
    * @param int $providerId
    * @param string $title
    * @param string $body
    * @return 
    */
   protected function pushInquiryNotice($providerId, $title, $body)
   {
      $provider = $this->getAppCaller()->call(
              PROVIDER_CONST::MODULE_NAME, PROVIDER_CONST::APP_NAME, PROVIDER_CONST::APP_API_MANAGER, 'getProvider', array($providerId)
      );
      $targetvalue = array($provider->getPhone());
      $request = new CloudPushHighRequest();
      $client = new PushClient();

      $request->setBody($body);
      $request->setDeviceType("3");
      $request->setRemind("true");
      $request->setStoreOffline("true");
      $request->setTarget("account");
      $request->setTargetValue(implode(',', $targetvalue));
      $request->setTitle($title);
//      $request->setAndroidExtParameters('{"id":"' . $orderNum . '"}');
//      $request->setIosExtParameters('{"id":"' . $orderNum . '"}');
      $request->setType("1");
      $client->execute($request);
   }

}