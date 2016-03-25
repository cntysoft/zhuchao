<?php
/**
 * 前台跟后台进行数据交流的接口
 *
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.sheneninfo.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
use Cntysoft\Phalcon\Mvc\AbstractController;
use ZhuChao\Framework\OpenApi\Provider\MobileApiServer as ApiServer;
use Cntysoft\Kernel;
use Cntysoft\Kernel\StdErrorType;
use Zend\Json\Json;
use Cntysoft\Kernel\ConfigProxy;
use ProviderMobileApi\ApiAuthorizer;
class MobileApiEntryController extends AbstractController
{
   /**
    *
    * @var \ZhuChao\Framework\OpenApi\Provider\FrontApiServer $apiServer
    */
   protected $apiServer = null;

   /**
    * 构造函数
    */
   public function initialize()
   {
      $gconf = ConfigProxy::getModuleConfig('Provider');
      $this->apiServer = new ApiServer($gconf->mobile_api_map->toArray(), new ApiAuthorizer());
   }

   public function indexAction()
   {
      try {
         //这里可能写入相关的错误信息
         /**
          * @TODO 这里没有任何的验证措施
          */
         $invokeArgs = $this->getInvokeInfo();
         $r = $this->apiServer->doCall($invokeArgs);
         return Kernel\generate_response(true, $r);
      } catch (\Exception $ex) {
         $extraError['errorCode'] = $ex->getCode();
         $extraError['errorInfo'] = (array) Kernel\g_data(\Cntysoft\API_CALL_EXP_KEY);
         return Kernel\generate_error_response(
                 str_replace(CNTY_ROOT_DIR, '', $ex->getMessage()), $extraError
         );
      }
   }

   /**
    * 获取调用相关信息
    *
    * @return boolean | array 当出现错误的时候返回false
    */
   protected function getInvokeInfo()
   {
      $cookies = explode(';', $this->di->get('request')->getHeader("Cookie"));
      foreach ($cookies as $value) {
         $cookie = explode('=', $value);
         if ((count($cookie) == 2) && !isset($_COOKIE[$cookie[0]])) {
            $_COOKIE[$cookie[0]] = urldecode($cookie[1]);
         }
      }
      $request = $this->di->get('request')->getPost();
      $requestData = Json::decode($request['data'], Json::TYPE_ARRAY);

      //检查存在性
      if (!isset($requestData[\Cntysoft\INVOKE_META_KEY])) {
         Kernel\throw_exception(new \Exception(
                 StdErrorType::msg('E_API_INVOKE_LEAK_META'), StdErrorType::code('E_API_INVOKE_LEAK_META')), \Cntysoft\STD_EXCEPTION_CONTEXT);
      }
     
      //检查格式
      $meta = $requestData[\Cntysoft\INVOKE_META_KEY];
      $security = isset($requestData[\Cntysoft\INVOKE_SECURITY_KEY]) ? $requestData[\Cntysoft\INVOKE_SECURITY_KEY] : array();
      if (isset($requestData[\Cntysoft\INVOKE_PARAM_KEY])) {
         $params = $requestData[\Cntysoft\INVOKE_PARAM_KEY];
      } else {
         $params = array();
      }
      if (!is_array($params)) {
         $params = (array) $params;
      }
      /**
       * 格式是否正确在API SERVER里面进行检测
       */
      return array(
         \Cntysoft\INVOKE_META_KEY     => $meta,
         \Cntysoft\INVOKE_PARAM_KEY    => $params,
         \Cntysoft\INVOKE_SECURITY_KEY => $security
      );
   }

}