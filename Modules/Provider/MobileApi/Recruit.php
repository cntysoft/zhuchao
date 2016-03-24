<?php 

namespace ProviderMobileApi;
use ZhuChao\Framework\OpenApi\AbstractScript;
use App\ZhuChao\MessageMgr\Constant as MessageMgr_Constant;
use App\ZhuChao\Provider\Constant as Provider_Content;
use Cntysoft\Kernel;
/**
 * 询价单处理
 * 
 * @package ProviderFrontApi
 */
class Recruit extends AbstractScript
{
   public  function getRecruitListByStatus($params){
      $this->checkRequireFields($params,array('page','pageSize','status'));
      
   }
}