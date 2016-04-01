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
      'meta'   => array(
         'key'                 => 'Article',
         'name'                => '文章模型',
         'buildIn'             => true,
         'editor'              => 'ArticleEditor',
         'dataSaver'           => 'StdSaver',
         'description'         => '系统默认文章内容模型，这个模型是CMS的基础模型',
         'itemName'            => '文章',
         'itemUnit'            => '篇',
         'enabled'             => true,
         'defaultTemplateFile' => '新闻内容页.phtml',
         'extraConfig'         =>
         array()
      ),
      'fields' => array(
         array(
            'name'      => 'nodeId',
            'alias'     => '所属节点',
            'fieldType' => 'Category',
            'require'   => true,
            'system'    => true,
            'type'      => 'integer'
         ),
         array(
            'name'      => 'title',
            'alias'     => '信息标题',
            'fieldType' => 'Title',
            'require'   => true,
            'system'    => true,
            'type'      => 'varchar',
            'length'    => 512
         ),
         array(
            'name'      => 'intro',
            'alias'     => '信息简介',
            'fieldType' => 'MultiLineText',
            'require'   => false,
            'system'    => true,
            'type'      => 'varchar',
            'length'    => 512,
            'uiOption'  => array(
               'enableLenCheck' => true,
               'maxLen'         => 512
            )
         ),
         array(
            'name'      => 'defaultPicUrl',
            'alias'     => '默认封面图片',
            'fieldType' => 'CoverImage',
            'require'   => false,
            'system'    => true,
            'type'      => 'varchar',
            'length'    => 512,
            'uiOption'  => array()
         ),
         array(
            'name'      => 'content',
            'alias'     => '文章内容',
            'fieldType' => 'WordEditor',
            'require'   => true,
            'system'    => false,
            'type'      => 'text',
         ),
         array(
            'name'      => 'status',
            'alias'     => '状态',
            'fieldType' => 'Status',
            'require'   => true,
            'system'    => true,
            'type'      => 'boolean'
         ),
         array(
            'name'      => 'imgRefMap',
            'alias'     => '文件引用映射',
            'fieldType' => 'MultiLineText',
            'require'   => false,
            'system'    => false,
            'display'   => false,
            'type'      => 'text'
         ),
         array(
            'name'      => 'fileRefs',
            'alias'     => '文件引用',
            'fieldType' => 'SingleLineText',
            'require'   => false,
            'system'    => false,
            'display'   => false,
            'type'      => 'varchar',
            'length'    => 512
         )
      )
   ),
   'Job'     => array(
      'meta'   => array(
         'key'                 => 'Job',
         'name'                => '招聘模型',
         'buildIn'             => true,
         'editor'              => 'StdEditor',
         'dataSaver'           => 'StdSaver',
         'description'         => '系统的招聘信息模型',
         'itemName'            => '信息',
         'itemUnit'            => '篇',
         'enabled'             => true,
         'defaultTemplateFile' => '招聘信息内容页模板.phtml',
         'extraConfig'         =>
         array()
      ),
      'fields' => array(
         array(
            'name'      => 'nodeId',
            'alias'     => '所属节点',
            'fieldType' => 'Category',
            'require'   => true,
            'system'    => true,
            'type'      => 'integer'
         ),
         array(
            'name'      => 'title',
            'alias'     => '职位名称',
            'fieldType' => 'Title',
            'require'   => true,
            'system'    => true,
            'type'      => 'varchar',
            'length'    => 512
         ),
         array(
            'name'      => 'intro',
            'alias'     => '职位简介',
            'fieldType' => 'MultiLineText',
            'require'   => false,
            'system'    => true,
            'type'      => 'varchar',
            'length'    => 512,
            'uiOption'  => array(
               'enableLenCheck' => true,
               'maxLen'         => 512
            )
         ),
         array(
            'name'      => 'content',
            'alias'     => '职位内容',
            'fieldType' => 'WordEditor',
            'require'   => true,
            'system'    => false,
            'type'      => 'text',
         ),
         array(
            'name'      => 'status',
            'alias'     => '状态',
            'fieldType' => 'Status',
            'require'   => true,
            'system'    => true,
            'type'      => 'boolean'
         ),
         array(
            'name'      => 'department',
            'alias'     => '招聘部门',
            'fieldType' => 'SingleLineText',
            'require'   => true,
            'system'    => false,
            'display'   => true,
            'type'      => 'varchar',
            'length'    => 128
         ),
         array(
            'name'      => 'number',
            'alias'     => '招聘人数',
            'fieldType' => 'Number',
            'require'   => true,
            'system'    => false,
            'display'   => true,
            'type'      => 'integer',
            'length'    => 10
         ),
         array(
            'name'      => 'tel',
            'alias'     => '招聘电话',
            'fieldType' => 'SingleLineText',
            'require'   => true,
            'system'    => false,
            'display'   => true,
            'type'      => 'varchar',
            'length'    => 64
         ),
         array(
            'name'      => 'endTime',
            'alias'     => '截止时间',
            'fieldType' => 'SingleLineText',
            'require'   => true,
            'system'    => false,
            'display'   => true,
            'type'      => 'integer',
            'length'    => 10
         ),
      )
   ),
   'CaseInfo'    => array(
      'meta'   => array(
         'key'                 => 'CaseInfo',
         'name'                => '案例模型',
         'buildIn'             => true,
         'editor'              => 'StdEditor',
         'dataSaver'           => 'StdSaver',
         'description'         => '系统的案例信息模型',
         'itemName'            => '信息',
         'itemUnit'            => '篇',
         'enabled'             => true,
         'defaultTemplateFile' => '案例内容页模板.phtml',
         'extraConfig'         =>
         array()
      ),
      'fields' => array(
         array(
            'name'      => 'nodeId',
            'alias'     => '所属节点',
            'fieldType' => 'Category',
            'require'   => true,
            'system'    => true,
            'type'      => 'integer'
         ),
         array(
            'name'      => 'title',
            'alias'     => '案例名称',
            'fieldType' => 'Title',
            'require'   => true,
            'system'    => true,
            'type'      => 'varchar',
            'length'    => 512
         ),
         array(
            'name'      => 'intro',
            'alias'     => '案例简介',
            'fieldType' => 'MultiLineText',
            'require'   => false,
            'system'    => true,
            'type'      => 'varchar',
            'length'    => 512,
            'uiOption'  => array(
               'enableLenCheck' => true,
               'maxLen'         => 512
            )
         ),
         array(
            'name'      => 'content',
            'alias'     => '案例内容',
            'fieldType' => 'WordEditor',
            'require'   => true,
            'system'    => false,
            'type'      => 'text',
         ),
         array(
            'name'      => 'status',
            'alias'     => '状态',
            'fieldType' => 'Status',
            'require'   => true,
            'system'    => true,
            'type'      => 'boolean'
         ),
         array(
            'name'      => 'fileRefs',
            'alias'     => '文件引用',
            'fieldType' => 'SingleLineText',
            'require'   => false,
            'system'    => false,
            'display'   => false,
            'type'      => 'varchar',
            'length'    => 512
         )
      )
   )
);
