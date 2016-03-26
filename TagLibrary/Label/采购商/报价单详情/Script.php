<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Arvin <cntyfeng@163.com>
 * @copyright  Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Label\Buyer;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractLabelScript;
use App\ZhuChao\MessageMgr\Constant as MESSAGEMGR_CONST;
use App\ZhuChao\Product\Constant as PRODUCT_CONST;
use Cntysoft\Kernel;

class Quotation extends AbstractLabelScript
{
   public function getQuotation()
   {
      $routeInfo = $this->getRouteInfo();
      $id = $routeInfo['quotationId'];

      return $this->appCaller->call(
         MESSAGEMGR_CONST::MODULE_NAME,
         MESSAGEMGR_CONST::APP_NAME,
         MESSAGEMGR_CONST::APP_API_OFFER,
         'getInquiryAndOffer',
         array($id)
      );
   }
   
   public function getProduct($id)
   {
      return $this->appCaller->call(
         PRODUCT_CONST::MODULE_NAME,
         PRODUCT_CONST::APP_NAME,
         PRODUCT_CONST::APP_API_PRODUCT_MGR,
         'getProductById',
         array($id)
      );
   }
   
   /**
    * 获取cdn图片的地址
    * 
    * @param string $imgUrl
    * @param array $arguments
    * @return string
    */
   public function getImageCdnUrl($imgUrl, $arguments = array())
   {
      if($imgUrl){
         return Kernel\get_image_cdn_url_operate($imgUrl, $arguments);
      }else{
         return 'Statics/Skins/Pc/Images/lazyicon.png';
      }
   }
   
   /**
    * 获取商品的网址
    * 
    * @param string $number
    * @return string
    */
   public function getProductUrl($number)
   {
      return 'http://'.\Cntysoft\RT_SYS_SITE_NAME.'/item/'.$number.'.html';
   }
   
   /**
    * 获取商品的网址
    * 
    * @param string $subAttr
    * @return string
    */
   public function getCompanyUrl($subAttr)
   {
      return 'http://'. $subAttr . '.' .\Cntysoft\RT_SYS_SITE_NAME;
   }

}