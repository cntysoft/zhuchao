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
      'identifier'        => 'index',
      'nodeType'          => 1,
      'showOnMenu'        => 1,
      'showOnListParent'  => 0,
      'coverTemplateFile' => '首页.phtml'
   ),
   array(
      'text'              => '筑巢商学院',
      'identifier'        => 'zhuchaoschool',
      'nodeType'          => 3,
      'showOnMenu'        => 1,
      'showOnListParent'  => 0,
      'listTemplateFile'  => '电商宝典/列表页.phtml',
      'coverTemplateFile' => '电商宝典/首页.phtml',
      'modelsTemplate'    => array(
         1 => '电商宝典/内容页.phtml'
      )
   ),
   array(
      'text'              => '老板内参',
      'identifier'        => 'laobanneican',
      'nodeType'          => 3,
      'showOnMenu'        => 1,
      'showOnListParent'  => 0,
      'listTemplateFile'  => '老板内参/列表页.phtml',
      'coverTemplateFile' => '老板内参/首页.phtml',
      'modelsTemplate'    => array(
         1 => '老板内参/内容页.phtml'
      )
   ),
   array(
      'text'              => '帮助中心',
      'identifier'        => 'help',
      'nodeType'          => 3,
      'showOnMenu'        => 1,
      'showOnListParent'  => 0,
      'listTemplateFile'  => '帮助中心/列表页.phtml',
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
      'listTemplateFile'  => '关于我们.phtml',
      'coverTemplateFile' => '关于我们.phtml',
      'modelsTemplate'    => array(
         1 => '关于我们.phtml'
      )
   )
);
