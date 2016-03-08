<?php

/**
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
return array(
    array(
        'text' => '首页',
        'identifier' => 'Index',
        'nodeType' => 1,
        'showOnMenu' => 1,
        'showOnListParent' => 0,
        'coverTemplateFile' => '首页.phtml'
    ),
    array(
        'text' => '友情链接',
        'identifier' => 'YouQingLianJie',
        'nodeType' => 3,
        'listTemplateFile' => '列表页模板/默认友情链接列表页模板.phtml',
        'showOnMenu' => 0,
        'showOnListParent' => 1,
        'modelsTemplate' => array(
            5 => '内容页模板/默认友情链接内容页模板.phtml'
        )
    ),
    array(
        'text' => '关于我们',
        'identifier' => 'guanyuwomen',
        'nodeType' => 1,
        'showOnMenu' => 0,
        'showOnListParent' => 1,
        'listTemplateFile' => '关于我们/关于我们.phtml',
    )
);
