<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace ProviderFrontApi;
use ZhuChao\Framework\OpenApi\AbstractScript;
use App\ZhuChao\Provider\Constant as P_CONST;
use Cntysoft\Kernel;
/**
 * 主要是处理采购商相关的的Ajax调用
 * 
 * @package ProviderFrontApi
 */
class Company extends AbstractScript
{
   /**
    * 修改企业信息
    * 
    * @param array $params
    */
   public function updateCompany($params)
   {
      //删除不能修改的字段
      unset($params['id']);
      unset($params['providerId']);

      if(isset($params['logo'])) {
         $cndServer = Kernel\get_image_cdn_server_url() .'/';
         $image = explode('@', str_replace($cndServer, '', $params['logo']));
         $params['logo'] = $image[0];
      }
      $user = $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR, 'getCurUser');
      $company = $user->getCompany();

      //已经存在企业信息
      if ($company) {
         unset($params['name']); //企业名称不允许修改
         $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MANAGER, 'updateProviderCompany', array($company->getId(), $params));
      } else {
         $params['providerId'] = $user->getId();
         $params['status'] = P_CONST::COMPANY_STATUS_NORMAL;
         $params['type'] = 9;
         $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MANAGER, 'addProviderCompany', array($params));
      }
   }

}