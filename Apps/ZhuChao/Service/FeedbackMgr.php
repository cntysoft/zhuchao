<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.sheneninfo.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
*/
namespace App\ZhuChao\Service;
use Cntysoft\Kernel\App\AbstractLib;
use Cntysoft\Kernel;
use App\ZhuChao\Service\Model\Feedback as FeedbackModel;

class FeedbackMgr extends AbstractLib
{
   /**
    * 添加反馈信息
    * 
    * @param array $params
    */
   public function addFeedback(array $params)
   {
      $this->checkRequireFields($params, array('type', 'text', 'identify', 'status'));
      
      $feedback = new FeedbackModel();
      $time = time();
      
      if(!$this->checkFeedback($params['identify'], $time)){
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception($errorType->msg('E_FEEDBACK_LOW_MIN_TIME'), $errorType->code('E_FEEDBACK_LOW_MIN_TIME')), $this->getErrorTypeContext());
      }
      
      $data = array(
         'type' => (int)$params['type'],
         'text' => $params['text'],
         'identify' => $params['identify'],
         'status' => Constant::FEEDBACK_STATUS_NEW,
         'inputTime' => $time,
         'name'  => isset($params['name']) ? $params['name'] : '',
         'email' => isset($params['email']) ? $params['email'] : '',
         'phone' => isset($params['phone']) ? $params['phone'] : '',
         'qq'    => isset($params['qq']) ? $params['qq'] : ''
      );
      
      $feedback->assignBySetter($data);
      $feedback->create();
   }
   
   /**
    * 检查反馈是否符合规则
    * 
    * @param string $identify
    * @param integer $time
    * @return boolean
    */
   public function checkFeedback($identify, $time)
   {
      $feedback = FeedbackModel::findFirst(array(
         'identify=?0',
         'orderBy' => 'id DESC',
         'bind' => array(
            0 => $identify
         )
      ));
      
      if(!$feedback || $time - $feedback->getInputTime() > Constant::FEEDBACK_MIN_TIME * 60){
         return true;
      }else{
         return false;
      }
   }
   
   /**
    * 修改反馈信息的状态
    * 
    * @param array $ids
    * @param integer $status
    */
   public function changeStatus(array $ids, $status)
   {
      if(!in_array($status, array(Constant::FEEDBACK_STATUS_NEW, Constant::FEEDBACK_STATUS_READED, Constant::FEEDBACK_STATUS_DEAL))){
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception($errorType->msg('E_FEEDBACK_STATUS_ERROR'), $errorType->code('E_FEEDBACK_STATUS_ERROR')), $this->getErrorTypeContext());
      }
      
      $cond = FeedbackModel::generateRangeCond('id', $ids);
      $list = FeedbackModel::find(array($cond));
      foreach($list as $one){
         $one->setStatus($status);
         $one->update();
      }
   }
   
}