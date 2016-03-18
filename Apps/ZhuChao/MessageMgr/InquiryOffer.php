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
class InquiryOffer extends AbstractLib
{
   /**
    * 添加询价单
    * 
    * @param array $params
    */
   public function addInquiry(array $params)
   {
      $this->checkRequireFields($params, array('gid', 'uid', 'expireTime','providerId'));
      $params['inputTime'] = time();
      $params['expireTime'] = (int) $params['expireTime'] * 24 * 3600 + time();
      $inquiry = new InquiryModel();
      $inquiry->assignBySetter($params);
      return $inquiry->create();
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
      return $offer->create();
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
      return $offer;
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
   public function getInquiryList(array $cond,$total = false,$orderBy = 'inputTime DESC',$limit = 15,$offset = 0)
   {
      $inquiries = InquiryModel::find(array(
         $cond,
         'order' => $orderBy,
         'limit' => array(
            'number' => $limit,
            'offset' => $offset
         )
      ));
      if($total){
         return array(
            'total' => InquiryModel::count(array($cond)),
            'item' => $inquiries
         );
      }
      return $inquiries;
   }

}