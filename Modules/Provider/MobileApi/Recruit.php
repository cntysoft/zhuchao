<?php 

namespace ProviderMobileApi;
use ZhuChao\Framework\OpenApi\AbstractScript;
use App\ZhuChao\MessageMgr\Constant as MessageMgr_Constant;
use App\ZhuChao\Provider\Constant as Provider_Content;
use App\Yunzhan\Content\Constant as Content_Constant;
use Cntysoft\Kernel;
/**
 * 询价单处理
 * 
 * @package ProviderFrontApi
 */
class Recruit extends AbstractScript
{
   /**
    *根据详情获得询价单列表
    * @param type $params
    */
   public  function getRecruitListByStatus($params){
      $this->checkRequireFields($params,array('page','pageSize','status'));
      $page = (int)$params['page'];
      $page == 0 ? $page =1:'';
      $pageSize = (int)$params['pageSize'];
      $pageSize == 0 ? $pageSize=1:'';
      $user = $this->appCaller->call(Provider_Content::MODULE_NAME, Provider_Content::APP_NAME, Provider_Content::APP_API_MGR, 'getCurUser');
      $infoList = $this->appCaller->call(Content_Constant::MODULE_NAME,  Content_Constant::APP_NAME,  Content_Constant::APP_API_INFO_LIST,'getInfoListByNodeAndStatus',
              array(5,2,$params['status'],false,'id DESC',$offset,$limit));
   }
}