<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
 */
use Cntysoft\Phalcon\Mvc\AbstractController;
use Cntysoft\Framework\Utils\CheckCode;
/**
 * 一些验证码信息
 */
class UtilsController extends AbstractController
{
   /**
    * 前台用户注册发送短信或者邮件验证码的时候验证码
    * 
    * @return void
    */
   public function registerChkAction()
   {
      $drawer = new CheckCode(\Cntysoft\PROVIDER_USER_S_KEY_REG_CHK_CODE, 12, 4);
      $drawer->draw();
      exit;
   }

   /**
    * 前台用户注册发送短信或者邮件验证码的时候验证码
    * 
    * @return void
    */
   public function loginChkAction()
   {
      $drawer = new CheckCode(\Cntysoft\PROVIDER_USER_S_KEY_LOGIN_CHK_CODE, 12, 4);
      $drawer->draw();
      exit;
   }

   /**
    * 前台用户忘记密码发送短信或者邮件验证码的时候验证码
    * 
    * @return void
    */
   public function forgetChkAction()
   {
      $drawer = new CheckCode(\Cntysoft\PROVIDER_USER_S_KEY_FORGET_CHK_CODE, 12, 4);
      $drawer->draw();
      exit;
   }

}