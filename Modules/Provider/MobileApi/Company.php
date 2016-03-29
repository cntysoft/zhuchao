<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace ProviderMobileApi;
use ZhuChao\Framework\OpenApi\AbstractScript;
use App\ZhuChao\Provider\Constant as P_CONST;
use Cntysoft\Kernel;
use Cntysoft\Framework\Utils\ChinaArea;
/**
 * 主要是处理采购商相关的的Ajax调用
 * 
 * @package ProviderFrontApi
 */
class Company extends AbstractScript
{
   public  $china;
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

      if (isset($params['logo'])) {
         $cndServer = Kernel\get_image_cdn_server_url() . '/';
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

   /**
    * 获得当前登录用户的企业信息
    */
   public function getCompanyInfo()
   {
      $user = $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR, 'getCurUser');
      $company = $user->getCompany();
      if ($company) {
         $companyProfile = $company->getProfile();
         $companyProfile = $companyProfile->toarray();
         $company = $company->toarray();
         $company = array_merge($company, $companyProfile);
         unset($company['id']);
         unset($company['providerId']);
         $company['providerAddress'] = $this->getArea($company['province']) . $this->getArea($company['city']) . $this->getArea($company['district']);
         $company['registerAddress'] = $this->getArea($company['registerProvince']) . $this->getArea($company['city']) . $this->getArea($company['registerDistrict']);
         $company['logo'] = \Cntysoft\Kernel\get_image_cdn_url($company['logo'],180,90);
         return $company;
      }
      return array();
   }

   /**
    * 获取制定Code的地区信息
    * 
    * @param array $params
    * @return string
    */
   public function getArea($code)
   {
      if(!$this->china){
         $this->china = new ChinaArea();
      }
      return $this->china->getArea($code);
   }
   
   /**
    * 开通店铺
    * 
    * @param array $params
    */
   public function siteSetting($params)
   {
      $this->checkRequireFields($params, array('subAttr'));
      $provider = $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR, 'getCurUser');
      //判断企业信息是否存在
      $company = $provider->getCompany();
      if (!$company) {
         $errorType = new ErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_COMPANY_NOT_EXIST'), $errorType->code('E_COMPANY_NOT_EXIST')
         ));
      }

      if ($company->getSubAttr()) {
         $errorType = new ErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_SUBATTR_CANNOT_CHANGE'), $errorType->code('E_SUBATTR_CANNOT_CHANGE')
         ));
      }
      
      //判断二级域名是否合法
      $subAttr = $params['subAttr'];
      if ($this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MANAGER, 'checkSubAttr', array($subAttr))) {
         $errorType = new ErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_SUBATTR_EXIST'), $errorType->code('E_SUBATTR_EXIST')
         ));
      }
      
      return $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MANAGER, 'createSite', array($company->getId(), $subAttr));
   }

}