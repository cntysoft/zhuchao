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
      $params['editor'] = $params['author'] = $company->getName();;
      $this->appCaller->call(CN_CONST::MODULE_NAME, CN_CONST::APP_NAME, CN_CONST::APP_API_MANAGER, 'add', array(CM_CONST::CONTENT_MODEL_ARTICLE, $params));
   }
   
   

}