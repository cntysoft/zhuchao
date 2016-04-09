<?php
/**
 * Cntysoft Web Platform 中国领先的web平台
 *
 * @category   Cntysoft
 * @author     SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Ds\ContentModel;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractDsScript;
use Cntysoft\Framework\Utils\ChinaArea;
use Cntysoft\Kernel;
use App\ZhuChao\Provider\Constant as UserContent;
class CompanyInfo extends AbstractDsScript
{
   /**
    * 保存中国省市区编码处理对象的静态属性
    * 
    * @var Cntysoft\Framework\Utils\ChinaArea
    */
   protected static $chinaArea = null;

   public function load()
   {
      $company = $this->appCaller->call(UserContent::MODULE_NAME, UserContent::APP_NAME, UserContent::APP_API_MANAGER, 'getProviderCompany', array(Kernel\get_site_id()));
      if (!$company) {
         return array(
            'name'             => '',
            'type'             => '',
            'tradeMode'        => '',
            'province'         => '',
            'city'             => '',
            'district'         => '',
            'address'          => '',
            'postCode'         => '',
            'website'          => '',
            'description'      => '',
            'logo'             => '',
            'status'           => 1,
            'profileId'        => '',
            'customer'         => '',
            'brand'            => '',
            'registerCapital'  => '',
            'registerYear'     => '',
            'registerProvince' => '',
            'registerCity'     => '',
            'registerDistrict' => '',
            'legalPerson'      => '',
            'bank'             => '',
            'bankAccount'      => ''
         );
      }
      $companyProfile = $company->getProfile();
      $companyProvider = $company->getProvider();
      $company = $company->toarray();
      $company['logo'] = $this->getImgcdn($company['logo'], 100, 54);
      $company['PCD'] = $this->getChPcd($company['province'], $company['city'], $company['district']);
      $companyProfile = $companyProfile->toarray();
      $company['contact'] = $companyProvider ? $companyProvider->getPhone() : '';
      $company['providername'] = $companyProvider ? $companyProvider->getName() : '';
      $company['providerqq'] = $companyProvider ? $companyProvider->getProfile()->getQq() : '';
      $company['provideremail'] = $companyProvider ? $companyProvider->getProfile()->getEmail() : '';
      $ret = array_merge($company, $companyProfile);
      $ret['company'] = $ret['id'];
      unset($ret['id']);
      unset($ret['providerId']);
      unset($ret['inputTime']);
      return $ret;
   }

   /**
    * 获得图片url
    * @param type $item
    * @param type $width
    * @param type $height
    * @return string
    */
   public function getImgcdn($url, $width, $height)
   {
      if (!isset($url) || empty($url)) {
         $url = 'Statics/Skins/Pc/Images/lazyicon.png';
      }
      return Kernel\get_image_cdn_url_operate($url, array('w' => $width, 'h' => $height, 'Q' => 100));
   }

   /**
    * 获取省市区信息
    * 
    * @param integer $code1
    * @param integer $code2
    * @param integer $code3
    * @return array()
    */
   public function getChPcd($code1, $code2, $code3)
   {
      $chinaArea = $this->getChinaArea();
      $ret = array();
      if ($code1) {
         array_push($ret, $chinaArea->getArea($code1));
      }
      if ($code2 && !empty($ret)) {
         $ret[0] == $chinaArea->getArea($code2) ? '' : array_push($ret, $chinaArea->getArea($code2));
      }
      if ($code3) {
         array_push($ret, $chinaArea->getArea($code3));
      }
      return $ret;
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

}