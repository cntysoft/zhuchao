<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace ProviderFrontApi;
use ZhuChao\Framework\OpenApi\AbstractScript;
use App\Yunzhan\CmMgr\Constant as CM_CONST;
use App\Yunzhan\Content\Constant as CN_CONST;
use App\ZhuChao\Provider\Constant as P_CONST;
use Cntysoft\Kernel;
class Site extends AbstractScript
{
   /**
    * 发表文章
    * 
    * @param array $params
    */
   public function addArticle($params)
   {
      unset($params['id']);

      $user = $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR, 'getCurUser');
      $company = $user->getCompany();
      $params['editor'] = $params['author'] = $company->getName();
      //这里直接审核通过,以后要加上内容审核
      $params['status'] = CN_CONST::INFO_S_VERIFY;
      $cndServer = Kernel\get_image_cdn_server_url() . '/';

      //删除图片的网址
      if (isset($params['defaultPicUrl'])) {
         $img = $params['defaultPicUrl'];
         unset($params['defaultPicUrl']);
         $item[0] = str_replace($cndServer, '', $img[0]);
         $item[1] = $img[1];
         $params['defaultPicUrl'] = $item;
      }
      $this->appCaller->call(CN_CONST::MODULE_NAME, CN_CONST::APP_NAME, CN_CONST::APP_API_MANAGER, 'add', array(CM_CONST::CONTENT_MODEL_ARTICLE, $params));
   }

   /**
    * 添加招聘信息
    * 
    * @param array $params
    */
   public function addJob($params)
   {
      unset($params['id']);

      $user = $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR, 'getCurUser');
      $company = $user->getCompany();
      $params['editor'] = $params['author'] = $company->getName();
      //这里直接审核通过,以后要加上内容审核
      $params['status'] = CN_CONST::INFO_S_VERIFY;
      $params['nodeId'] = CN_CONST::NODE_JOIN_ID;
      
      $this->appCaller->call(CN_CONST::MODULE_NAME, CN_CONST::APP_NAME, CN_CONST::APP_API_MANAGER, 'add', array(CM_CONST::CONTENT_MODEL_JOB, $params));
   }

}