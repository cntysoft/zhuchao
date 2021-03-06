<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author changwang    <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace FrontApi;
use Zend\Stdlib\ErrorHandler;
class ErrorType
{
   /**
    * 错误管理信息
    *
    * @var array $map
    */
   protected $map = array(
      'E_FRONT_AUTH_CODE_EXPIRE'   => array(10001, 'auth code expired'),
      'E_FRONT_AUTH_CODE_ERROR'    => array(10002, 'auth code error'),
      'E_USER_EXIST'               => array(10003, 'user is exist'),
      'E_SET_DEFAULT_ADDRESS_FAIL' => array(10004, 'set default address error'),
      'E_FAVORITE_HAVE_THIS_GOOD'  => array(10005, 'set favorite goods error'),
      'E_USER_HAVE_COUPON'         => array(10006, 'user have this coupon'),
      'E_HAS_CHAOGUO_STOCK'        => array(10007, 'has chaoguo stock'),
      'E_USER_LEVEL_ERROR'         => array(10008, 'user level error'),
      'E_STOCK_NONE'               => array(10009, 'stock is 0'),
      'E_COUPON_NUM_ERROR'         => array(10010, 'coupon num error'),
      'E_RECEIVE_COUPON_ERROR'     => array(10011, 'receive coupon error'),
      'E__USER_MSG_ERROR'          => array(10012, 'user msg error'),
      'E_WECHATPAY_CODE_ERROR'     => array(10013,' wechat pay error'),
      'E_GOODS_STATUS_NONE'        => array(10014, 'goods status error'),
      'E_ADD_ORDER_ERROR'  => array(10015, 'create order error')
   );

   /**
    * 提供这样的构造函数可以让其在APP中以数据的方式加载映射数据表
    *
    * @param array $map
    */
   public function __construct(array $map = array())
   {
      if (!empty($map)) {
         $this->map = $map;
      }
   }

   /**
    * 根据错误类型获取出错信息
    *
    * @param string $type
    * @return string
    */
   public function msg($type)
   {
      $tplArgs = func_get_args();
      array_shift($tplArgs);
      if (!array_key_exists($type, $this->map)) {
         trigger_error(sprintf('ERROR type %s is not exist', $type), E_USER_ERROR);
      }
      $tpl = $this->map[$type][1];
      array_unshift($tplArgs, $tpl);
      ErrorHandler::start();
      $msg = call_user_func_array('sprintf', $tplArgs);
      ErrorHandler::stop(true);
      return $msg;
   }

   /**
    * 获取原始的字符串信息
    *
    * @param string $type
    */
   public function rawMsg($type)
   {
      if (!array_key_exists($type, $this->map)) {
         trigger_error(sprintf('ERROR type %s is not exist', $type), E_USER_ERROR);
      }
      return $this->map[$type][1];
   }

   /**
    * 获取出错代码
    *
    * @param string $type
    * @return int
    * @throws Exception
    */
   public function code($type)
   {
      if (!array_key_exists($type, $this->map)) {
         trigger_error(sprintf('ERROR type %s is not exist', $type), E_USER_ERROR);
      }
      $data = $this->map[$type];
      return $data[0];
   }

   /**
    * 获取系统所有的错误类型名称
    *
    * @return array
    */
   public function errorTypes()
   {
      return array(array_keys($this->map));
   }

   /**
    * 获取错误实例
    *
    * @return \Cntysoft\Stdlib\ErrorType
    */
   public static function getInstance()
   {
      return new static();
   }

}