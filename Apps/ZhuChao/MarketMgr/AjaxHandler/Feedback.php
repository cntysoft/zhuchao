<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author ZhiHui <liuyan2526@qq.com.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\ZhuChao\MarketMgr\AjaxHandler;
use Cntysoft\Kernel\App\AbstractHandler;
use Cntysoft\Kernel;
use App\ZhuChao\Service\Constant as SERVICE_CONST;
class Feedback extends AbstractHandler
{
   /**
    * 设置反馈信息状态
    * @param array $params
    * @return type
    */
   public function changFeedbackStatus(array $params)
   {
      return $this->getAppCaller()->call(
              SERVICE_CONST::MODULE_NAME, 
              SERVICE_CONST::APP_NAME, 
              SERVICE_CONST::APP_API_FEEDBACK_MGR, 
              'changeStatus', array(array($params['id']),$params['status']));
   }
}