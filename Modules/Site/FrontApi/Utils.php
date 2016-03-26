<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace SiteFrontApi;
use ZhuChao\Framework\OpenApi\AbstractScript;
use App\Yunzhan\Product\Constant as PRODUCT_CONST;
use App\Yunzhan\Category\Constant as CATEGORYCONST;
use App\Yunzhan\Content\Constant as CONTENT_CONST;
use App\ZhuChao\Provider\Constant as PROVIDER_CONST;
use App\ZhuChao\Buyer\Constant as BUYER_CONST;
use Cntysoft\Kernel;
/**
 * 处理系统上传
 *
 * Class WebUploader
 * @package SysApiHandler
 */
class Utils extends AbstractScript
{
   public function addHits()
   {
      
   }

   /**
    * 获取产品列表
    * 
    * @param array $params
    */
   public function getProductList($params)
   {
      $this->checkRequireFields($params, array('page', 'limit'));
      $cond = array();
      $orderBy = 'id DESC';
      if (isset($params['keyword']) && $params['keyword']) {
         $cond[] = "(brand like '%" . $params['keyword'] . "%' or title like '%" . $params['keyword'] . "%' or description like '%" . $params['keyword'] . "%' )";
      }
      if (isset($params['sort']) && $params['sort']) {
         switch ((int) $params['sort']) {
            case 3:$orderBy = 'price ASC';
               break;
            case 4:$orderBy = 'price DESC';
               break;
            default:
               break;
         }
      }
      $cond[] = 'status=' . PRODUCT_CONST::PRODUCT_STATUS_VERIFY;
      $queryCond = array(implode(' and ', $cond));

      $productlist = $this->appCaller->call(
              PRODUCT_CONST::MODULE_NAME, PRODUCT_CONST::APP_NAME, PRODUCT_CONST::APP_API_PRODUCT_MGR, 'getProductList', array($queryCond, false, $orderBy, $params['page'] * $params['limit'], $params['limit'])
      );
      $ret = array();
      foreach ($productlist as $goods) {
         $infourl = 'http://' . \Cntysoft\RT_SYS_SITE_NAME . '/item/' . $goods->getNumber() . '.html';
         $defpic = $goods->getDefaultImage();
         $price0 = $goods->getPrice();
         $price = $price0 > 0 ? '¥' . $price0 : '面议';
         $ret[] = array(
            'infourl' => $infourl,
            'title'   => $goods->getBrand() . ' ' . $goods->getTitle() . ' ' . $goods->getDescription(),
            'imgurl'  => $this->getImgUrl($defpic, 70, 70),
            'price'   => $price
         );
      }
      return $ret;
   }

   /**
    * 获取cdn图片的地址
    * 
    * @param string $imgUrl
    * @param integer $width
    * @param integer $height
    * @return string
    */
   private function getImgUrl($imgUrl, $width, $height)
   {
      if ($imgUrl) {
         return Kernel\get_image_cdn_url_operate($imgUrl, array('w' => $width, 'h' => $height));
      } else {
         return 'Statics/Skins/Pc/Images/lazyicon.png';
      }
   }

   /**
    * 获取文章列表
    * 
    * @param array $params
    */
   private function getArticles($params)
   {
      $nodeId = array();
      $node = $this->appCaller->call(CATEGORYCONST::MODULE_NAME, CATEGORYCONST::APP_NAME, CATEGORYCONST::APP_API_STRUCTURE, 'getNodesByIdentifiers', array(
         explode(',', $params['nodeIdentifier'])
      ));
      foreach ($node as $item) {
         array_push($nodeId, $item->getId());
      }
      return $this->appCaller->call(
                      CONTENT_CONST::MODULE_NAME, CONTENT_CONST::APP_NAME, CONTENT_CONST::APP_API_INFO_LIST, 'getInfoListByNodeAndStatus', array(
                 $nodeId, 0, CONTENT_CONST::INFO_S_VERIFY, false, 'id desc', $params['page'] * $params['limit'], $params['limit']
      ));
   }

   /**
    * 手机段获取企业新闻列表
    * @param array $params
    * @return type
    */
   public function getNewsList(array $params)
   {
      $this->checkRequireFields($params, array('page', 'limit', 'nodeIdentifier'));
      $infolist = $this->getArticles($params);
      $ret = array();
      foreach ($infolist as $info) {
         $imgurl = '';
         $defpic = $info->getDefaultPicUrl()[0];
         $infourl = '/news/' . $info->getId() . '.html';
         if ($defpic) {
            $imgurl = $this->getImgUrl($defpic, 60, 60);
         }
         $ret[] = array(
            'infourl' => $infourl,
            'imgurl'  => $imgurl,
            'title'   => $info->getTitle(),
            'time'    => date('Y-m-d', $info->getInputTime()),
            'hits'    => $info->getHits()
         );
      }
      return $ret;
   }

   /**
    * 手机段获取企业招聘列表
    * @param array $params
    * @return type
    */
   public function getJobList(array $params)
   {
      $this->checkRequireFields($params, array('page', 'limit', 'nodeIdentifier'));
      $infolist = $this->getArticles($params);
      $ret = array();
      foreach ($infolist as $info) {
         $infourl = '/news/' . $info->getId() . '.html';
         $itemInfo = $this->appCaller->call(CONTENT_CONST::MODULE_NAME, CONTENT_CONST::APP_NAME, CONTENT_CONST::APP_API_MANAGER, 'read', array($info->getId()));
         $ret[] = array(
            'infourl'    => $infourl,
            'title'      => $info->getTitle(),
            'time'       => date('Y-m-d', $info->getInputTime()),
            'department' => $itemInfo[1]->getDepartment(),
            'number'     => $itemInfo[1]->getNumber()
         );
      }
      return $ret;
   }

   /**
    * 添加企业关注
    * 
    * @param array $params
    */
   public function addFollow($params)
   {
      $this->checkRequireFields($params, array('id'));
      $companyId = $params['id'];
      //验证企业信息是否存在
      $this->appCaller->call(PROVIDER_CONST::MODULE_NAME, PROVIDER_CONST::APP_NAME, PROVIDER_CONST::APP_API_MANAGER, 'getCompanyById', array($companyId));

      $acl = $this->di->get('BuyerAcl');
      $user = $acl->getCurUser();
      $this->appCaller->call(BUYER_CONST::MODULE_NAME, BUYER_CONST::APP_NAME, BUYER_CONST::APP_API_BUYER_FOLLOW, 'addFollow', array($user->getId(), $companyId));
   }

   /**
    * 退出
    * @return type
    */
   public function logout()
   {
      return $this->appCaller->call(BUYER_CONST::MODULE_NAME, BUYER_CONST::APP_NAME, BUYER_CONST::APP_API_BUYER_ACL, 'logout');
   }

}