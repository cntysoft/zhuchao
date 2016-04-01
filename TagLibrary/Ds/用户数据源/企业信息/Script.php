<?php
/**
 * Cntysoft Web Platform 中国领先的web平台
 *
 * @category   Cntysoft
 * @author     SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Ds\UserModel;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractDsScript;
use App\ZhuChao\Provider\Constant as UserContent;
use Cntysoft\Kernel;
class CompanyInfo extends AbstractDsScript
{
   public function load()
   {
      $user = $this->appCaller->call(UserContent::MODULE_NAME, UserContent::APP_NAME, UserContent::APP_API_MGR, 'getCurUser',array());
      $company = $user->getCompany();
      if(!$company){
         return array(
            'name' => '',
            'type' => '',
            'tradeMode' => '',
            'products' => '',
            'city' => '',
            'district' => '',
            'address' => '',
            'postCode' => '',
            'website' => '',
            'description' => '',
				'keywords' => '',
            'logo' => '',
            'status' => 1,
            'profileId' => '',
            'customer' => '',
            'brand' => '',
            'registerCapital' => '',
            'registerYear' => '',
            'registerProvince' => '',
            'registerCity' => '',
            'registerDistrict' => '',
            'legalPerson' => '',
            'bank' => '',
            'bankAccount' => ''
         );
      }
      $companyProfile = $company->getProfile();
      $company = $company->toarray();
      $companyProfile = $companyProfile->toarray();
      $ret = array_merge($company,$companyProfile);
      $ret['logo'] = Kernel\get_image_cdn_url($ret['logo']);
      unset($ret['id']);
      unset($ret['providerId']);
      unset($ret['inputTime']);
      
      $products = $ret['products'];
      $ret['products'] = explode(',', $products);
      return $ret;
   }

}