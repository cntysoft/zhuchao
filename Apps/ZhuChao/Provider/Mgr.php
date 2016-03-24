<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\ZhuChao\Provider;
use Cntysoft\Kernel\App\AbstractLib;
use Cntysoft\Kernel;
use Cntysoft\Framework\Utils\ChinaArea;
use App\ZhuChao\Provider\Model\BaseInfo as BaseModel;
use App\ZhuChao\Provider\Model\Profile as ProfileModel;
use App\ZhuChao\Provider\Model\Company as CompanyModel;
use App\ZhuChao\Provider\Model\CompanyProfile as CompanyProfileModel;
use Phalcon\Db\Adapter\Pdo\MySql;
use Cntysoft\Kernel\ConfigProxy;
/**
 * 站点管理员角色管理
 */
class Mgr extends AbstractLib
{
   /**
    * 保存中国省市区编码处理对象的静态属性
    * 
    * @var Cntysoft\Framework\Utils\ChinaArea
    */
   protected static $chinaArea = null;

   /**
    * 添加供应商信息
    * 
    * @param array $data
    */
   public function addProvider($data)
   {
      unset($data['id']);
      $baseInfo = new BaseModel();
      $requires = $baseInfo->getRequireFields(array('id', 'profileId'));
      $data += array(
         'loginTimes'             => 0,
         'status'                 => Constant::PROVIDER_STATUS_NORMAL, //默认正常
         'loginErrorTimes'        => 0,
         'registerTime'           => time(),
         'lastLoginTime'          => time(),
         'currentLoginErrorTimes' => 0,
         'lastLoginIp'            => ''
      );
      Kernel\ensure_array_has_fields($data, $requires);
      if ($this->providerNameExist($data['name'])) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_PROVIDER_NAME_EXIST'), $errorType->code('E_PROVIDER_NAME_EXIST')), $this->getErrorTypeContext());
      }
      if ($this->providerPhoneExist($data['phone'])) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_PROVIDER_PHONE_EXIST'), $errorType->code('E_PROVIDER_PHONE_EXIST')), $this->getErrorTypeContext());
      }

      $di = Kernel\get_global_di();
      $security = $di->getShared('security');
      $data['password'] = $security->hash($data['password']);
      //处理用户的基本信息和详细信息
      $profile = new ProfileModel();
      $profileDataFields = $profile->getDataFields();
      $profileData = array();
      foreach ($profileDataFields as $key) {
         if (array_key_exists($key, $data)) {
            $profileData[$key] = $data[$key];
            unset($data[$key]);
         }
      }

      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         $profile->assignBySetter($profileData);
         $profile->create();
         $baseInfo->setProfileId($profile->getId());
         $baseInfo->assignBySetter($data);
         $baseInfo->create();

         $db->commit();
      } catch (\Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
   }

   /**
    * 
    * @param int $id
    * @param array $data
    */
   public function updateProvider($id, $data)
   {
      $id = (int) $id;
      unset($data['id']);
      $provider = $this->getProvider($id);
      if (!$provider) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_USER_IS_NOT_EXIST', $id), $errorType->code('E_USER_IS_NOT_EXIST')
                 ), $this->getErrorTypeContext());
      }
      $profile = $provider->getProfile();
      $profileDataFields = $profile->getDataFields();
      $profileData = array();
      foreach ($profileDataFields as $key) {
         if (array_key_exists($key, $data)) {
            $profileData[$key] = $data[$key];
            unset($data[$key]);
         }
      }
      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         if (isset($data['password'])) {
            $di = Kernel\get_global_di();
            $security = $di->getShared('security');
            $data['password'] = $security->hash($data['password']);
         }
         $profile->assignBySetter($profileData);
         $profile->save();

         $provider->assignBySetter($data);
         $provider->save();
         $db->commit();
      } catch (\Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
   }

   /**
    * 查看用户名称是否存在，存在返回 true，不存在返回 false
    * 
    * @param string $name
    * @return boolean
    */
   public function providerNameExist($name)
   {
      $provider = BaseModel::findFirst("name = '$name'");
      return $provider ? true : false;
   }

   /**
    * 查看用户手机号码是否存在，存在返回 true，不存在返回 false
    * 
    * @param string $phone
    * @return boolean
    */
   public function providerPhoneExist($phone)
   {
      $provider = BaseModel::findFirst("phone = '$phone'");
      return $provider ? true : false;
   }

   /**
    * 修改供应商的状态
    * 
    * @param int $id
    * @param int $status
    * @return boolean
    */
   public function changeStatus($id, $status)
   {
      $provider = BaseModel::findFirst($id);
      if (!$provider) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_USER_NOT_EXIST'), $errorType->code('E_USER_NOT_EXIST')), $this->getErrorTypeContext());
      }

      $provider->setStatus($status);
      return $provider->save();
   }

   /**
    * 获取供应商信息
    * 
    * @param type $id
    * @return App\ZhuChao\Provider\Model\BaseInfo
    */
   public function getProvider($id)
   {
      $provider = BaseModel::findFirst($id);
      if (!$provider) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_USER_NOT_EXIST'), $errorType->code('E_USER_NOT_EXIST')), $this->getErrorTypeContext());
      }

      return $provider;
   }

   /**
    * 修改供应商企业的状态
    * 
    * @param int $id
    * @param int $status
    * @return boolean
    */
   public function changeCompanyStatus($id, $status)
   {
      $providercom = CompanyModel::findFirst($id);
      if (!$providercom) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_PROVIDER_COMPANY_NOT_EXIST'), $errorType->code('E_PROVIDER_COMPANY_NOT_EXIST')), $this->getErrorTypeContext());
      }

      $providercom->setStatus($status);
      return $providercom->save();
   }

   /**
    * 获取供应商企业信息
    * 
    * @param integer $id
    * @return App\ZhuChao\Provider\Model\Company
    */
   public function getProviderCompany($id)
   {
      $providercom = CompanyModel::findFirst($id);
      if (!$providercom) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_PROVIDER_COMPANY_NOT_EXIST'), $errorType->code('E_PROVIDER_COMPANY_NOT_EXIST')), $this->getErrorTypeContext());
      }

      return $providercom;
   }

   /**
    * 根据名称获取供应商
    * 
    * @param string $name
    * @return 
    */
   public function getProviderByName($name)
   {
      return BaseModel::findFirst("name = '$name'");
   }

   /**
    * 根据手机号码获取供应商
    * 
    * @param string $phone
    * @return 
    */
   public function getProviderByPhone($phone)
   {
      return BaseModel::findFirst("phone = '$phone'");
   }

   /**
    * 添加供应商企业信息
    * 
    * @param array $data
    */
   public function addProviderCompany($data)
   {
      unset($data['id']);
      $companyInfo = new CompanyModel();
      $requires = $companyInfo->getRequireFields(array('id', 'profileId'));
      $data += array(
         'inputTime' => time()
      );
      Kernel\ensure_array_has_fields($data, $requires);
      //处理用户的基本信息和详细信息
      $profile = new CompanyProfileModel();
      $profileDataFields = $profile->getDataFields();
      $profileData = array();
      foreach ($profileDataFields as $key) {
         if (array_key_exists($key, $data)) {
            $profileData[$key] = $data[$key];
            unset($data[$key]);
         }
      }

      //新添加企业信息的时候需要更新缓存
      if (isset($data['subAttr'])) {
         $cacher = $this->getAppObject()->getCacheObject();
         $cacher->delete(Constant::SITE_CACHE_KEY);
      }

      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         $profile->assignBySetter($profileData);
         $profile->create();
         $companyInfo->setProfileId($profile->getId());
         $companyInfo->assignBySetter($data);
         $companyInfo->create();

         $db->commit();
      } catch (\Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
   }

   /**
    * 修改供应商企业信息
    * 
    * @param int $id
    * @param array $data
    */
   public function updateProviderCompany($id, $data)
   {
      $id = (int) $id;
      unset($data['id']);
      $providercom = $this->getProviderCompany($id);
      if (!$providercom) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_PROVIDER_COMPANY_NOT_EXIST', $id), $errorType->code('E_PROVIDER_COMPANY_NOT_EXIST')
                 ), $this->getErrorTypeContext());
      }
      $profile = $providercom->getProfile();
      $profileDataFields = $profile->getDataFields();
      $profileData = array();
      foreach ($profileDataFields as $key) {
         if (array_key_exists($key, $data)) {
            $profileData[$key] = $data[$key];
            unset($data[$key]);
         }
      }
      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         $profile->assignBySetter($profileData);
         $profile->save();

         $providercom->assignBySetter($data);
         $providercom->save();
         $db->commit();
      } catch (\Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
   }

   /**
    * 获取所有的省份编码信息
    * 
    * @return array
    */
   public function getProvinces()
   {
      $chinaArea = $this->getChinaArea();
      return $chinaArea->getProvinces();
   }

   /**
    * 获取指定code的下级地区信息
    * 
    * @param integer $code
    * @return array
    */
   public function getArea($code)
   {
      $chinaArea = $this->getChinaArea();
      return $chinaArea->getChildArea((int) $code);
   }

   /**
    * 获取企业信息
    * 
    * @param int $id
    * @return 
    */
   public function getCompanyById($id)
   {
      $company = CompanyModel::findFirst($id);
      if (!$company) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_PROVIDER_COMPANY_NOT_EXIST'), $errorType->code('E_PROVIDER_COMPANY_NOT_EXIST')), $this->getErrorTypeContext());
      }

      return $company;
   }

   /**
    * 获取省市区管理对象
    * 
    * @return Cntysoft\Framework\Utils\ChinaArea
    */
   protected function getChinaArea()
   {
      if (null == self::$chinaArea) {
         self::$chinaArea = new ChinaArea();
      }

      return self::$chinaArea;
   }

   /**
    * 添加企业的网站
    * 
    * @param int $companyId
    * @param string $subAttr
    * @return boolean
    */
   public function createSite($companyId, $subAttr)
   {
      $company = $this->getCompanyById($companyId);
      $siteDbName = \Cntysoft\ZHUCHAO_SITE_DB_PREFIX . $companyId;
      
      $executeTime  = ini_get('max_execution_time');
      set_time_limit(0);
      $global = ConfigProxy::getGlobalConfig();
      $dbConfig = $global->db->toArray();
      $connection = new MySql($dbConfig);
      //创建数据库
      $connection->query("CREATE DATABASE $siteDbName");
      //向数据库中插入数据
      $dbConfig['dbname'] = $siteDbName;
      $connection->connect($dbConfig);
      $sqls = include $this->getAppObject()->getDataDir() . DS . 'SiteSqlData.php';
      foreach ($sqls as $sql) {
         $connection->execute($sql);
      }
      $connection->close();
      
      //最后将域名插入企业信息表中
      $company->setSubAttr($subAttr);
      ini_set('max_execution_time', $executeTime);
      return $company->save();
   }
   
   /**
    * 验证二级域名是否存在
    * 
    * @param string $subAttr
    * @return boolean
    */
   public function checkSubAttr($subAttr)
   {
      $company = CompanyModel::findFirst("subAttr = '$subAttr' ");
      
      return $company ? true : false;
   }

}