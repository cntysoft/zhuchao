<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\Site\Ui\AjaxHandler;
use Cntysoft\Kernel\App\AbstractHandler;
use Cntysoft\Kernel;
use App\Site\Ui\Constant;
use App\Site\CmMgr\Constant as CMMGR_CONST;
use Cntysoft\Framework\Qs\TagRefl;
use App\Site\Ui\Exception;

class Tag extends AbstractHandler
{
   /**
    * 内建标签分类常量
    */
   CONST SiteConfig = '站点配置类';
   CONST Sys = '系统函数类';
   CONST FieldTag = '数据源字段类';
   /**
    * 获取标签的分类
    *
    * @param array $params
    * @return array
    */
   public function getTagClassify(array $params)
   {
      $this->checkRequireFields($params, array('tagType'));
      $list = $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_TAG,
         'getTagClassifies',
         array(
            $params['tagType']
         )
      );
      $ret = array();
      foreach($list as $item){
         $ret[] = array('text' => Kernel\convert_2_utf8($item));
      }
      return $ret;
   }
   /**
    * @param array $params
    * @return array
    */
   public function getClassifyChildren(array $params)
   {
      $this->checkRequireFields($params, array(
         'id', 'nodeType'
      ));
      $tagType = $params['id'];
      $nodeType = (int) $params['nodeType'];
      if(Constant::N_T_ROOT == $nodeType){
         //输出分类
         $classifies = array(
            TagRefl::T_DS,
            TagRefl::T_LABLE
         );
      }else if(Constant::N_T_TYPE == $nodeType){
         $classifies = $this->getAppCaller()->call(
            Constant::MODULE_NAME,
            Constant::APP_NAME,
            Constant::APP_API_TAG,
            'getTagClassifies',
            array(
               $tagType
            )
         );
      }

      $ret = array();
      foreach ($classifies as $item){
         $item = Kernel\convert_2_utf8($item);
         $ret[] = array(
            'id' => $item,
            'text' => $item,
            'nodeType' => Constant::N_T_ROOT === $nodeType ? Constant::N_T_TYPE : Constant::N_T_CLASSIFY,
            'leaf' => Constant::N_T_TYPE === $nodeType ? true : false
         );
      }
      return $ret;
   }

   /**
    * 获取标签列表
    *
    * @param array $params
    * @return array
    */
   public function getTagLists(array $params)
   {
      $this->checkRequireFields($params, array('classify', 'tagType'));
      $orderBy = $limit = $offset = null;
      $this->getPageParams($orderBy, $limit, $offset, $params);
      $classify = Kernel\real_path($params['classify']);
      $tagType = $params['tagType'];
      $list = $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_TAG,
         'getTagList',
         array(
            array($tagType)
         )
      );
      $this->checkClassifyExist($list[$tagType], $classify);
      $list = $list[$tagType][$classify];
      $list = array_slice($list, $offset, $limit);
      $ret = array();
      foreach($list as $name => $item){
         $ret[] = array(
            'tagType' => $tagType,
            'classify' => Kernel\convert_2_utf8($classify),
            'name' => is_int($name) ? Kernel\convert_2_utf8($item['id']) : Kernel\convert_2_utf8($name),
            'description' => $item['description']
         );
      }
      return $ret;
   }

   /**
    * 判断标签指定的分类是否存在
    *
    * @param array $data
    * @param string $classify
    */
   protected function checkClassifyExist(array $data, $classify)
   {
      if(!array_key_exists($classify, $data)){
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_TAG_CLASSIFY_NOT_EXIST', $classify), $errorType->code('E_TAG_CLASSIFY_NOT_EXIST')
         ), $this->getErrorTypeContext());
      }
   }

	/**
	 * 复制标签生成新的标签
	 *
	 * @param array $params
	 */
	public function copyTag(array $params)
	{
		$this->checkRequireFields($params, array('name', 'tagType', 'classify'));
		$this->getAppCaller()->call(
			 Constant::MODULE_NAME,
			 Constant::APP_NAME,
			 Constant::APP_API_TAG,
			 'copyTag',
			 array($params['tagType'], $params['classify'], $params['name'])
		);
	}

   public function deleteTag(array $params)
   {
      $this->checkRequireFields($params, array('name', 'tagType', 'classify'));
      $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_TAG,
         'deleteTag',
         array($params['tagType'], $params['classify'], $params['name'])
      );
   }
   /**
    * 根据参数beforeClassify的值添加或修改分类
    *
    * @param array $params
    */
   public function classifyChange(array $params)
   {
      $this->checkRequireFields($params, array('classify', 'rootType', 'newClassify'));
      $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_TAG,
         'classifyChange',
         array($params['rootType'], $params['classify'], $params['newClassify'])
      );
   }

	/**
	 * 删除一个标签分类
	 *
	 * @param array $params
	 */
	public function deleteClassify(array $params)
	{
		$this->checkRequireFields($params, array('classify', 'rootType'));
		$this->getAppCaller()->call(
			 Constant::MODULE_NAME,
			 Constant::APP_NAME,
			 Constant::APP_API_TAG,
			 'deleteClassify',
			 array($params['rootType'], $params['classify'])
		);
	}

   /**
    * 判断一个标签是否存在
    *
    * @param array $params
    * @return array
    */
   public function tagNameExist(array $params)
   {
      $this->checkRequireFields($params, array('tagType','classify','name'));
      return array(
         'exist' => $this->getAppCaller()->call(
            Constant::MODULE_NAME,
            Constant::APP_NAME,
            Constant::APP_API_TAG,
            'tagNameExist',
            array(
               $params['tagType'],
               $params['classify'],
               $params['name']
            )
         )
      );
   }

   /**
    * 判断标签的脚本类名是否存在
    *
    * @param array $params
    * @return array
    */
   public function tagClassExist(array $params)
   {
      $this->checkRequireFields($params, array('tagType', 'classify', 'tagClass'));
      return array(
         'exist' => $this->getAppCaller()->call(
            Constant::MODULE_NAME,
            Constant::APP_NAME,
            Constant::APP_API_TAG,
            'tagNameExist',
            array(
               $params['tagType'],
               $params['classify'],
               $params['tagClass']
            )
         )
      );
   }

   /**
    * 创建一个全新的标签
    *
    * @param Array $params
    */
   public function createTag(array $params)
   {
      $this->checkRequireFields($params, array('tagType', 'meta'));
      $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_TAG,
         'createTag',
         array(
            $params['tagType'],
            $params['meta']
         )
      );
   }

   /**
    * 保存一个标签的元信息
    *
    * @param array $params
    */
   public function updateTagMeta(array $params)
   {
      $this->checkRequireFields($params, array('tagType', 'sourceClassify', 'sourceTagName', 'meta'));
      $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_TAG,
         'updateTagMeta',
         array(
            $params['tagType'],
            $params['sourceClassify'],
            $params['sourceTagName'],
            $params['meta']
         )
      );
   }

   /**
    * 获取标签的元信息
    *
    * @param array $params
    * @return array
    */
   public function getTagMetaInfo(array $params)
   {
      $this->checkRequireFields($params, array('tagType', 'classify', 'name'));
      return $this->getAppCaller()->call(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_TAG,
         'getTagMeta',
         array(
            $params['tagType'],
            $params['classify'],
            $params['name']
         )
      );
   }

   /**
    * 一次获取所有的标签列表，生成一个标准的树形结构
    *
    * @return array
    */
   public function getTagListTreeNodes()
   {
      $tagAppRef = $this->getAppCaller()->getAppObject(
         Constant::MODULE_NAME,
         Constant::APP_NAME,
         Constant::APP_API_TAG
      );
      $data = TagRefl::getTagList(array(TagRefl::T_LABLE, TagRefl::T_DS));
      $data[TagRefl::T_BUILDIN][self::SiteConfig] = $tagAppRef->getSiteCfgTags();
      $data[TagRefl::T_BUILDIN][self::Sys] = $tagAppRef->getSysTags();
      $ret = array(
         'text' => 'Tag Classify Tree',
         'children' => array()
      );
      foreach ($data as $type => $classifies){
         $type = Kernel\convert_2_utf8($type);
         $typeItem = array(
            'text' => $type,
            'expanded' => true,
            'children' => array()
         );
         foreach ($classifies as $classify => $tags){
            $classify = Kernel\convert_2_utf8($classify);
            $classifyItem = array(
               'text' => $classify,
               'children' => array()
            );
            foreach($tags as $tag => $meta){
               $tag = Kernel\convert_2_utf8($tag);
               $meta['tagType'] = $type;
               $classifyItem['children'][] = array(
                  'text' => $tag,
                  'leaf' => true,
                  'meta' => $meta,
                  'qtip' => array_key_exists('description', $meta) ? $meta['description'] : null
               );
            }
            $typeItem['children'][] = $classifyItem;
         }
         $ret['children'][] = $typeItem;
      }
      return $ret;
   }

   /**
    * 获取模型id列表
    */
   public function getModelIdList()
   {
      $modelList = $this->getAppCaller()->call(
         CMMGR_CONST::MODULE_NAME,
         CMMGR_CONST::APP_NAME,
         CMMGR_CONST::APP_API_MGR,
         'getModelList'
      );
      $modelIdList = array(array('id'=>0, 'name'=>'不限定模型'));
      foreach ($modelList as $value){
         $model['id'] = $value->getId();
         $model['name'] = $value->getName();
         array_push($modelIdList, $model);
      }
      return $modelIdList;
   }
}