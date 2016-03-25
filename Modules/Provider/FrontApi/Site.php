<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace ProviderFrontApi;
use ZhuChao\Framework\OpenApi\AbstractScript;
use App\Yunzhan\CmMgr\Constant as CM_CONST;
use App\Yunzhan\Content\Constant as CN_CONST;
use App\ZhuChao\Provider\Constant as P_CONST;
use Cntysoft\Kernel;
class Site extends AbstractScript
{
   /**
    * 发表文章
    * 
    * @param array $params
    */
   public function addArticle($params)
   {
      unset($params['id']);

      $user = $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR, 'getCurUser');
      $company = $user->getCompany();
      $params['editor'] = $params['author'] = $company->getName();
      //这里直接审核通过,以后要加上内容审核
      $params['status'] = CN_CONST::INFO_S_VERIFY;
      $cndServer = Kernel\get_image_cdn_server_url() . '/';

      //删除图片的网址
      if (isset($params['defaultPicUrl'])) {
         $img = $params['defaultPicUrl'];
         unset($params['defaultPicUrl']);
         $item[0] = str_replace($cndServer, '', $img[0]);
         $item[1] = $img[1];
         $params['defaultPicUrl'] = $item;
      }
      $this->appCaller->call(CN_CONST::MODULE_NAME, CN_CONST::APP_NAME, CN_CONST::APP_API_MANAGER, 'add', array(CM_CONST::CONTENT_MODEL_ARTICLE, $params));
   }
   //把文章移如回收站,包括新闻,招聘等
   public function deleteArticle($params){
      $this->checkRequireFields($params,array('id'));
      $this->appCaller->call(CN_CONST::MODULE_NAME,  CN_CONST::APP_NAME,  CN_CONST::APP_API_MANAGER, 'moveToTrashcan',array($params['id']));
   }
   /**
    * 添加招聘信息
    * 
    * @param array $params
    */
   public function addJob($params)
   {
      unset($params['id']);

      $user = $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR, 'getCurUser');
      $company = $user->getCompany();
      $params['editor'] = $params['author'] = $company->getName();
      //这里直接审核通过,以后要加上内容审核
      $params['status'] = CN_CONST::INFO_S_VERIFY;
      $params['nodeId'] = CN_CONST::NODE_JOIN_ID;

      $this->appCaller->call(CN_CONST::MODULE_NAME, CN_CONST::APP_NAME, CN_CONST::APP_API_MANAGER, 'add', array(CM_CONST::CONTENT_MODEL_JOB, $params));
   }

   /**
    * 修改信息
    * 
    * @param array $params
    */
   public function modifyContent($params)
   {
      $this->checkRequireFields($params, array('id'));
      $id = $params['id'];
      unset($params['id']);
      
      $cndServer = Kernel\get_image_cdn_server_url() . '/';
      //删除图片的网址
      if (isset($params['defaultPicUrl'])) {
         $img = $params['defaultPicUrl'];
         unset($params['defaultPicUrl']);
         $item[0] = str_replace($cndServer, '', $img[0]);
         $item[1] = $img[1];
         $params['defaultPicUrl'] = $item;
      }
      $this->appCaller->call(CN_CONST::MODULE_NAME, CN_CONST::APP_NAME, CN_CONST::APP_API_MANAGER, 'update', array($id, $params));
   }

   /**
    * 修改企业介绍
    * 
    * @param array $params
    */
   public function modifyIntro($params)
   {
      $this->checkRequireFields($params, array('content', 'fileRefs', 'imgRefMap'));
      
      $this->appCaller->call(CN_CONST::MODULE_NAME, CN_CONST::APP_NAME, CN_CONST::APP_API_MANAGER, 'update', array(CN_CONST::INFO_INTRO_ID, array(
         'content' => $params['content'],
         'fileRefs' => $params['fileRefs'],
         'imgRefMap' => $params['imgRefMap']
      )));
   }
   
   /**
    * 修改企业文化
    * 
    * @param array $params
    */
   public function modifyCulture($params)
   {
      $this->checkRequireFields($params, array('content', 'fileRefs', 'imgRefMap'));
      
      $this->appCaller->call(CN_CONST::MODULE_NAME, CN_CONST::APP_NAME, CN_CONST::APP_API_MANAGER, 'update', array(CN_CONST::INFO_CULTURE_ID, array(
         'content' => $params['content'],
         'fileRefs' => $params['fileRefs'],
         'imgRefMap' => $params['imgRefMap']
      )));
   }
   
   /**
    * 修改企业资质
    * 
    * @param array $params
    */
   public function modifyZizhi($params)
   {
      $this->checkRequireFields($params, array('content', 'fileRefs', 'imgRefMap'));
      
      $this->appCaller->call(CN_CONST::MODULE_NAME, CN_CONST::APP_NAME, CN_CONST::APP_API_MANAGER, 'update', array(CN_CONST::INFO_ZIZHI_ID, array(
         'content' => $params['content'],
         'fileRefs' => $params['fileRefs'],
         'imgRefMap' => $params['imgRefMap']
      )));
   }
   
   /**
    * 修改联系我们
    * 
    * @param array $params
    */
   public function modifyContact($params)
   {
      $this->checkRequireFields($params, array('content', 'fileRefs', 'imgRefMap'));
      
      $this->appCaller->call(CN_CONST::MODULE_NAME, CN_CONST::APP_NAME, CN_CONST::APP_API_MANAGER, 'update', array(CN_CONST::INFO_CONTACT_ID, array(
         'content' => $params['content'],
         'fileRefs' => $params['fileRefs'],
         'imgRefMap' => $params['imgRefMap']
      )));
   }
   
}