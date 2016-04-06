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
use App\Yunzhan\Setting\Constant as SETTING_CONST;
class SiteSetting extends AbstractDsScript
{
	public function load()
	{
		$config = $this->appCaller->call(
				  SETTING_CONST::MODULE_NAME, SETTING_CONST::APP_NAME, SETTING_CONST::APP_API_CFG, 'getItemsByGroup', array('Site')
		);

		$ret = array('banner' => array());
		foreach ($config as $one) {
			$key = $one->getKey();
			$value = $one->getValue();
			if ('Banner' == $key) {
				$value = unserialize($value);
				foreach ($value as $val) {
					$ret['banner'][] = array(
						'image'	 => $val[0],
						'id'		 => $val[1],
						'url'		 => $val[2]
					);
				}
			}
		}
		$config = $this->appCaller->call(
				  SETTING_CONST::MODULE_NAME, SETTING_CONST::APP_NAME, SETTING_CONST::APP_API_CFG, 'getItemsByGroup', array('Seo')
		);
		$ret['description'] = '';
		$ret['keywords'] = '';
		foreach ($config as $one) {
			$key = $one->getKey();
			$value = $one->getValue();
			if ('description' == $key) {
				$ret['description'] = $value;
			} else if('keywords' == $key){
				$ret['keywords'] = $value;
			}
		}
      
      $config = $this->appCaller->call(
				  SETTING_CONST::MODULE_NAME, SETTING_CONST::APP_NAME, SETTING_CONST::APP_API_CFG, 'getItemsByGroup', array('Nav')
		);
      $ret['product'] = '';
      $ret['case'] = '';
      $ret['news'] = '';
      $ret['zhaopin'] = '';
      $ret['aboutus'] = '';
      foreach ($config as $one) {
         $key = $one->getKey();
			$value = $one->getValue();
         if ('product' == $key) {
				$ret['product'] = $value;
			} else if('case' == $key){
				$ret['case'] = $value;
			} else if('news' == $key){
				$ret['news'] = $value;
			} else if('zhaopin' == $key){
				$ret['zhaopin'] = $value;
			} else if('aboutus' == $key){
				$ret['aboutus'] = $value;
			}
      }
		return $ret;
	}

}
