<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
return array(
   'Article' => array(
      'meta' => array(
         'key' => 'Article',
         'name' => '文章模型',
         'buildIn' => true,
         'editor' => 'ArticleEditor',
         'dataSaver' => 'StdSaver',
         'description' => '系统默认文章内容模型，这个模型是CMS的基础模型',
         'itemName' => '文章',
         'itemUnit' => '篇',
         'enabled' => true,
         'defaultTemplateFile' => '内容页模板/默认文章内容页模板.phtml',
         'extraConfig' =>
         array()
      ),
      'fields' => array(
         array(
            'name' => 'nodeId',
            'alias' => '所属节点',
            'fieldType' => 'Category',
            'require' => true,
            'system' => true,
            'type' => 'integer'
         ),
         array(
            'name' => 'title',
            'alias' => '信息标题',
            'fieldType' => 'Title',
            'require' => true,
            'system' => true,
            'type' => 'varchar',
            'length' => 512
         ),
         array(
            'name' => 'keywords',
            'alias' => '关键字',
            'fieldType' => 'Keywords',
            'require' => false,
            'system' => false,
            'type' => 'varchar',
            'length' => 255
         ),
         array(
            'name' => 'copyFrom',
            'alias' => '信息来源',
            'fieldType' => 'DictSelection',
            'require' => false,
            'system' => false,
            'type' => 'varchar',
            'length' => 32,
            'uiOption' => array(
               'kvDictKey' => 'Cms.ContentModelManager.Source',
               'btnText' => '选择来源',
               'textWidth' => '150',
               'multiSelect' => false
            )
         ),
         array(
            'name' => 'intro',
            'alias' => '信息简介',
            'fieldType' => 'MultiLineText',
            'require' => false,
            'system' => true,
            'type' => 'varchar',
            'length' => 512,
            'uiOption' => array(
               'enableLenCheck' => true,
               'maxLen' => 512
            )
         ),
         array(
            'name' => 'defaultPicUrl',
            'alias' => '默认封面图片',
            'fieldType' => 'CoverImage',
            'require' => false,
            'system' => true,
            'type' => 'varchar',
            'length' => 512,
            'uiOption' => array()
         ),
         array(
            'name' => 'content',
            'alias' => '文章内容',
            'fieldType' => 'WordEditor',
            'require' => true,
            'system' => false,
            'type' => 'text',
         ),
         array(
            'name' => 'infoGrade',
            'alias' => '信息评分',
            'fieldType' => 'Selection',
            'require' => true,
            'system' => true,
            'defaultValue' => '无',
            'type' => 'char',
            'length' => 5,
            'uiOption' => array(
               'denyChangeItemNum' => true,
               'selectionType' => 1,
               'name' => '★',
               'value' => '★★★★★',
               'items' => array(0 => '无|无', 1 => '★|★', 2 => '★★|★★', 3 => '★★★|★★★', 4 => '★★★★|★★★★', 5 => '★★★★★|★★★★★')
            )
         ),
         array(
            'name' => 'status',
            'alias' => '状态',
            'fieldType' => 'Status',
            'require' => true,
            'system' => true,
            'type' => 'boolean'
         ),
         array(
            'name' => 'imgRefMap',
            'alias' => '文件引用映射',
            'fieldType' => 'MultiLineText',
            'require' => false,
            'system' => false,
            'display' => false,
            'type' => 'text'
         ),
         array(
            'name' => 'fileRefs',
            'alias' => '文件引用',
            'fieldType' => 'SingleLineText',
            'require' => false,
            'system' => false,
            'display' => false,
            'type' => 'varchar',
            'length' => 512
         )
      )
   ),
   'Image' => array(
      'meta' => array(
         'key' => 'Image',
         'name' => '图片模型',
         'buildIn' => true,
         'editor' => 'StdEditor',
         'dataSaver' => 'StdSaver',
         'description' => '系统图片模型，用这个模型可以显示一些图片类型的信息',
         'itemName' => '图片',
         'itemUnit' => '张',
         'enabled' => true,
         'defaultTemplateFile' => '内容页模板/默认图片内容页模板.phtml',
         'extraConfig' =>
         array()
      ),
      'fields' => array(
         array(
            'name' => 'nodeId',
            'alias' => '所属节点',
            'fieldType' => 'Category',
            'require' => true,
            'system' => true,
            'type' => 'integer'
         ),
         array(
            'name' => 'title',
            'alias' => '图集名称',
            'fieldType' => 'Title',
            'require' => true,
            'system' => true,
            'type' => 'varchar',
            'length' => 512
         ),
         array(
            'name' => 'keywords',
            'alias' => '关键字',
            'fieldType' => 'Keywords',
            'require' => false,
            'system' => false,
            'type' => 'varchar',
            'length' => 255
         ),
         array(
            'name' => 'copyFrom',
            'alias' => '图集来源',
            'fieldType' => 'DictSelection',
            'require' => false,
            'system' => false,
            'type' => 'varchar',
            'length' => 255,
            'uiOption' => array(
               'kvDictKey' => 'Cms.ContentModelManager.Source',
               'btnText' => '图集来源',
               'textWidth' => '150',
               'multiSelect' => false
            )
         ),
         array(
            'name' => 'intro',
            'alias' => '图集简介',
            'fieldType' => 'MultiLineText',
            'require' => false,
            'system' => true,
            'type' => 'varchar',
            'length' => 512,
            'uiOption' => array(
               'enableLenCheck' => true,
               'maxLen' => 512
            )
         ),
         array(
            'name' => 'images',
            'alias' => '组图',
            'fieldType' => 'ImageGroup',
            'require' => true,
            'system' => false,
            'type' => 'text',
            'needSerialize' => true
         ),
         array(
            'name' => 'infoGrade',
            'alias' => '图集评分',
            'fieldType' => 'Selection',
            'require' => true,
            'system' => true,
            'defaultValue' => '无',
            'type' => 'char',
            'length' => 5,
            'uiOption' => array(
               'denyChangeItemNum' => true,
               'selectionType' => 1,
               'name' => '★',
               'value' => '★★★★★',
               'items' => array(0 => '无|无', 1 => '★|★', 2 => '★★|★★', 3 => '★★★|★★★', 4 => '★★★★|★★★★', 5 => '★★★★★|★★★★★')
            )
         ),
         array(
            'name' => 'status',
            'alias' => '状态',
            'fieldType' => 'Status',
            'require' => true,
            'system' => true,
            'type' => 'boolean'
         ),
         array(
            'name' => 'fileRefs',
            'alias' => '文件引用',
            'fieldType' => 'SingleLineText',
            'require' => false,
            'system' => false,
            'display' => false,
            'type' => 'varchar',
            'length' => 512
         )
      )
   ),
   'Announce' => array(
      'meta' => array(
         'key' => 'Announce',
         'name' => '公告模型',
         'buildIn' => true,
         'editor' => 'AnnounceEditor',
         'dataSaver' => 'StdSaver',
         'description' => '系统默认公告模型，这个是一个轻量级的文章模型，用来推送公告信息',
         'itemName' => '公告',
         'itemUnit' => '条',
         'enabled' => true,
         'defaultTemplateFile' => '内容页模板/默认公告内容页模板.phtml',
         'extraConfig' =>
         array()
      ),
      'fields' => array(
         array(
            'name' => 'nodeId',
            'alias' => '所属节点',
            'fieldType' => 'Category',
            'require' => true,
            'system' => true,
            'type' => 'integer'
         ),
         array(
            'name' => 'title',
            'alias' => '公告标题',
            'fieldType' => 'Title',
            'require' => true,
            'system' => true,
            'type' => 'varchar',
            'length' => 512,
         ),
         array(
            'name' => 'keywords',
            'alias' => '关键字',
            'fieldType' => 'Keywords',
            'require' => false,
            'system' => true,
            'display' => false,
            'type' => 'varchar',
            'length' => 255
         ),
         array(
            'name' => 'intro',
            'alias' => '公告简介',
            'fieldType' => 'MultiLineText',
            'require' => false,
            'system' => true,
            'display' => false,
            'type' => 'varchar',
            'length' => 512,
            'uiOption' => array(
               'enableLenCheck' => true,
               'maxLen' => 512
            )
         ),
         array(
            'name' => 'content',
            'alias' => '公告内容',
            'fieldType' => 'WordEditor',
            'require' => true,
            'system' => false,
            'type' => 'text'
         ),
         array(
            'name' => 'validDays',
            'alias' => '有效天数',
            'fieldType' => 'Number',
            'require' => true,
            'system' => false,
            'defaultValue' => 0,
            'length' => 11,
            'type' => 'integer'
         ),
         array(
            'name' => 'inputTime',
            'alias' => '发布日期',
            'fieldType' => 'Date',
            'require' => true,
            'system' => true,
            'type' => 'date'
         ),
         array(
            'name' => 'isLatest',
            'alias' => '是否最新',
            'fieldType' => 'Checkbox',
            'require' => true,
            'system' => false,
            'type' => 'boolean'
         ),
         array(
            'name' => 'infoGrade',
            'alias' => '信息评分',
            'fieldType' => 'Selection',
            'require' => true,
            'system' => true,
            'defaultValue' => '无',
            'type' => 'char',
            'length' => 5,
            'uiOption' => array(
               'denyChangeItemNum' => true,
               'selectionType' => 1,
               'name' => '★',
               'value' => '★★★★★',
               'items' => array(0 => '无|无', 1 => '★|★', 2 => '★★|★★', 3 => '★★★|★★★', 4 => '★★★★|★★★★', 5 => '★★★★★|★★★★★')
            )
         ),
         array(
            'name' => 'status',
            'alias' => '状态',
            'fieldType' => 'Status',
            'require' => true,
            'system' => true,
            'type' => 'boolean'
         ),
         array(
            'name' => 'imgRefMap',
            'alias' => '文件引用映射',
            'fieldType' => 'MultiLineText',
            'require' => false,
            'system' => false,
            'display' => false,
            'type' => 'text'
         ),
         array(
            'name' => 'fileRefs',
            'alias' => '文件引用',
            'fieldType' => 'SingleLineText',
            'require' => false,
            'system' => false,
            'display' => false,
            'type' => 'varchar',
            'length' => 512
         )
      )
   ),
   'FriendLink' => array(
      'meta' => array(
         'key' => 'FriendLink',
         'name' => '友情链接模型',
         'buildIn' => true,
         'editor' => 'StdEditor',
         'dataSaver' => 'StdSaver',
         'description' => '这个模型实现了网站底部的友情链接功能',
         'itemName' => '友情链接',
         'itemUnit' => '个',
         'enabled' => true,
         'defaultTemplateFile' => '内容页模板/默认友情链接内容页模板.phtml',
         'extraConfig' =>
         array()
      ),
      'fields' => array(
         array(
            'name' => 'nodeId',
            'alias' => '所属节点',
            'fieldType' => 'Category',
            'require' => true,
            'system' => true,
            'type' => 'integer'
         ),
         array(
            'name' => 'title',
            'alias' => '友情链接名称',
            'fieldType' => 'Title',
            'require' => true,
            'system' => true,
            'type' => 'varchar',
            'length' => 512
         ),
         array(
            'name' => 'intro',
            'alias' => '链接简介',
            'fieldType' => 'MultiLineText',
            'require' => false,
            'system' => true,
            'type' => 'varchar',
            'length' => 512,
            'uiOption' => array(
               'enableLenCheck' => true,
               'maxLen' => 512
            )
         ),
         array(
            'name' => 'linkType',
            'alias' => '链接类型',
            'fieldType' => 'Selection',
            'require' => true,
            'system' => false,
            'length' => 32,
            'type' => 'varchar',
            'defaultValue' => 1,
            'uiOption' => array(
               'selectionType' => 1,
               'denyChangeItemNum' => true,
               'items' => array(0 => '文字类型|1', 1 => '图片类型|2')
            )
         ),
         array(
            'name' => 'defaultPicUrl',
            'alias' => 'Logo图片',
            'fieldType' => 'CoverImage',
            'require' => false,
            'system' => true,
            'type' => 'varchar',
            'length' => 255
         ),
         array(
            'name' => 'isRecommend',
            'alias' => '是否推荐',
            'fieldType' => 'Checkbox',
            'require' => true,
            'system' => false,
            'type' => 'boolean'
         ),
         array(
            'name' => 'linkUrl',
            'alias' => '链接地址',
            'fieldType' => 'SingleLineText',
            'require' => true,
            'system' => false,
            'type' => 'varchar',
            'length' => 255
         ),
         array(
            'name' => 'infoGrade',
            'alias' => '推荐级别',
            'fieldType' => 'Selection',
            'require' => true,
            'system' => true,
            'defaultValue' => '无',
            'type' => 'char',
            'length' => 5,
            'uiOption' => array(
               'denyChangeItemNum' => true,
               'selectionType' => 1,
               'name' => '★',
               'value' => '★★★★★',
               'items' => array(0 => '无|无', 1 => '★|★', 2 => '★★|★★', 3 => '★★★|★★★', 4 => '★★★★|★★★★', 5 => '★★★★★|★★★★★')
            )
         ),
         array(
            'name' => 'status',
            'alias' => '状态',
            'fieldType' => 'Status',
            'require' => true,
            'system' => true,
            'type' => 'boolean'
         )
      )
   )
);
