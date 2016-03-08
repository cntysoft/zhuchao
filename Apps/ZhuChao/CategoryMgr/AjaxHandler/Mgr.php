<?php
/**
 * Cntysoft Cloud Software Team
 */
namespace App\ZhuChao\CategoryMgr\AjaxHandler;
use Cntysoft\Kernel\App\AbstractHandler;
use Cntysoft\Kernel;
use App\ZhuChao\CategoryMgr\Constant as CATE_CONST;
use Cntysoft\Stdlib\Tree;
use App\ZhuChao\CategoryMgr\Model\StdCategory as StdCateModel;
use App\ZhuChao\CategoryMgr\Exception;
use Cntysoft\Stdlib\Filesystem;
/**
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
class Mgr extends AbstractHandler
{
   /**
    * 获取商品分类的属性名称
    * 
    * @param array $params
    * @return array
    */
   public function getCategoryAttrNames(array $params)
   {
      $this->checkRequireFields($params, array(
         'cid'
      ));

      $category = $this->getAppCaller()->call(
              CATE_CONST::MODULE_NAME, CATE_CONST::APP_NAME, CATE_CONST::APP_API_MGR, 'getNode', array($params['cid'])
      );
      $normalAttrs = $category->getAttrs();
      $stdAttrs = $category->getStdAttrs();

      $names = array();
      foreach ($normalAttrs as $attr) {
         $names[] = array('name' => $attr->getName());
      }
      foreach ($stdAttrs as $attr) {
         $names[] = array('name' => $attr->getName());
      }
      return $names;
   }

   public function saveCategoryQueryAttrs(array $params)
   {
      $this->checkRequireFields($params, array('categoryId', 'attrs'));
      $cid = (int) $params['categoryId'];
      $attrs = $params['attrs'];
      $db = Kernel\get_db_adapter();
      $mgr = $this->getAppCaller()->getAppObject(
              CATE_CONST::MODULE_NAME, CATE_CONST::APP_NAME, CATE_CONST::APP_API_MGR
      );
      try {
         $db->begin();
         if (!empty($attrs['newAttrs'])) {
            foreach ($attrs['newAttrs'] as $attr) {
               $mgr->addQueryAttr($cid, $attr);
            }
         }
         if (!empty($attrs['updateAttrs'])) {
            foreach ($attrs['updateAttrs'] as $attr) {
               $mgr->updateQueryAttr($cid, $attr);
            }
         }
         if (!empty($attrs['deleteAttrs'])) {
            $mgr->deleteQueryAttr($cid, $attrs['deleteAttrs']);
         }
         $db->commit();
      } catch (Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
   }

   public function getCategoryQueryAttrs(array $params)
   {
      $this->checkRequireFields($params, array('categoryId'));
      $mgr = $this->getAppCaller()->getAppObject(
              CATE_CONST::MODULE_NAME, CATE_CONST::APP_NAME, CATE_CONST::APP_API_MGR
      );
      $attrs = $mgr->getNodeQueryAttrs((int) $params['categoryId']);
      $ret = array();
      foreach ($attrs as $attr) {
         $ret[] = $attr->toArray(true);
      }
      return $ret;
   }

   public function getChildren(array $params)
   {
      $this->checkRequireFields($params, array('id'));
      $id = (int) $params['id'];
      $mgr = $this->getAppCaller()->getAppObject(
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
    * 添加商品分类
    * 
    * @param array $params
    */
   public function addNode(array $params)
   {
      $this->checkRequireFields($params, array(
         'pid', 'name', 'identifier', 'img', 'nodeType'
      ));
      $mgr = $this->getAppCaller()->getAppObject(
              CATE_CONST::MODULE_NAME, CATE_CONST::APP_NAME, CATE_CONST::APP_API_MGR
      );
      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         $nodeType = (int) $params['nodeType'];
         $pid = (int) $params['pid'];
         $normalAttrs = isset($params['normalAttrs']) ? $params['normalAttrs'] : null;
         unset($params['pid']);
         unset($params['nodeType']);
         unset($params['normalAttrs']);
         $nid = $mgr->addNode($pid, $nodeType, $params);
         if (CATE_CONST::NODE_TYPE_DETAIL_CATEGORY !== $nodeType) {
            
         }
         if ($normalAttrs) {
            $mgr->attachNormalAttrs($nid, $normalAttrs);
         }
         $db->commit();
      } catch (\Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
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
         'nid'
      ));
      $nid = (int) $params['nid'];
      $mgr = $this->getAppCaller()->getAppObject(
              CATE_CONST::MODULE_NAME, CATE_CONST::APP_NAME, CATE_CONST::APP_API_MGR
      );
      $node = $mgr->getNode($nid);
      $nodeType = $node->getNodeType();
      $ret = $node->toArray(true);
      if (CATE_CONST::NODE_TYPE_DETAIL_CATEGORY != $nodeType) {
         $ret['queryAttrs'] = $mgr->getNodeQueryAttrs($nid);
      } else {
         $ret['normalAttrs'] = $mgr->getNodeNormalAttrs($nid)->toArray();
      }
      return $ret;
   }

   /**
    * 修改商品分类节点
    * 
    * @param array $params
    */
   public function updateNodeInfo(array $params)
   {
      $this->checkRequireFields($params, array('nid', 'img'));
      $nid = (int) $params['nid'];
      $mgr = $this->getAppCaller()->getAppObject(
              CATE_CONST::MODULE_NAME, CATE_CONST::APP_NAME, CATE_CONST::APP_API_MGR
      );

      $db = Kernel\get_db_adapter();
      try {
         $db->begin();
         $node = $mgr->getNode($nid);
         $nodeType = $node->getNodeType();
         //处理商品分类的图标
         $icon = $params['img'];
         $oldIcon = $node->getImg();
         if ($oldIcon) {//已有图标
            if (isset($icon) && $icon != $oldIcon) {
//               $ossClient = $this->di->get('OssClient');
//               $bucketName = Kernel\get_image_oss_bucket_name();
//
//               $isDelete = false;
//               $response = $ossClient->deleteObject($bucketName, $oldIcon);
//               if ($ossClient->responseIsOk($response)) {
//                  $isDelete = true;
//               }

               $isDelete = false;
               if (Filesystem::deleteFile(CNTY_ROOT_DIR . $oldIcon)) {
                  $isDelete = true;
               }
               if (!$isDelete) {
                  $errorType = $this->getErrorType();
                  Kernel\throw_exception(new Exception($errorType->msg('E_DELETE_ICON_ERROR'), $errorType->code('E_DELETE_ICON_ERROR')));
               }

               $node->setImg($icon);
            }
         } else {
            $node->setImg($icon);
         }

         if (CATE_CONST::NODE_TYPE_DETAIL_CATEGORY == $nodeType) {
            $updateAttrs = array();
            $newNormalAttrs = array();
//            $newStdAttrs = array();
            $normalAttrs = $params['normalAttrs'];
            $newAttrIds = array();
            foreach ($normalAttrs as $attr) {
               if (array_key_exists('id', $attr)) {
                  $newAttrIds[] = (int) $attr['id'];
                  $updateAttrs[] = $attr;
               } else {
                  $newNormalAttrs[] = $attr;
               }
            }
//                $stdAttrs = $params['stdAttrs'];
//                foreach ($stdAttrs as $attr) {
//                    if (array_key_exists('id', $attr)) {
//                        $newAttrIds[] = (int) $attr['id'];
//                        $updateAttrs[] = $attr;
//                    } else {
//                        $newStdAttrs[] = $attr;
//                    }
//                }
            //所有的
            $oldAttrs = $node->getAttrs();
            $oldAttrIds = array();
            foreach ($oldAttrs as $attr) {
               $oldAttrIds[] = $attr->getId();
            }
            $deleteAttrIds = array_diff($oldAttrIds, $newAttrIds);
            if (!empty($deleteAttrIds)) {
               $mgr->deleteAttrsById($deleteAttrIds);
            }
            //修改的节点
            if (!empty($updateAttrs)) {
               $mgr->updateNodeAttrs($nid, $updateAttrs);
            }
            if (!empty($newNormalAttrs)) {
               $mgr->attachNormalAttrs($nid, $newNormalAttrs);
            }
//                if (!empty($newStdAttrs)) {
//                    $mgr->attachStdAttrs($nid, $newStdAttrs);
//                }
         }

         //处理 identifier
         if (array_key_exists('identifier', $params)) {
            $node->setIdentifier($params['identifier']);
         }
         //处理分类名称
         if (array_key_exists('name', $params)) {
            $node->setName($params['name']);
         }
         $node->save();
         $db->commit();
      } catch (\Exception $ex) {
         $db->rollback();
         Kernel\throw_exception($ex, $this->getErrorTypeContext());
      }
   }

   /**
    * 获取指定的标准商品分类列表
    * 
    * @param array $params
    * @return array
    */
   public function getStdCategoryList($params)
   {
      $this->checkRequireFields($params, array('id'));
      $id = (int) $params['id'];
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
      $node = $this->getAppCaller()->call(
              CATE_CONST::MODULE_NAME, CATE_CONST::APP_NAME, CATE_CONST::APP_API_MGR, 'getNode', array((int) $params['id']));

      $ret = array();
      $stdAttrs = $node->getStdAttrs();
      foreach ($stdAttrs as $attr) {
         $item = $attr->toArray(true);
         array_push($ret, $item);
      }

      return $ret;
   }

   /**
    * 保存节点的规格属性
    * 
    * @param array $params
    */
   public function saveCategoryStdAttrs($params)
   {
      $this->checkRequireFields($params, array('values', 'nid'));
      $values = $params['values'];
      $nid = $params['nid'];

      $mgr = $this->getAppCaller()->getAppObject(CATE_CONST::MODULE_NAME, CATE_CONST::APP_NAME, CATE_CONST::APP_API_MGR);

      $node = $mgr->getNode($nid);
      $nodeType = $node->getNodeType();
      if (CATE_CONST::NODE_TYPE_DETAIL_CATEGORY == $nodeType) {
         $updateAttrs = array();
         $newStdAttrs = array();
         $newAttrIds = array();
         foreach ($values as $attr) {
            if (array_key_exists('id', $attr)) {
               $newAttrIds[] = (int) $attr['id'];
               $updateAttrs[] = $attr;
            } else {
               $newStdAttrs[] = $attr;
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
               $mgr->deleteStdAttrsById($deleteAttrIds);
            }
            //修改的节点
            if (!empty($updateAttrs)) {
               $mgr->updateStdAttrs($nid, $updateAttrs);
            }
            if (!empty($newStdAttrs)) {
               $mgr->attachStdAttrs($nid, $newStdAttrs);
            }

            $db->commit();
         } catch (\Exception $ex) {
            $db->rollback();
         }
      } else {
         
      }
   }

}