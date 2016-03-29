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
use Cntysoft\Kernel;
use ZhuChao\Framework\Net\Upload;
use Cntysoft\Kernel\ConfigProxy;
/**
 * 处理系统上传
 *
 * Class WebUploader
 * @package SysApiHandler
 */
class Uploader extends AbstractScript
{
   /**
    * 处理上传图片
    * 
    * @return array
    */
   public function upload($param)
   {
      header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
      header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
      header("Cache-Control: no-store, no-cache, must-revalidate");
      header("Cache-Control: post-check=0, pre-check=0", false);
      header("Pragma: no-cache");
      @set_time_limit(5 * 60);

      $this->checkRequireFields($param, array(
         'uploadDir'
      ));
      $cfg = ConfigProxy::getFrameworkConfig('Net');
      $params = array(
         'uploadDir'     => $param['uploadDir'],
         'overwrite'     => false,
         'enableFileRef' => true,
         'randomize'     => true,
         'createSubDir'  => true,
         'enableNail'    => false,
         'useOss'        => $cfg->upload->useOss
      );
      $request = $this->di->get('request');
      //这里不做检查，相关参数没指定已经有默认的参数了
      //探测分片信息
      $chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
      $chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 1;
      $params['chunk'] = $chunk;
      $params['total_chunk'] = $chunks;

      $uploadPath = $params['uploadDir'];
      unset($params['uploadDir']);
      $params['uploadDir'] = $uploadPath;
      //在这里强制的不启用缩略图
      $params['enableNail'] = false;

      $uploader = new Upload($params);
      $files = $request->getUploadedFiles();
      //在这里是否需要检测是否有错误, 探测到错误的时候抛出异常
      $ret = array();
      foreach ($files as $item) {
         $ret[] = $uploader->saveUploadFile($item);
      }
      $pic = array();
      foreach ($ret as $items) {
         foreach ($items as $item) {
            array_push($pic, array(
               'id'   => $item['rid'],
               'url'  => $item['filename'],
               "base" => Kernel\get_image_cdn_server_url() . '/'
            ));
         }
      }
      //在这里是否需要检测是否有错误, 探测到错误的时候抛出异常
      return $pic;
   }

}