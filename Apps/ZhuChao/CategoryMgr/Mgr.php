<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\ZhuChao\CategoryMgr;
use Cntysoft\Kernel\App\AbstractLib;
use App\ZhuChao\CategoryMgr\Model\CategoryAttrs as AttrModel;
use App\ZhuChao\CategoryMgr\Model\CategoryQueryAttrs as QueryAttrsModel;
use App\ZhuChao\CategoryMgr\Model\Category as CategoryModel;
use App\ZhuChao\CategoryMgr\Model\CategoryStdAttrs as StdAttrsModel;
use Cntysoft\Kernel;
class Mgr extends AbstractLib
{
   const ATTR_MODEL_CLS = 'App\ZhuChao\CategoryMgr\Model\CategoryAttrs';
   const QUERY_ATTR_MODEL_CLS = 'App\ZhuChao\CategoryMgr\Model\CategoryQueryAttrs';
   const STD_ATTR_MODEL_CLS = 'App\ZhuChao\CategoryMgr\Model\CategoryStdAttrs';

   /**
    * @var \Cntysoft\Stdlib\Tree $tree
    */
   protected $tree = null;
   /**
    * @var \Cntysoft\Stdlib\Tree $tree
    */
   protected $stdcolor = array(
      '粉红色'   => '#FFB6C1',
      '红色'    => '#FF0000',
      '酒红色'   => '#990000',
      '橘色'    => '#FFA500',
      '巧克力色'  => '#D2691E',
      '浅黄色'   => '#FFFFB1',
      '黄色'    => '#FFFF00',
      '绿色'    => '#008000',
      '深卡其布色' => '#BAB361',
      '浅绿色'   => '#98FB98',
      '深紫色'   => '#4B0082',
      '军绿色'   => '#5D762A',
      '天蓝色'   => '#1EDDFF',
      '褐色'    => '#FF0000',
      '浅灰色'   => '#E4E4E4',
      '紫色'    => '#800080',
      '紫罗兰色'  => '#DDA0DD',
      '蓝色'    => '#0000FF',
      '深蓝色'   => '#041690',
      '深灰色'   => '#666666',
      '白色'    => '#ffffff',
      '黑色'    => '#000000',
      '透明'    => '#fcfcfc'
   );

   /**
    * @return \Cntysoft\Stdlib\Tree
    */
   public function getNodeTree()
   {
      if (null == $this->tree) {
         $nodes = CategoryModel::find();
         $root = new CategoryModel();
         $root->assign(array(
            'id'   => 0,
            'pid'  => -1,
            'text' => '商品品类库'
         ));
         $tree = new \Cntysoft\Stdlib\Tree($root);
         foreach ($nodes as $node) {
            //在这里是否获取地址
            $tree->setNode($node->getId(), $node->getPid(), $node);
         }
         $this->tree = $tree;
      }
      return $this->tree;
   }

   /**
    * 增加一个产品分类
    * 
    * @param int $pid
    * @param int $nodeType
    * @param string $name
    * @param string $identifier
    * @param string $icon
    * @return int
    */
   public function addNode($pid, $nodeType, $data)
   {
      $pid = (int) $pid;
      if ($pid < 0) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_NODE_PID_ERROR'), $errorType->code('E_NODE_PID_ERROR')
                 ), $this->getErrorTypeContext());
      }

      $nodeType = (int) $nodeType;
      if (!in_array($nodeType, array(Constant::NODE_TYPE_NORMAL_CATEGORY, Constant::NODE_TYPE_DETAIL_CATEGORY))) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_NODE_TYPE_ERROR'), $errorType->code('E_NODE_TYPE_ERROR')
                 ), $this->getErrorTypeContext());
      }

      $node = new CategoryModel();
      //nodetype的加入防止每次增加分类都新建一个树
      if (Constant::NODE_STARTUP_NODE_ID != $pid) {
         $pnode = $this->getNode($pid);
         if (!$pnode) {
            $errorType = $this->getErrorType();
            Kernel\throw_exception(new Exception(
                    $errorType->msg('E_NODE_NOT_EXIST', $pid), $errorType->code('E_NODE_NOT_EXIST')
                    ), $this->getErrorTypeContext());
         }
         if ($pnode->getNodeType() == Constant::NODE_TYPE_DETAIL_CATEGORY) {
            $errorType = $this->getErrorType();
            Kernel\throw_exception(new Exception(
                    $errorType->msg('E_NODE_LEVEL_ERROR'), $errorType->code('E_NODE_LEVEL_ERROR')
                    ), $this->getErrorTypeContext());
         }
      }
      
      $node->setPid($pid);
      $node->setNodeType($nodeType);
      $node->setCreateTime(time());
      $node->assignBySetter($data);
      $node->create();
      return $node->getId();
   }

   /**
    * 根据节点的id获取查询属性
    *
    * @param int $cid
    * @return \Phalcon\Mvc\Model\Resultset\Simple
    */
   public function getNodeQueryAttrs($cid)
   {
      $cid = (int) $cid;
      $node = $this->getNode($cid);
      return QueryAttrsModel::find(array(
                 'categoryId = ?0',
                 'bind' => array(
                    0 => $cid
                 )
      ));
   }

   /**
    * 添加查询属性
    * 
    * @param int $cid
    * @param array $attr
    */
   public function addQueryAttr($cid, array $attr)
   {
      $cid = (int) $cid;
      $node = $this->getNode($cid);
      $nodeType = $node->getNodeType();
      if (Constant::NODE_TYPE_DETAIL_CATEGORY != $nodeType) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_NODE_TYPE_ERROR', $nodeType), $errorType->code('E_NODE_TYPE_ERROR')
         ));
      }
      $attrObj = new QueryAttrsModel();
      $attrObj->assignBySetter($attr);
      $attrObj->create();
   }

   /**
    * 更新查询属性
    * 
    * @param int $cid
    * @param array $attr
    */
   public function updateQueryAttr($cid, array $attr)
   {
      $cid = (int) $cid;
      $node = $this->getNode($cid);
      $nodeType = $node->getNodeType();
      $this->checkRequireFields($attr, array('id'));
      $aid = $attr['id'];
      unset($attr['id']);
      unset($attr['categoryId']);
      unset($attr['name']);
      if (Constant::NODE_TYPE_DETAIL_CATEGORY != $nodeType) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_NODE_TYPE_ERROR', $nodeType), $errorType->code('E_NODE_TYPE_ERROR')
         ));
      }
      $record = QueryAttrsModel::findFirst($aid);
      $record->assignBySetter($attr);
      $record->save();
   }

   /**
    * 删除查询属性
    * 
    * @param int $cid
    * @param array $attrs
    */
   public function deleteQueryAttr($cid, array $attrs)
   {
      $modelsManager = Kernel\get_models_manager();
      $query = sprintf('DELETE FROM %s WHERE id in (%s)', self::QUERY_ATTR_MODEL_CLS, implode(', ', $attrs));
      $modelsManager->executeQuery($query);
   }

   /**
    * @param int $nid
    * @return \Phalcon\Mvc\Model\Resultset\Simple
    */
   public function getNodeNormalAttrs($nid)
   {
      $nid = (int) $nid;
      $node = $this->getNode($nid);
      $nodeType = $node->getNodeType();
      if (Constant::NODE_TYPE_DETAIL_CATEGORY !== $nodeType) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_NODE_TYPE_ERROR', $nodeType), $errorType->code('E_NODE_TYPE_ERROR')
         ));
      }
      return AttrModel::find(array(
                 'nodeId = ?0',
                 'bind' => array(
                    0 => $nid
                 )
      ));
   }

   /**
    * @param int $nid
    * @return \Phalcon\Mvc\Model\Resultset\Simple
    */
   public function getNodeAttrs($nid)
   {
      return AttrModel::find(array(
                 'nodeId = ?0',
                 'bind' => array(
                    0 => (int) $nid
                 )
      ));
   }

   /**
    * 获取商品分类的规格属性
    * 
    * @param int $nid
    * @return \Phalcon\Mvc\Model\Resultset\Simple
    */
   public function getNodeStdAttrs($nid)
   {
      return StdAttrsModel::find(array(
                 'nodeId = ?0',
                 'bind' => array(
                    0 => (int) $nid
                 )
      ));
   }

   /**
    * 设置普通的属性
    *
    * @param $nid
    * @param array $attrs
    */
   public function attachNormalAttrs($nid, array $attrs)
   {
      $this->attachAttrs($nid, $attrs);
   }

   /**
    * 设置标准的属性，这个属性是能影响价格与图片的
    *
    * @param $nid
    * @param array $attrs
    */
   public function attachStdAttrs($nid, array $attrs)
   {
      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         foreach ($attrs as $attr) {
            $attr['nodeId'] = $nid;
            $stdAttr = new StdAttrsModel();
            $attr['optValue'] = ($attr['name'] === '颜色') ? $this->formatStdColor(explode(',', $attr['optValue'])) : $attr['optValue'];
            $stdAttr->assignBySetter($attr);
            $stdAttr->create();
         }
         $db->commit();
      } catch (\Exception $e) {
         $db->rollback();
      }
   }

   /**
    * <code>
    * array(
    *     array(
    *       'nodeId', 'name' , 'value', 'required', 'attrType', 'group','optValue'
    *    )
    * );
    * </code>
    * @param int $nid
    * @param array $attrs
    */
   protected function attachAttrs($nid, array $attrs)
   {
      $nid = (int) $nid;
      $node = $this->getNode($nid);
      if (!$node) {
         $errorType = $this->getErrorType();
         throw_exception(new Exception(
                 $errorType->msg('E_NODE_NOT_EXIST', $nid), $errorType->code('E_NODE_NOT_EXIST')
         ));
      }
      $nodeType = $node->getNodeType();
      //分情况
      if (Constant::NODE_TYPE_NORMAL_CATEGORY == $nodeType) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_NODE_DONOT_SUPPORT_ATTACH_ATTR'), $errorType->code('E_NODE_DONOT_SUPPORT_ATTACH_ATTR')
         ));
      } else if (Constant::NODE_TYPE_DETAIL_CATEGORY == $nodeType) {
         $db = Kernel\get_db_adapter();
         try {
            $db->begin();
            foreach ($attrs as $item) {
               $this->checkRequireFields($item, array(
                  'name'
               ));

               $item += array(
                  'required' => false
               );
               $newattr = new AttrModel();
               $item+= array(
                  'required' => false
               );
               $item['nodeId'] = $nid;
               $newattr->assignBySetter($item);
               $newattr->create();
            }
            $db->commit();
         } catch (\Exception $ex) {
            $db->rollback();
            Kernel\throw_exception($ex, $this->getErrorTypeContext());
         }
      }
   }

   /**
    * <code>
    * array(
    *     array(
    *       'nodeId', 'name' , 'value', 'required', 'attrType', 'group','optValue'
    *    )
    * );
    * </code>
    *
    * @param int $nid
    * @param array $attrs
    */
   public function updateNodeAttrs($nid, array $attrs)
   {
      $nid = (int) $nid;
      $node = $this->getNode($nid);
      if (!$node) {
         $errorType = $this->getErrorType();
         throw_exception(new Exception(
                 $errorType->msg('E_NODE_NOT_EXIST', $nid), $errorType->code('E_NODE_NOT_EXIST')
         ));
      }
      $nodeType = $node->getNodeType();
      if (Constant::NODE_TYPE_NORMAL_CATEGORY == $nodeType) {
         //查询的是不能更新的
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_NODE_TYPE_ERROR', $nodeType), $errorType->code('E_NODE_TYPE_ERROR')
         ));
      } else if (Constant::NODE_TYPE_DETAIL_CATEGORY == $nodeType) {
         $db = Kernel\get_db_adapter();
         try {
            $db->begin();
            foreach ($attrs as $attr) {
               if (!isset($attr['id'])) {
                  continue;
               }
               $attrId = (int) $attr['id'];
               $attrObj = $this->getNodeAttr($nid, $attrId);
               if (!$attrObj) {
                  //不存在是否再添加
                  $this->checkRequireFields($attr, array(
                     'name', 'group'
                  ));
                  $newattr = new AttrModel();
                  $attr+= array(
                     'required' => false
                  );
                  unset($attr['id']);
                  $attr['nodeId'] = $nid;
                  $newattr->assignBySetter($attr);
                  $newattr->create();
               } else {
                  unset($attr['id']);
                  unset($attr['nodeId']);
                  $attrObj->assignBySetter($attr);
                  $attrObj->save();
               }
            }
            $db->commit();
         } catch (\Exception $ex) {
            $db->rollback();
            Kernel\throw_exception($ex, $this->getErrorTypeContext());
         }
      }
   }

   /**
    * @param array $ids
    */
   public function deleteAttrsById(array $ids)
   {
      $modelsManager = Kernel\get_models_manager();
      $query = sprintf('DELETE FROM %s WHERE id in (%s)', self::ATTR_MODEL_CLS, implode(', ', $ids));
      $modelsManager->executeQuery($query);
   }

   /**
    * 删除规格属性信息
    * 
    * @param array $ids
    */
   public function deleteStdAttrsById(array $ids)
   {
      $modelsManager = Kernel\get_models_manager();
      $query = sprintf('DELETE FROM %s WHERE id in (%s)', self::STD_ATTR_MODEL_CLS, implode(', ', $ids));
      $modelsManager->executeQuery($query);
   }

   /**
    * @param int $id
    * @param int $attrName
    * @return boolean
    */
   public function nodeHasAttr($nid, $attrName)
   {
      return AttrModel::count(array(
                 'nodeId = ?0 AND name = ?1',
                 'bind' => array(
                    0 => $nid,
                    1 => $attrName
                 )
              )) > 0 ? true : false;
   }

   /**
    * 获取指定名称的属性
    *
    * @param int $nid
    * @param string $attrId
    * @return \App\ZhuChao\CategoryMgr\Model\CategoryAttrs
    */
   public function getNodeAttr($nid, $attrId)
   {
      return AttrModel::findFirst(array(
                 'nodeId = ?0 AND id = ?1',
                 'bind' => array(
                    0 => $nid,
                    1 => $attrId
                 )
      ));
   }

   /**
    * 获取指定的规格属性
    * 
    * @param int $nid
    * @param int $attrId
    * @return \App\ZhuChao\CategoryMgr\Model\CategoryStdAttrs
    */
   public function getNodeStdAttr($nid, $attrId)
   {
      return StdAttrsModel::findFirst(array(
                 'nodeId = ?0 AND id = ?1',
                 'bind' => array(
                    0 => $nid,
                    1 => $attrId
                 )
      ));
   }

   /**
    * @param int $nid
    * @return \App\ZhuChao\CategoryMgr\Model\Category
    */
   public function getNode($nid)
   {
      return CategoryModel::findFirst((int) $nid);
   }

   /**
    * 根据节点的标识符获取节点
    * 
    * @param string $identifier
    * @return \App\ZhuChao\CategoryMgr\Model\Category
    */
   public function getNodeByIdentifier($identifier)
   {
      $node = CategoryModel::findFirst(array(
                 'identifier = ?0',
                 'bind' => array(
                    0 => $identifier,
                 )
      ));
      if (!$node) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_NODE_NOT_EXIST', $identifier), $errorType->code('E_NODE_NOT_EXIST')), $this->getErrorTypeContext());
      }
      return $node;
   }

   /**
    * 根据栏目标识符查找商品栏目ID
    *
    * @param array $nodeIdentifiers
    * @return mixed
    * @throws \Exception
    */
   public function getNodesByIdentifiers(array $nodeIdentifiers)
   {
      $ret = CategoryModel::find(CategoryModel::generateRangeCond('identifier', $nodeIdentifiers));

      if (!$ret) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_NODE_NOT_EXIST', $nodeIdentifiers), $errorType->code('E_NODE_NOT_EXIST')), $this->getErrorTypeContext());
      }

      return $ret;
   }

   /**
    * @param int $id
    * @param string $name
    * @return \App\ZhuChao\CategoryMgr\Model\Category
    */
   public function changeName($id, $name)
   {
      if (!$this->hasNode($id)) {
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_NODE_NOT_EXIST', $id), $errorType->code('E_NODE_NOT_EXIST')
                 ), $this->getErrorTypeContext());
      }
      $node = $this->getNode($id);
      $node->setName($name);
      $node->save();
   }

   /**
    * 获取孩子分类节点集合
    *
    * @param int $id
    * @param bool $pure
    * @return \Phalcon\Mvc\Model\Resultset\Simple
    */
   public function getChildren($id)
   {
      $set = CategoryModel::find(array(
                 'pid = ?0',
                 'bind' => array(
                    0 => $id
                 )
      ));
      return $set;
   }

   /**
    * 检查商品分类是否存在
    *
    * @param $id
    * @return bool
    */
   public function hasNode($id)
   {
      return CategoryModel::count($id) > 0 ? true : false;
   }

   /**
    * 更新规格属性
    * 
    * @param int $nid
    * @param array $attrs
    */
   public function updateStdAttrs($nid, $attrs)
   {
      $nid = (int) $nid;
      $node = $this->getNode($nid);
      if (!$node) {
         $errorType = $this->getErrorType();
         throw_exception(new Exception(
                 $errorType->msg('E_NODE_NOT_EXIST', $nid), $errorType->code('E_NODE_NOT_EXIST')
         ));
      }
      $nodeType = $node->getNodeType();
      if (Constant::NODE_TYPE_NORMAL_CATEGORY == $nodeType) {
         //查询的是不能更新的
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
                 $errorType->msg('E_NODE_TYPE_ERROR', $nodeType), $errorType->code('E_NODE_TYPE_ERROR')
         ));
      } else if (Constant::NODE_TYPE_DETAIL_CATEGORY == $nodeType) {
         $db = Kernel\get_db_adapter();
         try {
            $db->begin();
            foreach ($attrs as $attr) {
               if (!isset($attr['id'])) {
                  continue;
               }
               $attrId = (int) $attr['id'];
               $attrObj = $this->getNodeStdAttr($nid, $attrId);
               $attr['optValue'] = ($attr['name'] === '颜色') ? $this->formatStdColor(explode(',', $attr['optValue'])) : $attr['optValue'];
               if (!$attrObj) {
                  //不存在是否再添加
                  $this->checkRequireFields($attr, array(
                     'name'
                  ));
                  $newattr = new StdAttrsModel();
                  unset($attr['id']);
                  $attr['nodeId'] = $nid;
                  $newattr->assignBySetter($attr);
                  $newattr->create();
               } else {
                  unset($attr['id']);
                  unset($attr['nodeId']);
                  $attrObj->assignBySetter($attr);
                  $attrObj->save();
               }
            }
            $db->commit();
         } catch (\Exception $ex) {
            $db->rollback();
            Kernel\throw_exception($ex, $this->getErrorTypeContext());
         }
      }
   }

   public function getNodes($orderBy = 'id asc', $offset = 0, $limit = 15)
   {
      return CategoryModel::find(array(
                 'order' => $orderBy,
                 'limit' => array(
                    'number' => $limit,
                    'offset' => $offset
                 )
      ));
   }

   /**
    * 获取叶子分类集合
    * 
    * @return objectlist
    */
   public function getLeafNodes()
   {
      return CategoryModel::find(array(
                 'nodeType = ?1',
                 'bind' => array(
                    1 => 2
                 )
      ));
   }

   /**
    * 商家app添加分类基本信息
    * 
    * @param array $params 
    * @return array
    */
   public function mobileAddNodeBase($params)
   {
      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         $nodeType = $params['leaf'] ? Constant::NODE_TYPE_DETAIL_CATEGORY : Constant::NODE_TYPE_NORMAL_CATEGORY;
         $pid = (int) $params['pId'];
         if ($params['leaf']) {
            $this->checkRequireFields($params, array('stdTopId', 'stdSubId', 'stdDetailId'));
         }
         unset($params['pId']);
         unset($params['leaf']);
         $ret = $this->addNode($pid, $nodeType, $params);
         $db->commit();
         return array('id' => $ret);
      } catch (\Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
   }

   /**
    * 
    * 商家app修改分类基本信息
    * 
    * 
    * @param array $params 
    */
   public function mobileUpdateNodeInfo($params)
   {

      $nid = (int) $params['id'];
      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         $node = $this->getNode($nid);
         $nodeType = $node->getNodeType();
         if ($nodeType == Constant::NODE_TYPE_DETAIL_CATEGORY) {
            $this->checkRequireFields($params, array('stdTopId', 'stdSubId', 'stdDetailId'));
            $node->setStdTopId($params['stdTopId']);
            $node->setStdSubId($params['stdSubId']);
            $node->setStdDetailId($params['stdDetailId']);
         }
         //处理商品分类的图标
         $icon = $params['img'];
         $node->setImg($icon);
         //处理 identifier
         if (array_key_exists('identifier', $params)) {
            $node->setIdentifier($params['identifier']);
         }
         //处理分类名称
         if (array_key_exists('name', $params)) {
            $node->setName($params['name']);
         }
         if (array_key_exists('pid', $params)) {
            $node->setPid($params['pid']);
         }
         $node->save();
         $db->commit();
      } catch (\Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
   }

   /**
    * 商家app保存分类属性
    * 
    * @param array $params 
    * @return array
    */
   public function mobileSaveCategoryAttrs($params)
   {
      $values = $params['attrs'];
      $nid = $params['id'];

      $node = $this->getNode($nid);
      $nodeType = $node->getNodeType();
      if (Constant::NODE_TYPE_DETAIL_CATEGORY == $nodeType) {
         $updateAttrs = array();
         $newAttrs = array();
         $newAttrIds = array();
         foreach ($values as $attr) {
            $group = $attr['group'];

            foreach ($attr['attrs'] as $attrvalue) {
               if ($attrvalue['attrid'] != 0) {
                  $newAttrIds[] = (int) $attrvalue['attrid'];
                  $updateAttrs[] = array(
                     'id'       => $attrvalue['attrid'],
                     'name'     => $attrvalue['name'],
                     'group'    => $group,
                     'optValue' => implode(',', $attrvalue['value'])
                  );
               } else {
                  $newAttrs[] = array(
                     'name'     => $attrvalue['name'],
                     'group'    => $group,
                     'optValue' => implode(',', $attrvalue['value'])
                  );
               }
            }
         }
         //所有的
         $oldAttrs = $node->getAttrs();
         $oldAttrIds = array();
         foreach ($oldAttrs as $attr) {
            $oldAttrIds[] = $attr->getId();
         }
         $deleteAttrIds = array_diff($oldAttrIds, $newAttrIds);

         $db = Kernel\get_db_adapter();
         try {
            $db->begin();
            if (!empty($deleteAttrIds)) {
               $this->deleteAttrsById($deleteAttrIds);
            }
            //修改的节点
            if (!empty($updateAttrs)) {
               $this->updateNodeAttrs($nid, $updateAttrs);
            }
            if (!empty($newAttrs)) {
               $this->attachNormalAttrs($nid, $newAttrs);
            }

            $db->commit();
            return array('success' => true);
         } catch (\Exception $ex) {
            $db->rollback();
            Kernel\throw_exception($ex, $this->getErrorTypeContext());
         }
      }
   }

   /**
    * 商家app保存分类规格
    * 
    * @param array $params 
    * @return array
    */
   public function mobileSaveCategoryStdAttrs($params)
   {
      $values = $params['std'];
      $nid = $params['id'];

      $node = $this->getNode($nid);
      $nodeType = $node->getNodeType();
      if (Constant::NODE_TYPE_DETAIL_CATEGORY == $nodeType) {
         $updateAttrs = array();
         $newStdAttrs = array();
         $newAttrIds = array();
         foreach ($values as $stdattr) {
            $optValue = ($stdattr['name'] === '颜色') ? $this->formatStdColor($stdattr['value']) : implode(',', $stdattr['value']);
            if ($stdattr['stdid'] != 0) {
               $newAttrIds[] = (int) $stdattr['stdid'];
               $updateAttrs[] = array(
                  'id'       => $stdattr['stdid'],
                  'name'     => $stdattr['name'],
                  'optValue' => $optValue
               );
            } else {
               $newStdAttrs[] = array(
                  'name'     => $stdattr['name'],
                  'optValue' => $optValue
               );
            }
         }
         //所有的
         $oldAttrs = $node->getStdAttrs();
         $oldAttrIds = array();
         foreach ($oldAttrs as $attr) {
            $oldAttrIds[] = $attr->getId();
         }
         $deleteAttrIds = array_diff($oldAttrIds, $newAttrIds);

         $db = Kernel\get_db_adapter();
         try {
            $db->begin();
            if (!empty($deleteAttrIds)) {
               $this->deleteStdAttrsById($deleteAttrIds);
            }
            //修改的节点
            if (!empty($updateAttrs)) {
               $this->updateStdAttrs($nid, $updateAttrs);
            }
            if (!empty($newStdAttrs)) {
               $this->attachStdAttrs($nid, $newStdAttrs);
            }

            $db->commit();
            return array('success' => true);
         } catch (\Exception $ex) {
            $db->rollback();
            Kernel\throw_exception($ex, $this->getErrorTypeContext());
         }
      } else {
         
      }
   }

   protected function formatStdColor($value)
   {
      $ret = array();
      $optvalue = '';
      foreach ($value as $color) {
         $stdcolor = explode('|', $color);
         if (in_array($stdcolor[0], $ret)) {
            
         } else {
            $optvalue.=key_exists($stdcolor[0], $this->stdcolor) ? $stdcolor[0] . '|' . $this->stdcolor[$stdcolor[0]] . ',' : $stdcolor[0] . '|0,';
            array_push($ret, $stdcolor[0]);
         }
      }
      return trim($optvalue, ',');
   }
   
   /**
    * 根据关键字获取分类
    * 
    * @param string $key
    * @param integer $nodeType
    * @param boolean $total
    * @param string $orderBy
    * @param integer $offset
    * @param integer $limit
    * @return 
    */
   public function searchCategory($key, $nodeType = 2, $total = false, $orderBy = 'id DESC', $offset = 0, $limit = 15)
   {
      $cond = array();
      $cond[] = "name like '%".$key."%'";
      $cond[] = 'nodeType=?0';
      $bind = array(
         0 => (int)$nodeType
      );
      $cond = implode(' and ', $cond);
      
      if($limit){
         $items = CategoryModel::find(array(
            $cond,
            'bind' => $bind,
            'order' => $orderBy,
            'limit' => array(
               'offset' => $offset,
               'number' => $limit
            )
         ));
      }else{
         $items = CategoryModel::find(array(
            $cond,
            'bind' => $bind,
            'order' => $orderBy
         ));
      }
      
      if($total){
         return array($items, CategoryModel::count(array($cond)));
      }
      
      return $items;
   }

}