<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
*/
namespace EditApi;
use KeleShop\Framework\OpenApi\AbstractScript;
use Cntysoft\Stdlib\Filesystem;
use Cntysoft\Kernel;
use App\Shop\GoodsMgr\Constant as GOODS_CONST;
use App\Shop\CategoryMgr\Constant as CATEGORY_CONST;
use Cntysoft\Kernel\ConfigProxy;
use KeleShop\Framework\Net\Upload;
use KeleShop\Framework\Core\FileRef\Manager as FileRefMgr;

class Phone extends AbstractScript
{
   /**
    * 模板文件的路径
    * @var string 
    */
   protected $tplPath = CNTY_DATA_DIR. DS . 'Framework' . DS . 'Edit' . DS . 'phone' . DS . 'tpl.json';
   /**
    * 缓存处理对象
    * @var  
    */
   protected $cacher = '';

   /**
    * 获取模板配置
    * 
    * @return string
    */
   public function getTplConfig()
   {
      if(!file_exists($this->tplPath)){
         Filesystem::filePutContents($this->tplPath, '');
      }
      $cacher = $this->getCacher();
      $key = md5('EditApi.Phone.TplConfig');
      if(!$cacher->exists($key)){
         $tpl = Filesystem::fileGetContents($this->tplPath);
         $cacher->save($key, $tpl);
      }else{
         $tpl = $cacher->get($key);
      }

      return json_decode($tpl, true);
   }
   
   /**
    * 保存模板的修改
    * 
    * @param array $params
    */
   public function saveTplConfig(array $params)
   {
      
      if(!$params){
         $errorType = ErrorType::getInstance();
         Kernel\throw_exception(new Exception($errorType->msg('E_EDIT_PHONE_TPLCONFIG_ERROR'), $errorType->code('E_EDIT_PHONE_TPLCONFIG_ERROR')));
      }
      
      $config = $this->getTplConfig();
      $oldRids = $this->getTplRids($config);
      $rids = $this->getTplRids($params);
      $deleteRids = array_diff($oldRids, $rids);
      $newRids = array_diff($rids, $oldRids);
      $fileMgr = new FileRefMgr();
      foreach ($deleteRids as $did){
         $fileMgr->removeFileRef($did);
      }
      foreach($newRids as $nid){
         $fileMgr->confirmFileRef($nid);
      }

      $cacher = $this->getCacher();
      $key = md5('EditApi.Phone.TplConfig');
      $cacher->delete($key);
      $tpl = json_encode($params);
      
      Filesystem::filePutContents($this->tplPath, $tpl);
   }
   
   /**
    * 获取商店商品的列表
    * 
    * @param array $params
    */
   public function getProductList(array $params)
   {
      $this->checkRequireFields($params, array('page', 'limit'));
      $page = (int)$params['page'];
      $limit = (int)$params['limit'];
      $page = $page > 0 ? $page : 1;
      $offset = ($page - 1) * $limit;
      $list = $this->appCaller->call(
         GOODS_CONST::MODULE_NAME,
         GOODS_CONST::APP_NAME,
         GOODS_CONST::APP_API_GOODS,
         'getGoodsListBy',
         array(array('status = 1'), true, 'inputTime DESC', $offset, $limit)
      );
      $items = $list[0];
      $ret = array(
         'currentPage' => $page,
         'pageSize'    => $limit,
         'total'       => $list[1]
      );
      $products = array();
      if(count($items)){
         foreach($items as $item){
            $pro = array(
               'title' => $item->getTitle(),
               'img'   => $this->getImgUrl($item->getImg()),
               'price' => $item->getPrice(),
               'identifier' => $item->getId(),
               'href' => $this->getProductUrl($item->getId())
            );
            array_push($products, $pro);
         }
      }

      $ret['products'] = $products;
      
      return $ret;
   }
   
   /**
    * 获取模板配置文件中的文件引用信息
    * 
    * @param array $config
    */
   public function getTplRids($config)
   {
      $rids = array();
      if(!is_array($config)){
         return $rids;
      }
      foreach($config as $key => $value){
         if(is_array($value)){
            $rids = array_merge($rids, $this->getTplRids($value));
         }else{
            if('rid' == $key){
               $rids[] = $value;
            }
         }
      }
      
      return $rids;
   }
   
   /**
    * 获取商品分类的列表
    * 
    * @param array $params
    * @return array
    */
   public function getCategoryList(array $params)
   {
      $this->checkRequireFields($params, array('page', 'limit'));
      $page = (int)$params['page'];
      $limit = (int)$params['limit'];
      $page = $page > 0 ? $page : 1;
      $offset = ($page - 1) * $limit;
      $list = $this->appCaller->call(
         CATEGORY_CONST::MODULE_NAME,
         CATEGORY_CONST::APP_NAME,
         CATEGORY_CONST::APP_API_MGR,
         'getNodeList',
         array(true, 'id ASC', $offset, $limit)
      );
      $items = $list[0];
      $ret = array(
         'currentPage' => $page,
         'pageSize'    => $limit,
         'total'       => $list[1]
      );
      $categories = array();
      if(count($items)){
         foreach($items as $item){
            $pro = array(
               'title' => $item->getName(),
               'identifier' => $item->getId(),
               'href' => $this->getCategoryUrl($item->getId())
            );
            array_push($categories, $pro);
         }
      }

      $ret['categories'] = $categories;
      
      return $ret;
   }
   
   /**
    * 处理用户中心上传图片
    * 
    * @return array
    */
   public function uploadImg(array $data)
   {
      $this->checkRequireFields($data, array('callback'));
      header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
      header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
      header("Cache-Control: no-store, no-cache, must-revalidate");
      header("Cache-Control: post-check=0, pre-check=0", false);
      header("Pragma: no-cache");
      @set_time_limit(5 * 60);
      
      $cfg = ConfigProxy::getFrameworkConfig('Net');
      $params = array(
         'uploadDir'     => '/Data/UploadFiles/Edit/Phone',
         'overwrite'     => true,
         'enableFileRef' => true,
         'randomize'     => true,
         'createSubDir'  => false,
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
      $file = $uploader->saveUploadFile(array_shift($files));
      return "<script>$ = parent.$;parent.".$data['callback']."('".$file[0]['filename']."', '".$file[0]['rid']."');</script>";
   }

   /**
    * 获取商品的网址
    * 
    * @param integer $id
    */
   public function getProductUrl($id)
   {
      return '/product/'.$id.'.html';
   }
   
   /**
    * 获取图片的网址
    * 
    * @param string $img
    * @return string
    */
   public function getImgUrl($img)
   {
      return $img;
   }
   
   /**
    * 获取商品分类的网址
    * 
    * @param string $id
    * @return string
    */
   public function getCategoryUrl($id)
   {
      return '/productclassify/'.$id.'/1.html';
   }
   
   /**
    * @return \Phalcon\Cache\Backend\File
    */
   public function getCacher()
   {
      if(null == $this->cacher){
         $this->cacher = Kernel\make_cache_object(implode(DS, array('Framework', 'Edit', 'Phone' )), 7200);
      }
      return $this->cacher;
   }
   
}