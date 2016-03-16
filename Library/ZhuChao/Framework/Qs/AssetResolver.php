<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace ZhuChao\Framework\Qs;
use Cntysoft\Framework\Qs\AssetResolverInterface;
use ZhuChao\Kernel\StdHtmlPath;
use Cntysoft;
class AssetResolver implements AssetResolverInterface
{
   const DEVICE_PC = 'Pc';
   const DEVICE_MOBILE = 'Mobile';

   /**
    * 当前请求的设备类型
    * 
    * @var string
    */
   protected $deviceType = null;

   /**
    *  静态文件的基本路径
    *
    * @return string
    */
   public function getAssetBasePath()
   {
      if (SYS_RUNTIME_MODE == SYS_RUNTIME_MODE_DEBUG) {
         return StdHtmlPath::getSkinPath();
      } else {
         return 'http://statics-res.fhzc.com';
      }
   }

   /**
    * Css文件的基本路径
    *
    * @return string
    */
   public function getCssBasePath()
   {
      $basePath = $this->getAssetBasePath();
      $deviceType = $this->detactDeviceType();
      return $basePath . '/' . $deviceType . '/' . Cntysoft\CSS;
   }

   /**
    * Image文件的基本路径
    *
    * @return string
    */
   public function getImageBasePath()
   {
      $basePath = $this->getAssetBasePath();
      $deviceType = $this->detactDeviceType();
      return $basePath . '/' . $deviceType . '/' . Cntysoft\IMAGE;
   }

   /**
    *  Js文件的基本路径
    *
    * @return string
    */
   public function getJsBasePath()
   {
      $basePath = StdHtmlPath::getSkinPath();
      $deviceType = $this->detactDeviceType();
      return $basePath . '/' . $deviceType . '/' . Cntysoft\JS;
   }

   /**
    * 探测访问设备的类型
    *
    * @return string
    */
   protected function detactDeviceType()
   {
      if (null == $this->deviceType) {
         $agent = $_SERVER['HTTP_USER_AGENT'];
         $iphone = strstr(strtolower($agent), 'mobile');
         $android = strstr(strtolower($agent), 'android');
         $windowsPhone = strstr(strtolower($agent), 'phone');
         $androidTablet = false;
         if (strstr(strtolower($agent), 'android')) {
            if (!strstr(strtolower($agent), 'mobile')) {
               $androidTablet = true;
            }
         }
         $ipad = strstr(strtolower($agent), 'ipad');
         if ($androidTablet || $ipad) {
            $this->deviceType = self::DEVICE_MOBILE;
         } elseif ($iphone && !$ipad || $android && !$androidTablet || $windowsPhone) { //If it's a phone and NOT a tablet
            $this->deviceType = self::DEVICE_MOBILE;
         } else {
            $this->deviceType = self::DEVICE_PC;
         }
      }
      return $this->deviceType;
   }

}