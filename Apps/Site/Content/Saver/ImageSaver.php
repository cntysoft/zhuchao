<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\Site\Content\Saver;
use Cntysoft\Kernel;
use Cntysoft\Framework\Core\FileRef\Manager as RefManager;
use ZhuChao\Framework\Net\FileRefDownload;
use Cntysoft\Framework\Utils\Image;
use Cntysoft\Stdlib\Filesystem;
use Cntysoft\Stdlib\String;
use App\Site\CmMgr\Model\General as GeneralModel;
use App\Site\CmMgr\Model\Content as CModel;
use App\Site\CmMgr\Model\EffectImageQueryAttrs as QueryModel;
use App\Site\Content\Constant;

class ImageSaver extends StdSaver
{
   /**
    * 网络文件下载器
    *
    * @var {Cntysoft\Framework\Net\FileRefDownload} $downloader
    */
   protected $downloader = null;
   
   protected $queryData = array();
           
   /**
    * @inheritdoc
    */
   public function read(GeneralModel $gmodel, CModel $cmodel)
   {
      $ckey = $cmodel->getKey();
      $scmodelCls = $this->getModelCls($ckey);
      $scmodel = $scmodelCls::findFirst($gmodel->getItemId());
      if (!$scmodel) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception($errorType->msg('E_ITEM_SUB_MODEL_NOT_EXIST', $gmodel->getId(), $gmodel->getItemId()), $errorType->code('E_ITEM_SUB_MODEL_NOT_EXIST')), $this->getErrorTypeContext());
      }
      
      return $scmodel;
   }

   /**
    * @inheritdoc
    */
   public function add( array $gData, array $data, CModel $cmodel)
   {
      $ckey = $cmodel->getKey();
      $scmodelCls = $this->getModelCls($ckey);
      $scmodel = new $scmodelCls();
      $requires = $scmodel->getRequireFields(array('id'));
      $data = $this->singleToImages($gData['title'], $data);
      Kernel\ensure_array_has_fields($data, $requires);
      if (method_exists($scmodel, 'getFileRefs')) {
         //检查文件引用
         if (array_key_exists('fileRefs', $data)) {
            if (empty($data['fileRefs'])) { //为空的时候直接给NULL
               $data['fileRefs'] = null;
            }
            else {
               //先确定
               $refManager = new RefManager();
               foreach ($data['fileRefs'] as $ref) {
                  $refManager->confirmFileRef($ref);
               }
               $data['fileRefs'] = implode(',', $data['fileRefs']);
            }
         }
      }else {
         unset($data['fileRefs']);
      }
      $this->formatQueryData($data);
      $scmodel->assignBySetter($data);
      $scmodel->create();
      return $scmodel->getId();
   }

   /**
    * @inheritdoc
    */
   public function update(GeneralModel $gmodel, array $data, CModel $cmodel)
   {
      //检查文件引用
      $ckey = $cmodel->getKey();
      $scmodelCls = $this->getModelCls($ckey);
      $scmodel = $scmodelCls::findFirst($gmodel->getItemId());
      if (!$scmodel) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception($errorType->msg('E_ITEM_SUB_MODEL_NOT_EXIST', $gmodel->getId(), $gmodel->getItemId()), $errorType->code('E_ITEM_SUB_MODEL_NOT_EXIST')), $this->getErrorTypeContext());
      }
      $requires = $scmodel->getRequireFields(array('id'));
      $data = $this->singleToImages($gmodel->getTitle(), $data);
      Kernel\ensure_array_has_fields($data, $requires);
      //条件缺一不可
      if(isset($data['fileRefs']) && method_exists($scmodel, 'getFileRefs')){
         $oldRefs = $scmodel->getFileRefs();
         $oldRefs = explode(',', $oldRefs);
         $nowRefs = $data['fileRefs'];
         $deleteRefs = array_diff($oldRefs, $nowRefs);
         $newRefs = array_diff($nowRefs, $oldRefs);
         $refManager = new RefManager();
         foreach($deleteRefs as $ref){
            $refManager->removeFileRef($ref);
         }
         foreach($newRefs as $ref){
            $refManager->confirmFileRef($ref);
         }
         $data['fileRefs'] = implode(',', $data['fileRefs']);
      } else {
         unset($data['fileRefs']);
      }
      
      $this->formatQueryData($data);
      $queryData = $this->getQueryData();
      unset($queryData['defaultPicUrl']);
      $id = isset($data['qid']) ? $data['qid'] : '';
      unset($data['qid']);
      
      if (!empty($data)) {
         $scmodel->save($data);
      }
      $query = QueryModel::findFirst($id);
      if (!$query) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception($errorType->msg('E_EFFECT_IMAGE_QUERY_NOT_EXIST'), $errorType->code('E_EFFECT_IMAGE_QUERY_NOT_EXIST')), $this->getErrorTypeContext());
      }

      $queryData['nodeId'] = $gmodel->getNodeId();
      
      $query->save($queryData);
   }

   /**
    * 获取文章标题的关键字
    *
    * @param array $params
    * @return array
    */
   public function getKeywords($params)
   {
      $this->checkRequireFields($params, array('title'));
      $title = $params['title'];
      $keywords = String::getKeyWords($title);
      return array(
         'keywords' => $keywords
      );
   }

   /**
    * @inheritdoc
    */
   public function delete(GeneralModel $gmodel, CModel $cmodel)
   {
      $ckey = $cmodel->getKey();
      $scmodelCls = $this->getModelCls($ckey);
      //删除当前信息关联的附件
      $scmodel = $scmodelCls::findFirst($gmodel->getItemId());
      //不存在都无所谓了
      if ($scmodel) {
         //这里的附件管理
         if (method_exists($scmodel, 'getFileRefs')) {
            $fileRefs = $scmodel->getFileRefs();
            if (!empty($fileRefs)) {
               $fileRefs = explode(',', $fileRefs);
               $manager = new RefManager();
               foreach ($fileRefs as $ref) {
                  $manager->removeFileRef($ref);
               }
            }
         }
         $scmodel->delete();
      }
      $query = QueryModel::findFirst(array(
         'gid=?0',
         'bind' => array(
            0 => $gmodel->getId()
         )
      ));
      if($query){
         $query->delete();
      }
   }

   /**
    * 下载指定的网络资源
    *
    * @param array $params
    * @return array
    */
   public function downloadRemoteFile(array $params)
   {
      $this->checkRequireFields($params, array('fileUrl'));
      $generateNail = isset($params['nail']) ? $params['nail'] : false;
      $downloader = $this->getDownloader();
      $fileInfo = $downloader->download($params['fileUrl']);
      $targetFilename = $fileInfo['targetFile'];
      unset($fileInfo['targetFile']);
        $image = new Image(array(
           'imageFromPath' => $targetFilename
        ), array(
                'thumb' =>
                     array (
                           'width' => '0',
                           'height' => '0'
                     ),
           ));
        $type = $image->getIwsImageType();

      if ($generateNail) {
         //生成缩略图
         $targetDir = Filesystem::dirname($targetFilename);
         $filename = str_replace($targetDir . DS, '', $targetFilename);
         $filename = explode('.', $filename);
         $nailName = array_shift($filename);
         $imageNail = new Image(array('imageFromPath' => $targetFilename));
         $nailFilename = $imageNail->generateThumbnail($targetDir, $nailName . '_nail');
         unset($imageNail);

         if(strstr($type, 'jpg')){
           $image->generateThumbnail($targetDir, $nailName, 'LT', 70);
         }
         unset($image);
         
         //将缩略图保存到文件引用之中
         $refManager = new RefManager();
         $stat = stat($nailFilename);
         $nailFileinfo = array('filesize' => $stat['size'], 'filename' => Filesystem::basename($nailFilename), 'attachment' => str_replace(CNTY_ROOT_DIR, '', $nailFilename));
         $rid = $refManager->addTempFileRef($nailFileinfo);
         $nailFileinfo['rid'] = $rid;
         //构造一个新的返回值，第一个值是源文件信息，第二个值是生成的缩略图信息
         $ret = array($fileInfo, $nailFileinfo);
         return $ret;
      }
      else {
         unset($image);
         return $fileInfo;
      }
   }

   /**
    * 获取文件下载器
    *
    * @return \Cntysoft\Framework\Net\FileRefDownload
    */
   protected function getDownloader()
   {
      if (null == $this->downloader) {
         $this->downloader = new FileRefDownload();
      }
      return $this->downloader;
   }
   
   /**
    * 获取筛选属性的值
    * 
    * @return array
    */
   public function getQueryData()
   {
      return $this->queryData;
   }
   
   /**
    * 对编辑器数据进行处理，将筛选属性的值从数据中分离出来
    * 
    * @param array $data
    */
   protected function formatQueryData(array &$data)
   {
      $queryKey = array('color', 'style', 'space', 'part', 'houseType', 'area', 'imageType');
      $type = isset($data['imageType']) ? (int)$data['imageType'] : 1;
      foreach ($data as $key => $value){
         if(in_array($key, $queryKey)){
            if((2 == $type && in_array($key, array('houseType', 'area'))) || (1 == $type && in_array($key, array('space', 'part')))){
               unset($data[$key]);
               continue;
            }
            $this->queryData[$key] = (int)$value;
            unset($data[$key]);
         }
      }
      foreach($data['images'] as $image){
         if($image['isCover']){
            $this->queryData['defaultPicUrl'][] = $image['filename'];
            $this->queryData['defaultPicUrl'][] = $image['fileRefIds'];
            break;
         }
      }
   }
   
   /**
    * 向数据库中存入筛选属性
    * 
    * @param array $data
    */
   public function setEffectImageQuery(array $data)
   {
      $query = new QueryModel();
      $query->assignBySetter($data);
      $query->create();
   }
   
   /**
    * 根据指定的的效果图id获取其筛选属性
    * 
    * @param integer $gid
    * @return App\Site\CmMgr\Model\EffectImageQueryAttrs
    */
   public function getQuery($gid)
   {
      $query = QueryModel::findFirst(array(
         'gid = ?0',
         'bind' => array(
            0 => $gid
         )
      ));
      if (!$query) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception($errorType->msg('E_EFFECT_IMAGE_QUERY_NOT_EXIST'), $errorType->code('E_EFFECT_IMAGE_QUERY_NOT_EXIST')), $this->getErrorTypeContext());
      }
      return $query;
   }
   
   /**
    * 将单图格式的数据转换成组图格式
    * 
    * @param string $title 信息的标题
    * @param array $data 
    * @return array
    */
   protected function singleToImages($title, array $data)
   {
      if(Constant::QUERY_ATTR_SINGLE == $data['imageType']){
         $data['images'] = array(array(
            'description' => $title,
            'isCover' => 1,
            'filename' => $data['singlePic'][0],
            'fileRefIds' => (array)$data['singlePic'][1]
         ));
      }
      unset($data['singlePic']);
      return $data;
   }
   
   /**
    * 根据筛选属性获取
    * 
    * @param array $id 栏目id
    * @param boolean $total 是否返回信息总数
    * @param array $queryAttrs 筛选属性的值
    * @return array|Phalcon\Mvc\Model\Resultset\Simple
    */
   public function getQueryListByQuery(array $id = array(0), $total = false, $imageType = 0, $queryAttrs = array())
   {
      $cond = array();
      if(count($id) && !in_array(0, $id)){
         $cond[] = QueryModel::generateRangeCond('nodeId', $id);
      }
      
      if(0 != (int)$imageType){
         $cond[] = 'imageType = ' . (int)$imageType;
      }

      foreach($queryAttrs as $key => $value){
         $item = $key . '=' .$value;
         array_push($cond, $item);
      }
      
      $cond = implode(' and ', $cond);
      $items = QueryModel::find(array(
         $cond
      ));
      
      if($total){
         return array(
            $items, (int) QueryModel::find(array(
               $cond
            ))
         );
      }
      
      return $items;
   }
   
}