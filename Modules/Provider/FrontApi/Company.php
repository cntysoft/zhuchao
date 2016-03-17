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
/**
 * 主要是处理采购商相关的的Ajax调用
 * 
 * @package ProviderFrontApi
 */
class Company extends AbstractScript
{
   /**
    * 用户添加企业信息接口
    * 
    * @param array $params
    */
   public function addCompany($params)
   {
      $this->checkRequireFields($params, array('name', 'type', 'products', 'tradeMode'));
      unset($params['id']);
      unset($params['providerId']);
      $user = $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR, 'getCurUser');
      $params['providerId'] = $user->getId();
      $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR, 'addProviderCompany', array($params));
   }
   
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
      unset($params['name']);
      
      $user = $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR, 'getCurUser');
      $company = $user->getCompany();
      $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR, 'updateProviderCompany', array($company->getId(), $params));
   }
}