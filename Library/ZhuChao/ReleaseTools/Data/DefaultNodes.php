<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
return array(
   array(
      'text'              => '首页',
      'identifier'        => 'Index',
      'nodeType'          => 1,
      'showOnMenu'        => 1,
      'showOnListParent'  => 0,
      'coverTemplateFile' => '首页.phtml'
   ),
   array(
      'text'              => '建材老板内参',
      'identifier'        => 'laobanneican',
      'nodeType'          => 3,
      'showOnMenu'        => 1,
      'showOnListParent'  => 0,
      'listTemplateFile'  => '列表页模板/默认文章列表页模板.phtml',
      'coverTemplateFile' => '封面页模板/默认新闻封面页模板.phtml',
      'modelsTemplate'    => array(
         1 => '内容页模板/默认文章内容页模板.phtml'
      )
   ),
   array(
      'text'              => '电商宝典',
      'identifier'        => 'dianshangbaodian',
      'nodeType'          => 3,
      'showOnMenu'        => 1,
      'showOnListParent'  => 0,
      'listTemplateFile'  => '列表页模板/默认文章列表页模板.phtml',
      'coverTemplateFile' => '封面页模板/默认新闻封面页模板.phtml',
      'modelsTemplate'    => array(
         1 => '内容页模板/默认文章内容页模板.phtml'
      )
   ),
   array(
      'text'              => '帮助中心',
      'identifier'        => 'bangzhuzhongxin',
      'nodeType'          => 3,
      'showOnMenu'        => 1,
      'showOnListParent'  => 0,
      'listTemplateFile'  => '列表页模板/默认文章列表页模板.phtml',
      'coverTemplateFile' => '帮助中心/首页.phtml',
      'modelsTemplate'    => array(
         1 => '帮助中心/内容页.phtml'
      )
   ),
   array(
      'text'              => '关于我们',
      'identifier'        => 'guanyuwomen',
      'nodeType'          => 3,
      'showOnMenu'        => 1,
      'showOnListParent'  => 0,
      'listTemplateFile'  => '列表页模板/默认文章列表页模板.phtml',
      'coverTemplateFile' => '关于我们/首页.phtml',
      'modelsTemplate'    => array(
         1 => '关于我们/内容页.phtml'
      )
   )
);
