<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author ZhiHui <liuyan2526@qq.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\ZhuChao\MessageMgr\AjaxHandler;
use Cntysoft\Kernel\App\AbstractHandler;
use App\ZhuChao\MessageMgr\Constant as MESSAGE_CONST;
use Cntysoft\Kernel;
class InquiryOffer extends AbstractHandler
{
   /**
    * 获取询价报价列表
    * 
    * @param array $params
    * @return array
    */
   public function getInquiryAndOfferList(array $params)
   {
      $inquiries = $this->getAppCaller()->call(
              MESSAGE_CONST::MODULE_NAME, MESSAGE_CONST::APP_NAME, MESSAGE_CONST::APP_API_OFFER, 'getInquiryList', array(array(), true, 'inputTime DESC', $params['limit'], $params['start']));
      $ret = array(
         'total' => $inquiries['total']
      );
      foreach ($inquiries['item'] as $inquiry) {
         $buyer = $inquiry->getBuyer();
         $child = array(
            'id'          => $inquiry->getId(),
            'inquiry'     => $buyer->getName(),
            'inquiryTime' => date('Y-m-d', $inquiry->getInputTime()),
            'goods'       => $inquiry->getGid()
         );
         $offer = $inquiry->getOffer();
         if (!$offer) {
            $child['offer'] = MESSAGE_CONST::NOT_HAVE_PRICE;
            $child['lowPrice'] = MESSAGE_CONST::NOT_HAVE_PRICE;
            $child['highPrice'] = MESSAGE_CONST::NOT_HAVE_PRICE;
            $child['offerTime'] = MESSAGE_CONST::NOT_HAVE_PRICE;
         } else {
            $provider = $offer->getProvider();
            $child['offer'] = $provider->getName();
            $child['lowPrice'] = $offer->getLowPrice();
            $child['highPrice'] = $offer->getHighPrice();
            $child['offerTime'] = date('Y-m-d', $offer->getInputTime());
         }
         $ret['items'][] = $child;
      }
      return $ret;
   }

}