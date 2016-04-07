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
use App\Yunzhan\Setting\Constant as SETTING_CONST;
use App\ZhuChao\Provider\Constant as P_CONST;
use App\Yunzhan\Category\Constant as CATE_CONST;
use Cntysoft\Kernel;
use ZhuChao\Framework\Core\FileRef\Manager as RefManager;

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
         $image = explode('@', str_replace($cndServer, '', $img[0]));
         $item[0] = $image[0];
         $item[1] = $img[1];
         $params['defaultPicUrl'] = $item;
      }
      $this->appCaller->call(CN_CONST::MODULE_NAME, CN_CONST::APP_NAME, CN_CONST::APP_API_MANAGER, 'add', array(CM_CONST::CONTENT_MODEL_ARTICLE, $params));
   }

   //把文章移如回收站,包括新闻,招聘等
   public function deleteArticle($params)
   {
      $this->checkRequireFields($params, array('id'));
      $this->appCaller->call(CN_CONST::MODULE_NAME, CN_CONST::APP_NAME, CN_CONST::APP_API_MANAGER, 'moveToTrashcan', array($params['id']));
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
         $result = explode('@', str_replace($cndServer, '', $img[0]));
         $item[0] = $result[0];
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
            'content'   => $params['content'],
            'fileRefs'  => $params['fileRefs'],
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
            'content'   => $params['content'],
            'fileRefs'  => $params['fileRefs'],
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
            'content'   => $params['content'],
            'fileRefs'  => $params['fileRefs'],
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
            'content'   => $params['content'],
            'fileRefs'  => $params['fileRefs'],
            'imgRefMap' => $params['imgRefMap']
      )));
   }

   /**
    * 修改店铺的banner信息
    * 
    * @param array $params
    */
   public function modifySetting(array $params)
   {
      $this->checkRequireFields($params, array('banner', 'keywords', 'description','product','case','aboutus','news','zhaopin'));

      $this->appCaller->call(
              SETTING_CONST::MODULE_NAME, SETTING_CONST::APP_NAME, SETTING_CONST::APP_API_CFG, 'setItem', array('Seo', 'keywords', $params['keywords'])
      );
      $this->appCaller->call(
              SETTING_CONST::MODULE_NAME, SETTING_CONST::APP_NAME, SETTING_CONST::APP_API_CFG, 'setItem', array('Seo', 'description', $params['description'])
      );
      $this->appCaller->call(
              SETTING_CONST::MODULE_NAME, SETTING_CONST::APP_NAME, SETTING_CONST::APP_API_CFG, 'setItem', array('Nav', 'product', $params['product'] ? 1 : 0)
      );
      $this->appCaller->call(
              SETTING_CONST::MODULE_NAME, SETTING_CONST::APP_NAME, SETTING_CONST::APP_API_CFG, 'setItem', array('Nav', 'case', $params['case'] ? 1 : 0)
      );
      $this->appCaller->call(
              SETTING_CONST::MODULE_NAME, SETTING_CONST::APP_NAME, SETTING_CONST::APP_API_CFG, 'setItem', array('Nav', 'news', $params['news'] ? 1 : 0)
      );
      $this->appCaller->call(
              SETTING_CONST::MODULE_NAME, SETTING_CONST::APP_NAME, SETTING_CONST::APP_API_CFG, 'setItem', array('Nav', 'zhaopin', $params['zhaopin'] ? 1 : 0)
      );
      $this->appCaller->call(
              SETTING_CONST::MODULE_NAME, SETTING_CONST::APP_NAME, SETTING_CONST::APP_API_CFG, 'setItem', array('Nav', 'aboutus', $params['aboutus'] ? 1 : 0)
      );
      
      
      $refManager = new RefManager();
      $newBanners = $params['banner'];
      
      $oldBanners = $this->appCaller->call(
         SETTING_CONST::MODULE_NAME, SETTING_CONST::APP_NAME, SETTING_CONST::APP_API_CFG, 'getItemByKey', array('Banner')
      );
      $oldBanners = unserialize($oldBanners[0]->getValue());
      
      $newFileRefs = $oldFileRefs = array();
      
      foreach($newBanners as $nb){
         $newFileRefs[] = (int)$nb[1];
      }
      
      foreach($oldBanners as $ob){
         $oldFileRefs[] = (int)$ob[1];
      }
      
      $deleteFileRefs = array_diff($oldFileRefs, $newFileRefs);
      $addFileRefs = array_diff($newFileRefs, $oldFileRefs);
      
      if(count($deleteFileRefs)){
         foreach($deleteFileRefs as $df){
            $refManager->removeFileRef($df);
         }
      }
      
      if(count($addFileRefs)){
         foreach($addFileRefs as $of){
            $refManager->confirmFileRef($of);
         }
      }
      
      $this->appCaller->call(
         SETTING_CONST::MODULE_NAME, SETTING_CONST::APP_NAME, SETTING_CONST::APP_API_CFG, 'setItem', array('Site', 'Banner', serialize($params['banner']))
      );
   }

   public function addCase($params)
   {
      $this->checkRequireFields($params, array('content', 'nodeId', 'title', 'intro', 'fileRefs'));
      unset($params['id']);

      $params['nodeId'] = $this->getCaseNodeId($params['nodeId']);
      $user = $this->appCaller->call(P_CONST::MODULE_NAME, P_CONST::APP_NAME, P_CONST::APP_API_MGR, 'getCurUser');
      $company = $user->getCompany();
      $params['editor'] = $params['author'] = $company->getName();
      //这里直接审核通过,以后要加上内容审核
      $params['status'] = CN_CONST::INFO_S_VERIFY;
      $cndServer = Kernel\get_image_cdn_server_url() . '/';

      //删除图片的网址
      $content = array();
      $defaultPicUrl = array();
      foreach ($params['content'] as $key => $item) {
         $img = str_replace($cndServer, '', $item['src']);
         if (0 == $key) {
            $defaultPicUrl[] = $img;
            $defaultPicUrl[] = $item['rid'];
         }

         array_push($content, array(
            'rid'   => $item['rid'],
            'src'   => $img,
            'intro' => $item['intro']
         ));
      }

      unset($params['content']);
      $params['content'] = $content;
      $params['defaultPicUrl'] = $defaultPicUrl;
      return $this->appCaller->call(CN_CONST::MODULE_NAME, CN_CONST::APP_NAME, CN_CONST::APP_API_MANAGER, 'add', array(CN_CONST::CMODEL_CASEINFO_ID, $params));
   }

   public function modifyCase($params)
   {
      $this->checkRequireFields($params, array('id', 'content', 'title', 'intro', 'fileRefs'));
      $id = $params['id'];
      unset($params['id']);
      $cndServer = Kernel\get_image_cdn_server_url() . '/';

      //删除图片的网址
      $content = array();
      $defaultPicUrl = array();
      foreach ($params['content'] as $key => $item) {
         $img = str_replace($cndServer, '', $item['src']);
         if (0 == $key) {
            $defaultPicUrl[] = $img;
            $defaultPicUrl[] = $item['rid'];
         }

         array_push($content, array(
            'rid'   => $item['rid'],
            'src'   => $img,
            'intro' => $item['intro']
         ));
      }

      unset($params['content']);
      $params['content'] = $content;
      $params['defaultPicUrl'] = $defaultPicUrl;
      return $this->appCaller->call(CN_CONST::MODULE_NAME, CN_CONST::APP_NAME, CN_CONST::APP_API_MANAGER, 'update', array($id, $params));
   }

   public function addCaseCategory()
   {
      
   }

   public function deleteCase($params)
   {
      $this->checkRequireFields($params, array('id'));
      $this->appCaller->call(CN_CONST::MODULE_NAME, CN_CONST::APP_NAME, CN_CONST::APP_API_MANAGER, 'moveToTrashcan', array($params['id']));
   }

   /**
    * 获取案例中心的子栏目列表
    * 
    * @return array
    */
   public function getCaseCategory()
   {
      $list = $this->appCaller->call(CATE_CONST::MODULE_NAME, CATE_CONST::APP_NAME, CATE_CONST::APP_API_STRUCTURE, 'getSubNodes', array(CATE_CONST::NODE_CASE_ID));
      $ret = array();
      foreach ($list as $cate) {
         array_push(array(
            'id'   => $cate->getId(),
            'text' => $cate->getText()
         ));
      }

      return $ret;
   }

   /**
    * 验证案例的分组
    * 
    * @param int $id
    * @return int
    */
   protected function getCaseNodeId($id)
   {
      if (CATE_CONST::NODE_CASE_ID == $id) {
         return $id;
      }

      $node = $this->appCaller->call(CATE_CONST::MODULE_NAME, CATE_CONST::APP_NAME, CATE_CONST::APP_API_STRUCTURE, 'getNode', array($id));
      if (CATE_CONST::NODE_CASE_ID == $node->getPid()) {
         return $id;
      } else {
         return CATE_CONST::NODE_CASE_ID;
      }
   }

}