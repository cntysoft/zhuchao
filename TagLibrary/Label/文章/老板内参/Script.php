<?php
/**
 * Cntysoft Web Platform 中国领先的web平台
 *
 * @category   Cntysoft
 * @author     Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Label\Category;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractLabelScript;
use App\Site\Category\Constant as CategoryConst;
use App\Site\Content\Constant as ContentConst;
class NewsList extends AbstractLabelScript
{
   /**
    * 列表的输出数目
    *
    * @var int
    */
   protected $outputNum = null;
   /**
    * 系统栏目树
    *
    * @var null
    */
   protected static $tree = null;
   /**
    * 路由信息
    *
    * @var null
    */
   protected $routes = null;
   /**
    * 列表的排列方式
    *
    * @var array
    */
   protected $orderType = array(
      'id DESC', //0   : 按照ID降序排列
      'id ASC', //1   : 按照ID升序排列
      'inputTime DESC', //2   : 按照信息插入时间降序排列
      'inputTime ASC', //3  :  按照信息插入时间升序排列
      'hits DESC', //4   : 按照总点击数降序排列
      'hits ASC' //5   : 按照总点击数生序排列
   );

   /**
    * 获取自定义信息列表
    */
   public function execute()
   {
      $nodeId = array();
      $params = $this->invokeParams;
      $nodeIdentifier = $params['nodeIdentifier'];
      $modelId = $params['modelId'];
      $enablePage = $params['enablePage'];
      $pageParam = $this->getPageParam();
      $node = $this->appCaller->call(CategoryConst::MODULE_NAME, CategoryConst::APP_NAME, CategoryConst::APP_API_STRUCTURE, 'getNodesByIdentifiers', array($nodeIdentifier)
      );
      foreach ($node as $item) {
         array_push($nodeId, $item->getId());
      }
      $orderType = 'id asc';
      return $this->appCaller->call(
                      ContentConst::MODULE_NAME, ContentConst::APP_NAME, ContentConst::APP_API_INFO_LIST, 'getInfoListByNodeAndStatus', array($nodeId, $modelId, ContentConst::INFO_S_VERIFY, $enablePage, $orderType, $pageParam['offset'], $pageParam['limit'])
      );
   }

   /**
    * 获取自定义信息列表
    */
   public function getArticleList($orderType = 'id asc')
   {
      $nodeId = array();
      $params = $this->invokeParams;
      $route = $this->getRoute();
      if (!isset($route['nodeIdentifier'])) {
         return false;
      }
      $nodeIdentifier = $route['nodeIdentifier'];
      $modelId = $params['modelId'];
      $enablePage = $params['enablePage'];
      $pageParam = $this->getPageParam();
      $node = $this->appCaller->call(CategoryConst::MODULE_NAME, CategoryConst::APP_NAME, CategoryConst::APP_API_STRUCTURE, 'getNodeByIdentifier', array($nodeIdentifier)
      );
      return $this->appCaller->call(
                      ContentConst::MODULE_NAME, ContentConst::APP_NAME, ContentConst::APP_API_INFO_LIST, 'getInfoListByNodeAndStatus', array(array($node->getId()), $modelId, ContentConst::INFO_S_VERIFY, $enablePage, $orderType, $pageParam['offset'], $pageParam['limit'])
      );
   }

   /**
    * 
    * @param string $nodeIdentifier
    * @return type
    */
   public function getDefalutNode($nodeIdentifier)
   {

      $node = $this->appCaller->call(CategoryConst::MODULE_NAME, CategoryConst::APP_NAME, CategoryConst::APP_API_STRUCTURE, 'getNodeByIdentifier', array($nodeIdentifier)
      );
      return $node;
   }

   /**
    * 通过配置获取
    * 
    * @return string
    */
   public function getIvokeNodeIdentifier()
   {
      $params = $this->invokeParams;
      $nodeIdentifier = $params['nodeIdentifier'][0];
      return $nodeIdentifier;
   }

   public function getRouteNodeIdentifier()
   {
      return $this->getRoute()['nodeIdentifier'];
   }

   /**
    * 获取路由信息
    */
   public function getRoute()
   {
      if (null == $this->routes) {
         $this->routes = $this->getRouteInfo();
      }
      return $this->routes;
   }

   /**
    * 获取指定栏目的子栏目
    *
    * @param integer $nodeId
    *
    * @return array
    */
   public function getSubNodeIds($nodeId)
   {
      if (null == self::$tree) {
         self::$tree = $this->appCaller->call(CategoryConst::MODULE_NAME, CategoryConst::APP_NAME, CategoryConst::APP_API_STRUCTURE, 'getTreeObject', array());
      }

      return self::$tree->getChildren($nodeId, -1, false);
   }

   /**
    * 要获取信息的栏目id
    *
    * @return array
    */
   public function getNodeIds()
   {
      if (isset($this->invokeParams['nodeId'])) {
         $id = $this->invokeParams['nodeId'];
         $nodeIds = is_array($id) ? $id : array($id);
      } else {
         $routeInfo = $this->getRouteInfo();
         $nid = isset($routeInfo['nid']) ? (int) $routeInfo['nid'] : 0; //默认为节点1
         $nodeIds = is_array($nid) ? $nid : array($nid);
      }

      return $nodeIds;
   }

   /**
    * 获取指定信息的详细内容
    *
    * @param integer $itemId
    * @return array
    */
   public function getDetail($itemId)
   {
      return $this->appCaller->call(ContentConst::MODULE_NAME, ContentConst::APP_NAME, ContentConst::APP_API_MANAGER, 'read', array($itemId));
   }

   /**
    * 获取信息分页参数
    *
    * @return array
    */
   protected function getPageParam()
   {
      $enablePage = $this->getParam('enablePage');
      $outputNum = $this->getOutputNum();
      if ($enablePage) {
         $routeInfo = $this->getRouteInfo();
         $pageId = isset($routeInfo['pageid']) ? $routeInfo['pageid'] : 1;
         return array(
            'limit'  => $outputNum,
            'offset' => ($pageId - 1) * $outputNum
         );
      } else {
         return array(
            'limit'  => $outputNum,
            'offset' => 0
         );
      }
   }

   /**
    * 获取列表的输出数
    *
    * @return integer
    */
   public function getOutputNum()
   {
      if (null == $this->outputNum) {
         if (!isset($this->invokeParams['outputNum'])) {
            $this->outputNum = 15;
         } else {
            $this->outputNum = $this->invokeParams['outputNum'];
         }
      }

      return $this->outputNum;
   }

   /**
    * 根据栏目nodeIdentifier获得栏目信息
    */
   public function getNodeByNodeIndentifier($nodeIdentifier)
   {
      return $this->appCaller->call(CategoryConst::MODULE_NAME, CategoryConst::APP_NAME, CategoryConst::APP_API_STRUCTURE, 'getNodeByIdentifier', array($nodeIdentifier));
   }

   /**
    * 获取信息的URL
    *
    * @param integer $itemId
    * @return string
    */
   public function getInfoUrl($itemId)
   {
      return '/article/' . $itemId . '.html';
   }

   /**
    * 获取节点的URL
    *
    * @param string $nodeIdentifier
    * @return string
    */
   public function getNodeUrl($nodeIdentifier)
   {
      return $this->appCaller->call(CategoryConst::MODULE_NAME, CategoryConst::APP_NAME, CategoryConst::APP_API_STRUCTURE, 'getNodeUrl', array($nodeIdentifier));
   }

   /**
    * 获取带分页列表页分页的URL
    *
    * @param string $nodeIdentifier
    * @param integer $pageId
    * @return string
    */
   public function getPageUrl($nodeIdentifier, $pageId)
   {
      return '/category/' . $nodeIdentifier . '/' . $pageId . '.html';
   }

   /**
    * 格式化字符串
    *
    * @param string $string
    * @param integer $len
    * @param boolean $useSuffix
    * @return string
    */
   public function formatString($string, $len, $useSuffix)
   {
      $titleLength = iconv_strlen($string, 'UTF-8');
      if ($titleLength <= $len) {
         return $string;
      }
      if ($len < $titleLength) { //当要求的长度小于题目的长度，自动为题目加上后缀
         $useSuffix = true;
      }
      if ($useSuffix) {
         $suffixLength = iconv_strlen('...', 'UTF-8');
         $titleLength = $len - $suffixLength;
         $title = iconv_substr($string, 0, $titleLength, 'UTF-8');
         return $title . '...';
      } else {
         $titleLength = $len;
         $title = iconv_substr($string, 0, $titleLength, 'UTF-8');
         return $title;
      }
   }

   /**
    * 如果分页，获取当前是第几页
    *
    * @return integer
    */
   public function getCurrentPageId()
   {
      $routeInfo = $this->getRouteInfo();
      return isset($routeInfo['pageid']) ? $routeInfo['pageid'] : 1;
   }

   /**
    * 　获取分页相关的参数
    *
    * @param integer $total
    * @return array
    */
   public function getPaging($total)
   {
      $routeInfo = $this->getRouteInfo();
      $nid = isset($routeInfo['nid']) ? (int) $routeInfo['nid'] : 0; //默认为节点1
      $currentPage = isset($routeInfo['pageid']) ? $routeInfo['pageid'] : 1;
      $num = $this->getOutputNum();
      $pageNum = (int) ceil($total / $num);
      $currentPage < $pageNum ? $currentPage : $pageNum;
      return array(
         'total'          => $pageNum,
         'current'        => $currentPage,
         'nodeIdentifier' => $routeInfo['nodeIdentifier']
      );
   }

   /**
    * 根据栏目id获得栏目信息
    */
   public function getNode($nodeId)
   {
      return $this->appCaller->call(CategoryConst::MODULE_NAME, CategoryConst::APP_NAME, CategoryConst::APP_API_STRUCTURE, 'getNode', array($nodeId));
   }

   /**
    * 获得图片url
    * @param type $item
    * @param type $width
    * @param type $height
    * @return string
    */
   public function getImgcdn($url)
   {
      if (!isset($url) || empty($url)) {
         $url = 'Static/lazyicon.png';
      }
      return \Cntysoft\Kernel\get_image_oss_server_url() . DS . $url;
   }

}