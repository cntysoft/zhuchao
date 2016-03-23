<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\Yunzhan\Content\Saver;
use Cntysoft\Kernel;
use ZhuChao\Framework\Core\FileRef\Manager as RefManager;
use ZhuChao\Framework\Net\FileRefDownload;
use Cntysoft\Framework\Utils\Image;
use Cntysoft\Stdlib\Filesystem;
use Cntysoft\Stdlib\String;
use App\Yunzhan\CmMgr\Model\General as GeneralModel;
use App\Yunzhan\CmMgr\Model\Content as CModel;
class StdSaver extends AbstractSaver
{

   /**
    * 网络文件下载器
    *
    * @var {Cntysoft\Framework\Net\FileRefDownload} $downloader
    */
   protected $downloader = null;

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
         Kernel\throw_exception(new Exception($errorType->msg('E_ITEM_SUB_MODEL_NOT_EXIST',
               $gmodel->getId(), $gmodel->getItemId()),
            $errorType->code('E_ITEM_SUB_MODEL_NOT_EXIST')),
            $this->getErrorTypeContext());
      }
      return $scmodel;
   }

   /**
    * @inheritdoc
    */
   public function add(array $gData, array $data, CModel $cmodel)
   {
      $ckey = $cmodel->getKey();
      $scmodelCls = $this->getModelCls($ckey);
      $scmodel = new $scmodelCls();
      $requires = $scmodel->getRequireFields(array('id'));
      Kernel\ensure_array_has_fields($data, $requires);
//      if (method_exists($scmodel, 'getFileRefs')) {
//         //检查文件引用
//         if (array_key_exists('fileRefs', $data)) {
//            if (empty($data['fileRefs'])) { //为空的时候直接给NULL
//               $data['fileRefs'] = null;
//            } else {
//               //先确定
//               $refManager = new RefManager();
//               foreach ($data['fileRefs'] as $ref) {
//                  $refManager->confirmFileRef($ref);
//               }
//               $data['fileRefs'] = implode(',', $data['fileRefs']);
//            }
//         }
//      } else {
         unset($data['fileRefs']);
//      }

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
         Kernel\throw_exception(new Exception($errorType->msg('E_ITEM_SUB_MODEL_NOT_EXIST',
               $gmodel->getId(), $gmodel->getItemId()),
            $errorType->code('E_ITEM_SUB_MODEL_NOT_EXIST')),
            $this->getErrorTypeContext());
      }
      //条件缺一不可
//      if (isset($data['fileRefs']) && method_exists($scmodel, 'getFileRefs')) {
//         $oldRefs = $scmodel->getFileRefs();
//         $oldRefs = explode(',', $oldRefs);
//         $nowRefs = $data['fileRefs'];
//         $deleteRefs = array_diff($oldRefs, $nowRefs);
//         $newRefs = array_diff($nowRefs, $oldRefs);
//         $refManager = new RefManager();
//         foreach ($deleteRefs as $ref) {
//            $refManager->removeFileRef($ref);
//         }
//         foreach ($newRefs as $ref) {
//            $refManager->confirmFileRef($ref);
//         }
//         $data['fileRefs'] = implode(',', $data['fileRefs']);
//      } else {
         unset($data['fileRefs']);
//      }

      if (!empty($data)) {
         $scmodel->save($data);
      }
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
   }

   /**
    * 下载指定的网络资源
    *
    * @param array $params
    * @return array
    */
   public function downloadRemoteFile(array $params)
   {
      $this->checkRequireFields($params, array('fileUrl', 'targetDir', 'useOss'));
      $downloader = $this->getDownloader();
      $fileInfo = $downloader->download($params['fileUrl'], $params['targetDir'], $params['useOss']);
      unset($fileInfo['targetFile']);
      return $fileInfo;
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

}