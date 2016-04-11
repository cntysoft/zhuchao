<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Arvin <cntyfeng@163.com>
 * @copyright  Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace PagesFrontApi;
use ZhuChao\Framework\OpenApi\AbstractScript;
use App\ZhuChao\Service\Constant as SERVICE_CONST;
use Cntysoft\Kernel;

/**
 * 客户服务相关的前台接口
 *
 */
class Service extends AbstractScript
{
   /**
    * 添加反馈信息
    * 
    * @param array $params
    */
   public function addFeedback(array $params)
   {
      $this->checkRequireFields($params, array('type', 'name', 'text'));

      $params['identify'] = md5(Kernel\get_server_env('REMOTE_ADDR') . Kernel\get_server_env('REMOTE_PORT') . Kernel\get_server_env('HTTP_USER_AGENT'));
      $params['status'] = SERVICE_CONST::FEEDBACK_STATUS_NEW;
      
      $this->appCaller->call(
         SERVICE_CONST::MODULE_NAME, 
         SERVICE_CONST::APP_NAME, 
         SERVICE_CONST::APP_API_FEEDBACK_MGR, 
         'addFeedback', 
         array($params)
      );
   }

}