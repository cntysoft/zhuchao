<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.sheneninfo.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\ZhuChao\Service\AjaxHandler;
use Cntysoft\Kernel\App\AbstractHandler;
use App\ZhuChao\Service\Constant as SERVICE_CONST;
class Feedback extends AbstractHandler
{
   /**
    * 获取反馈信息列表
    * @param array $params
    * @return type
    */
   public function getFeedbackList(array $params)
   {
      $feedbacks = $this->getAppCaller()->call(
              SERVICE_CONST::MODULE_NAME, SERVICE_CONST::APP_NAME, SERVICE_CONST::APP_API_FEEDBACK_MGR, 'getFeedbackList', array(array(), true, 'inputTime DESC', $params['limit'], $params['start']));
      $ret = array(
         'total' => $feedbacks['total']
      );
      foreach ($feedbacks['item'] as $feedback) {
         $child = array(
            'id' => $feedback->getId(),
            'type' => $feedback->getType(),
            'text' => $feedback->getText(),
            'name' => $feedback->getName(),
            'phone' => $feedback->getPhone(),
            'email' => $feedback->getEmail(),
            'qq' => $feedback->getQq(),
            'inputTime' => date('Y-m-d', $feedback->getInputTime()),
            'status' => $feedback->getStatus(),
            'identify' => $feedback->getIdentify()
         );
         $ret['items'][] = $child;
      }
      return $ret;
   }

}