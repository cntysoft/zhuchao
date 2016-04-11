<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
*/
namespace App\ZhuChao\Service;

class Constant
{
   const MODULE_NAME = 'ZhuChao';
   const APP_NAME = 'Service';
   const APP_API_FEEDBACK_MGR = 'FeedbackMgr';

   //权限相关
   const PK_APP_KEY = 'Service';
   const PK_WIDGET_FEEDBACK = 'Feedback';

   //反馈的状态
   const FEEDBACK_STATUS_NEW = 1;
   const FEEDBACK_STATUS_READED = 2;
   const FEEDBACK_STATUS_DEAL = 3;
   
   const FEEDBACK_MIN_TIME = 1; //两次提交之间的最小时间间隔，分钟
}
