<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\Site\Ui;
use Cntysoft\Kernel\App\AbstractLib;
use Cntysoft\Framework\Qs\TagRefl;
use Cntysoft\Kernel;
use Qs\Lib\SiteConfig;
use Qs\Lib\Sys;
/**
 * 标签管理类定义
 */
class Tag extends AbstractLib
{
   /**
    * 获取标签的列表
    *
    * @param array $tagType
    * @return array
    */
   public function getTagList(array $tagType)
   {
      return TagRefl::getTagList($tagType);
   }

   /**
    * 获取标签的元信息
    *
    * @param string $tagType
    * @param string $classify
    * @param string $tagName
    * @return array
    */
   public function getTagMeta($tagType, $classify, $tagName)
   {
      return TagRefl::getTagMeta($tagType, $classify, $tagName);
   }

   /**
    * 获取标签的分类
    *
    * @param $tagType
    * @return array
    */
   public function getTagClassifies($tagType)
   {
      return TagRefl::getTagClassifies($tagType);
   }

	/**
	 * 复制一个标签
	 *
	 * @param $tagType
	 * @param $classify
	 * @param $tagName
	 */
	public function copyTag($tagType, $classify, $tagName)
	{
		TagRefl::copyTag($tagType, $classify, $tagName);
	}
   /**
    * 判断一个标签是否存在
    *
    * @param string $tagType
    * @param string $classify
    * @param string $tagName
    * @return boolean
    */
   public function tagNameExist($tagType, $classify, $tagName)
   {
      try {
         TagRefl::checkTagExist($tagType, $classify, $tagName, true);
         return true;
      } catch (\Exception $e) {
         return false;
      }
   }

   /**
    * 添加或修改分类
    *
    * @param string $tagType
    * @param string $classify
    * @param string $newClassify
    * @throws \Exception
    */
   public function classifyChange($tagType, $classify, $newClassify)
   {
      if(!$this->classifyExist($tagType, Kernel\real_path($newClassify))){
         TagRefl::classifyChange($tagType, $classify, $newClassify);
      }else{
         $errorType = $this->getErrorType();
         Kernel\throw_exception(new Exception(
            $errorType->msg('E_TAG_CLASSIFY_EXIST', $classify),
            $errorType->code('E_TAG_CLASSIFY_EXIST')
         ), $this->getErrorTypeContext());
      }
   }

	/**
	 * 删除标签分类
	 *
	 * @param $tagType
	 * @param $classify
	 *
	 * @throws \Exception
	 */
	public function deleteClassify($tagType, $classify)
	{
		if(!$this->classifyExist($tagType, Kernel\real_path($classify))){
			$errorType = $this->getErrorType();
			Kernel\throw_exception(new Exception(
				$errorType->msg('E_TAG_CLASSIFY_NOT_EXIST'),
				$errorType->code('E_TAG_CLASSIFY_NOT_EXIST')
			), $this->getErrorTypeContext());
		}

		TagRefl::deleteClassify($tagType, $classify);
	}

   /**
    * 判断一个分类是否存在
    *
    * @param $tagType
    * @param $classify
    * @return bool
    */
   public function classifyExist($tagType, $classify)
   {
      return TagRefl::classifyExist($tagType, $classify);
   }

   /**
    * 新建一个标签
    *
    * @param string $tagType
    * @param array $meta
    */
   public function createTag($tagType, array $meta)
   {
      switch($tagType){
         case TagRefl::T_LABLE:
            TagRefl::createLabelTagSkeleton($meta);
            break;
         case TagRefl::T_DS:
            TagRefl::createDsTagSkeleton($meta);
            break;
      }
   }

   /**
    * 删除一个标签
    *
    * @param $tagType
    * @param $classify
    * @param $name
    */
   public function deleteTag($tagType, $classify, $name)
   {
      return TagRefl::deleteTag($tagType, $classify, $name);
   }

   /**
    * 更新一个标签的元信息
    *
    * @param string $tagType
    * @param string $sourceClassify
    * @param string $sourceTagName
    * @param array $meta
    */
   public function updateTagMeta($tagType, $sourceClassify, $sourceTagName, array $meta)
   {
      switch($tagType){
         case TagRefl::T_LABLE:
            TagRefl::updateLabelTagMeta($tagType, $sourceClassify, $sourceTagName, $meta);
            break;
         case TagRefl::T_DS:
            TagRefl::updateDsTagMeta($tagType, $sourceClassify, $sourceTagName, $meta);
            break;
      }
   }


   /**
    * 获取站点配置的标签列表
    *
    * @return array
    */
   public function getSiteCfgTags()
   {
      $path = CNTY_SYS_LIB_DIR . DS . 'Framework'. DS .'Qs'. DS .'Context'. DS .'Lib'. DS .'SiteConfig.php';
      $tags = array();
      if(!file_exists($path)){
         return $tags;
      }
      include $path;
      $sc = new SiteConfig();
      $methods = get_class_methods($sc);
      if(!empty($methods)){
         $tag['category'] = 'SiteConfig';
         foreach ($methods as $method){
            $tag['id'] = $method;
            $tags[$method] = $tag;
         }
      }
      return $tags;
   }

   /**
    * 获取系统标签的名称
    *
    * @return array
    */
   public function getSysTags()
   {
      $path = CNTY_SYS_LIB_DIR . DS . 'Framework'. DS  .'Qs'. DS .'Context'. DS .'Lib'. DS .'Sys.php';
      $tags = array();
      if(!file_exists($path)){
         return $tags;
      }
      include $path;
      $sys = new Sys();
      $methods = get_class_methods($sys);
      if(!empty($methods)){
         $tag['category'] = 'Sys';
         foreach ($methods as $method){
            $tag['id'] = $method;
            $tags[$method] = $tag;
         }
      }
      return $tags;
   }
}