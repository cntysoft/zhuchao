<?php namespace ProviderMobileApi;
use ZhuChao\Framework\OpenApi\AbstractScript;
use App\ZhuChao\Provider\Constant as Provider_Content;
use App\Yunzhan\CmMgr\Constant as CM_CONST;
use App\Yunzhan\Content\Constant as Content_Constant;
use Cntysoft\Kernel;
/**
 * 询价单处理
 * 
 * @package ProviderFrontApi
 */
class Recruit extends AbstractScript
{
   /**
    * 根据详情获得招聘列表
    * @param type $params
    */
   public function getRecruitListByStatus($params)
   {
      $this->checkRequireFields($params, array('page', 'pageSize', 'status'));
      $page = (int) $params['page'];
      $page == 0 ? $page = 1 : '';
      $pageSize = (int) $params['pageSize'];
      $pageSize == 0 ? $pageSize = 1 : '';
      $offset = ($page - 1) * $pageSize;
      $user = $this->appCaller->call(Provider_Content::MODULE_NAME, Provider_Content::APP_NAME, Provider_Content::APP_API_MGR, 'getCurUser');
      $infoList = $this->appCaller->call(Content_Constant::MODULE_NAME, Content_Constant::APP_NAME, Content_Constant::APP_API_INFO_LIST, 'getInfoListByNodeAndStatus', array(5, 2, $params['status'], false, 'id DESC', $offset, $pageSize));
      $ret = array();
      foreach ($infoList as $item) {
         $job = $this->appCaller->call(Content_Constant::MODULE_NAME, Content_Constant::APP_NAME, Content_Constant::APP_API_MANAGER, 'read', array($item->getId()))[1];
         $ret[] = array(
            "id"       => $item->getId(),
            "position" => $item->getTitle(),
            "number"   => $job->getNumber(),
         );
      }
      return $ret;
   }

   /**
    * 发表文章
    * 
    * @param array $params
    */
   public function addRecruit($params)
   {
      $this->checkRequireFields($params,array('title','department','content','tel','endTime','number'));
      unset($params['id']);
      $user = $this->appCaller->call(Provider_Content::MODULE_NAME, Provider_Content::APP_NAME, Provider_Content::APP_API_MGR, 'getCurUser');
      $company = $user->getCompany();
      $params['editor'] = $params['author'] = $company->getName();
      //这里直接审核通过,以后要加上内容审核
      $params['status'] = Content_Constant::INFO_S_VERIFY;
      $params['nodeId'] = Content_Constant::NODE_JOIN_ID;
      $this->appCaller->call(Content_Constant::MODULE_NAME, Content_Constant::APP_NAME, Content_Constant::APP_API_MANAGER, 'add', array(CM_CONST::CONTENT_MODEL_JOB, $params));
   }

   //把文章移如回收站,包括新闻,招聘等
   public function deleteRecruit($params)
   {
      $this->checkRequireFields($params, array('id'));
      $this->appCaller->call(Content_Constant::MODULE_NAME, Content_Constant::APP_NAME, Content_Constant::APP_API_MANAGER, 'moveToTrashcan', array($params['id']));
   }
   /**
    * 获得招聘详情
    * @param type $id
    */
   public function getRecruitById($params){
      $this->checkRequireFields($params,array('id'));
      $recruit = $this->appCaller->call(Content_Constant::MODULE_NAME, Content_Constant::APP_NAME, Content_Constant::APP_API_MANAGER, 'read', array($params['id']));
      if($recruit[0]->getCmodelId() != 2){
         return false;
      }
      $job = $this->appCaller->call(Content_Constant::MODULE_NAME, Content_Constant::APP_NAME, Content_Constant::APP_API_MANAGER, 'read', array($recruit[0]->getId()));
      if($job){
         $job = $job[1];
      }
      $job = $job->toarray();
      $job['title'] = $recruit[0] -> getTitle();
      return $job;
   }
   /**
    * 修改招聘
    * @param type $params
    */
   public function modifyRecruit($params){
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
      $this->appCaller->call(Content_Constant::MODULE_NAME, Content_Constant::APP_NAME, Content_Constant::APP_API_MANAGER, 'update', array($id, $params));
   }
   
   
}