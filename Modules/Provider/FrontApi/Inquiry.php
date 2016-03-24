<?php 


namespace ProviderFrontApi;
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