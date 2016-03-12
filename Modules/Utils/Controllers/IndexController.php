<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
use Cntysoft\Phalcon\Mvc\AbstractController;
use Cntysoft\Framework\Utils\CheckCode;
/**
 * 一些功能
 */
class IndexController extends AbstractController
{
   /**
    * 站点验证码生成
    */
   public function siteManagerChkCodeAction()
   {
      $drawer = new CheckCode(\Cntysoft\SITEMANAGER_S_KEY_CHK_CODE, 12, 4);
      $drawer->draw();
      exit;
   }

   /**
    * 前台用户注册发送短信或者邮件验证码的时候验证码
    * 
    * @return void
    */
   public function providerRegisterChkAction()
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
   public function providerLoginChkAction()
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
   public function providerForgetChkAction()
   {
      $drawer = new CheckCode(\Cntysoft\PROVIDER_USER_S_KEY_FORGET_CHK_CODE, 12, 4);
      $drawer->draw();
      exit;
   }

}