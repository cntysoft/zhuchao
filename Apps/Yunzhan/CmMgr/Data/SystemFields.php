<?php
use Phalcon\Db\Column;
/**
 * 系统默认必须有的字段
 */
return array(
   array(
      'name'      => 'nodeId',
      'alias'     => '所属节点',
      'fieldType' => 'Category',
      'require'   => true,
      'system'    => true,
      'type' => Column::TYPE_INTEGER
   ),
   array(
      'name'      => 'title',
      'alias'     => '标题',
      'fieldType' => 'Title',
      'require'   => true,
      'system'    => true,
      'type' => Column::TYPE_VARCHAR,
      'length' => 255
   ),
   array(
      'name'      => 'intro',
      'alias'     => '信息简介',
      'fieldType' => 'MultiLineText',
      'require'   => false,
      'system'    => true,
      'type' => Column::TYPE_VARCHAR,
      'length' => 255
   ),
   array(
      'name'      => 'status',
      'alias'     => '状态',
      'fieldType' => 'Status',
      'require'   => true,
      'system'    => true,
      'type' => Column::TYPE_INTEGER
   )
);