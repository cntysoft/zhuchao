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
use Cntysoft\Framework\Utils\QrCode;
use App\ZhuChao\UserCenter\Constant as UserConst;
use App\ZhuChao\ShopFlow\Constant as ShopConst;
use Cntysoft\Framework\Pay\WeChat\NativePay;
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
   public function frontRegisterChkAction()
   {
      $drawer = new CheckCode(\Cntysoft\FRONT_USER_S_KEY_REG_CHK_CODE, 12, 4);
      $drawer->draw();
      exit;
   }

   /**
    * 前台用户忘记密码发送短信或者邮件验证码的时候验证码
    * 
    * @return void
    */
   public function frontForgetChkAction()
   {
      $drawer = new CheckCode(\Cntysoft\FRONT_USER_S_KEY_FORGET_CHK_CODE, 12, 4);
      $drawer->draw();
      exit;
   }

   /**
    * 微信支付二维码的生成路由
    */
   public function wechatQrcodeAction()
   {
      $orderId = $this->request->getQuery('data');
      $curUser = $this->getAppCaller()->call(
              UserConst::MODULE_NAME, UserConst::APP_NAME, UserConst::APP_API_ACL, 'getCurUser'
      );
      if (!$curUser) {
         header('location:/login.html');
      }
      $order = $this->getAppCaller()->call(
              ShopConst::MODULE_NAME, ShopConst::APP_NAME, ShopConst::APP_API_MGR, 'getOrderByUserAndNumber', array((int) $curUser->getId(), (int) $orderId)
      );

      if (!$order) {
         header('location:/404.html');
      }
      $nativePay = new NativePay();
      $cacher = $nativePay->getCacher();
      $qrcode = new QrCode();
      $codeUrl = $cacher->get($orderId);
      if (!$codeUrl) {
         $ret = $nativePay->getPayUrl('凤凰筑巢商品', (int) ($order->getPrice() * 100), $orderId);
         if ('SUCCESS' == $ret['return_code'] && isset($ret['code_url'])) {
            $codeUrl = $ret['code_url'];
         } else if ('OUT_TRADE_NO_USED' == $ret['err_code']) {
            $nativePay->closeOrder($orderId);
            header('location:/Utils/Index/wechatQrcode?data=' . $orderId);
         }
      }

      echo '<img alt="" src="' . $qrcode->renderWeChat($codeUrl) . '"/>';
      exit;
   }
   
   /**
    * 微信支付二维码的生成路由
    */
   public function wechatPayQrCodeAction()
   {
      $query = $this->request->getQuery();
      if(isset($query['data'])){
         $qrcode = new QrCode($query['data']);
         $size = isset($query['size']) ? $query['size'] : 340;
         $padding = isset($query['padding']) ? $query['padding'] : 10;
         $qrcode->setSize($size)->setPadding($padding)->setErrorCorrection('high');
         $qrcode->render();
      }
      exit;
   }
}