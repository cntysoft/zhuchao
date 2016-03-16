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

class UtilsController extends AbstractController
{
   /**
    * 注册页图片验证码生成
    */
   public function registerChkCodeAction()
   {
      $drawer = new CheckCode(\Cntysoft\FRONT_USER_S_KEY_REG_CHK_CODE, 12, 4);
      $drawer->draw();
   }
   /**
    * 找回密码页图片验证码生成
    */
   public function forgetChkCodeAction()
   {
      $drawer = new CheckCode(\Cntysoft\FRONT_USER_S_KEY_FORGET_CHK_CODE, 12, 4);
      $drawer->draw();
   }
}