<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author wql <wql1211608804@163.com>
 * @copyright Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace MobileApi;
use KeleShop\Framework\OpenApi\AbstractScript;
use App\Shop\CategoryMgr\Constant as CATE_CONST;
use App\Shop\CategoryMgr\Model\Category as CategoryModel;
use Cntysoft\Stdlib\Tree;
use App\Shop\CategoryMgr\Model\StdCategory as StdCateModel;
use Cntysoft\Kernel;
class Category extends AbstractScript
{
   /**
    * 获取商品分类的所有属性
    * 
    * @param array $params
    * @return array
    */
   public function getCategoryAttrs(array $params)
   {
      $this->checkRequireFields($params, array(
         'id'
      ));

      $category = $this->appCaller->call(
              CATE_CONST::MODULE_NAME, CATE_CONST::APP_NAME, CATE_CONST::APP_API_MGR, 'getNode', array($params['id'])
      );
      $normalAttrs = $category->getAttrs();

      $ret = array();
      $group = array();
      foreach ($normalAttrs as $attr) {
         $attrGroup = $attr->getGroup();
         if (in_array($attrGroup, $group)) {
            $ret = $this->addAttrToGroup($ret, $attrGroup, array(
               'attrid' => $attr->getId(),
               'name'   => $attr->getName(),
               'value'  => explode(',', $attr->getOptValue()))
            );
         } else {
            array_push($group, $attrGroup);
            $ret[] = array('attrs' => array(array(
                     'attrid' => $attr->getId(),
                     'name'   => $attr->getName(),
                     'value'  => explode(',', $attr->getOptValue()))
               ), 'group' => $attrGroup);
         }
      }
      return $ret;
   }

   /**
    * 
    * 增加一个属性
    * 
    * @param array $param
    * @param string $group
    * @param array $value
    * @return array 
    */
   protected function addAttrToGroup(&$param, $group, $value)
   {
      foreach ($param as $key => $groupattr) {
         if ($groupattr['group'] == $group) {
            array_push($groupattr['attrs'], $value);
            $param[$key] = $groupattr;
            break;
         }
      }
      return $param;
   }

   public function getChildren(array $params)
   {
      $this->checkRequireFields($params, array('id'));
      $id = (int) $params['id'];
      $mgr = $this->appCaller->getAppObject(
              CATE_CONST::MODULE_NAME, CATE_CONST::APP_NAME, CATE_CONST::APP_API_MGR
      );
      $tree = $mgr->getNodeTree();
      $nodes = $tree->getChildren($id, 1, true);
      $ret = array();
      foreach ($nodes as $node) {
         $item = $node->toArray(true);
         $item['text'] = $item['name'];
         unset($item['name']);
         if ($item['nodeType'] == CATE_CONST::NODE_TYPE_DETAIL_CATEGORY) {
            $item['leaf'] = true;
         }
         $ret[] = $item;
      }
      return $ret;
   }

   /**
    * 获取所有商品分类
    * 
    * @param array $params
    * @return array
    */
   public function getCategoryTree(array $params)
   {
      $mgr = $this->appCaller->getAppObject(
         CATE_CONST::MODULE_NAME, CATE_CONST::APP_NAME, CATE_CONST::APP_API_MGR
      );
      $tree = $mgr->getNodeTree();
      $nodes = CategoryModel::find();
      $ret = array();
      foreach ($nodes as $node) {
         $item['id'] = $node->getId();
         $item['pId'] = $node->getPid();
         $item['name'] = $node->getName();
         $item['img'] = 'http://' . Kernel\get_server_url() . $node->getImg();
         $item['depth'] = count($tree->getParents($item['id'], true));
         if ($node->getNodeType() == CATE_CONST::NODE_TYPE_DETAIL_CATEGORY) {
            $item['leaf'] = true;
         } else {
            $item['leaf'] = false;
         }
         $ret[] = $item;
      }
      return $ret;
   }

   /**
    * 添加商品分类
    * 
    * @param array $params
    */
   public function addNodeBase(array $params)
   {
      $this->checkRequireFields($params, array(
         'pId', 'name', 'identifier', 'img', 'leaf'
      ));
      return $this->appCaller->call(
                      CATE_CONST::MODULE_NAME, CATE_CONST::APP_NAME, CATE_CONST::APP_API_MGR, 'mobileAddNodeBase', array($params)
      );
   }

   /**
    * 获取商品分类信息
    * 
    * @param array $params
    * @return array
    */
   public function getNodeInfo(array $params)
   {
      $this->checkRequireFields($params, array(
         'id'
      ));
      $nid = (int) $params['id'];
      $mgr = $this->appCaller->getAppObject(
              CATE_CONST::MODULE_NAME, CATE_CONST::APP_NAME, CATE_CONST::APP_API_MGR
      );
      $node = $mgr->getNode($nid);
      $nodeType = $node->getNodeType();

      $nodeInfo = $node->toArray(true);
      $nodeInfo['img'] =  $this->getImgUrlWithServer($nodeInfo['img']);
      if (CATE_CONST::NODE_TYPE_DETAIL_CATEGORY != $nodeType) {
         $nodeInfo['leaf'] = false;
      } else {
         $nodeInfo['leaf'] = true;
         $ret['platformCategory'] = array(
            'first'  => $this->getStdCategoryList(array('pId' => 0)),
            'second' => $this->getStdCategoryList(array('pId' => $nodeInfo['stdTopId'])),
            'third'  => $this->getStdCategoryList(array('pId' => $nodeInfo['stdSubId'])),
         );
      }
      $nodeInfo['pId'] = $nodeInfo['pid'];
      $ret['category'] = $nodeInfo;
      $ret['parentCategory'] = $this->getNormalNodes();

      return $ret;
   }

   /**
    * 获取所有普通分类
    * 
    * @return array
    */
   protected function getNormalNodes()
   {
      $normalNodes = CategoryModel::find(array('nodeType=' . CATE_CONST::NODE_TYPE_NORMAL_CATEGORY));
      $parentCategory = array();
      foreach ($normalNodes as $node) {
         array_push($parentCategory, array(
            'id'   => $node->getId(),
            'name' => $node->getName()
         ));
      }
      return $parentCategory;
   }

   /**
    * 修改商品分类节点
    * 
    * @param array $params
    */
   public function updateNodeInfo(array $params)
   {
      $this->checkRequireFields($params, array('id', 'img'));
      $this->appCaller->call(
              CATE_CONST::MODULE_NAME, CATE_CONST::APP_NAME, CATE_CONST::APP_API_MGR, 'mobileUpdateNodeInfo', array($params)
      );
   }

   /**
    * 添加分类初始化
    * 
    * @return array
    */
   public function initAdd()
   {
      $parentCategory = $this->getNormalNodes();
      $platformCategory = $this->getStdCategoryList(array('pId' => 0));
      return array('parentCategory' => $parentCategory, 'platformCategory' => $platformCategory);
   }

   /**
    * 获取指定的标准商品分类列表
    * 
    * @param array $params
    * @return array
    */
   public function getStdCategoryList($params)
   {
      $this->checkRequireFields($params, array('pId'));
      $id = (int) $params['pId'];
      $tree = $this->generateStdCategoryTree();

      $list = $tree->getChildren($id, 1, true);
      $ret = array();
      foreach ($list as $cate) {
         array_push($ret, array(
            'id'   => $cate->getId(),
            'name' => $cate->getName()
         ));
      }
      return $ret;
   }

   /**
    * 生成标准商品分类数
    * 
    * @return Tree
    */
   protected function generateStdCategoryTree()
   {
      $categories = StdCateModel::find();
      $root = new StdCateModel();
      $root->assign(array(
         'id'   => 0,
         'pid'  => -1,
         'text' => 'Category Root'
      ));
      $tree = new Tree($root);
      foreach ($categories as $cate) {
         $tree->setNode($cate->getId(), $cate->getPid(), $cate);
      }

      return $tree;
   }

   /**
    * 获取商品分类的规格属性
    * 
    * @param array $params
    * @return array
    */
   public function getCategoryStdAttrs($params)
   {
      $this->checkRequireFields($params, array('id'));
      $node = $this->appCaller->call(
              CATE_CONST::MODULE_NAME, CATE_CONST::APP_NAME, CATE_CONST::APP_API_MGR, 'getNode', array((int) $params['id']));

      $ret = array();
      $stdAttrs = $node->getStdAttrs();
      foreach ($stdAttrs as $attr) {
         $item['stdid'] = $attr->getId();
         $item['name'] = $attr->getName();
         $item['value'] = array();
         
         $optVal = explode(',', $attr->getOptValue());
         foreach($optVal as $val) {
            if(strpos($val, '|')) {
               $val = explode('|', $val);
               array_push($item['value'], $val[0]);
            }else {
               array_push($item['value'], $val);
            }
         }
         array_push($ret, $item);
      }

      return $ret;
   }

   /**
    * 保存节点的属性
    * 
    * @param array $params
    */
   public function saveCategoryAttrs($params)
   {
      $this->checkRequireFields($params, array('attrs', 'id'));
      return $this->appCaller->call(
                      CATE_CONST::MODULE_NAME, CATE_CONST::APP_NAME, CATE_CONST::APP_API_MGR, 'mobileSaveCategoryAttrs', array($params));
   }

   /**
    * 保存节点的规格属性
    * 
    * @param array $params
    */
   public function saveCategoryStdAttrs($params)
   {
      $this->checkRequireFields($params, array('std', 'id'));
      return $this->appCaller->call(
                      CATE_CONST::MODULE_NAME, CATE_CONST::APP_NAME, CATE_CONST::APP_API_MGR, 'mobileSaveCategoryStdAttrs', array($params));
   }

}