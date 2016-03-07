<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
*/
use Cntysoft\Phalcon\Mvc\AbstractController;
use KeleShop\Framework\OpenApi\EditApiServer as ApiServer;
use Cntysoft\Kernel;
use Cntysoft\Kernel\StdErrorType;
use Cntysoft\Kernel\ConfigProxy;
use EditApi\ApiAuthorizer;
use Phalcon\Http\Response;

class EditApiEntryController extends AbstractController
{
   /**
    *
    * @var \KeleShop\Framework\OpenApi\Server $apiServer
    */
   protected $apiServer = null;

   /**
    * 构造函数
    */
   public function initialize()
   {
      $gconf = ConfigProxy::getModuleConfig('Open');
      $this->apiServer = new ApiServer($gconf->edit_api_map->toArray(), new ApiAuthorizer());
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
         if('Phone' == $invokeArgs['REQUEST_META']['cls'] && 'uploadImg' == $invokeArgs['REQUEST_META']['method']){
            $reponse = new Response();
            $reponse->setContent($r[0]);
            return $reponse;
         }else{
            return Kernel\generate_response(true, $r);
         }
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
      $requestData = $this->di->get('request')->getPost();

      //检查存在性
      if (!isset($requestData[\Cntysoft\INVOKE_META_KEY]) || !$requestData[\Cntysoft\INVOKE_META_KEY]) {
         if(isset($requestData['cls']) && isset($requestData['method']) && 'uploadImg' == $requestData['method']){
            $requestData[\Cntysoft\INVOKE_META_KEY]['cls'] = $requestData['cls'];
            $requestData[\Cntysoft\INVOKE_META_KEY]['method'] = $requestData['method'];
         }else{
            Kernel\throw_exception(new \Exception(
            StdErrorType::msg('E_API_INVOKE_LEAK_META'), StdErrorType::code('E_API_INVOKE_LEAK_META')), \Cntysoft\STD_EXCEPTION_CONTEXT);
         }
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
      if(isset($requestData['method']) && 'uploadImg' == $requestData['method'] && isset($requestData['callback']) && $requestData['callback']){
         $params['callback'] = $requestData['callback'];
      }
      
      /**
       * 格式是否正确在API SERVER里面进行检测
       */
      return array(
         \Cntysoft\INVOKE_META_KEY  => $meta,
         \Cntysoft\INVOKE_PARAM_KEY => $params,
         \Cntysoft\INVOKE_SECURITY_KEY => $security
      );
   }
}
