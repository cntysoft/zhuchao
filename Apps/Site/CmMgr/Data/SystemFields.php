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
      'name'      => 'titleFontColor',
      'alias'     => '标题颜色',
      'fieldType' => 'Color',
      'require'   => false,
      'system'    => true,
      'type' => Column::TYPE_CHAR,
      'length' => 8,
      'uiOption'  => array(
         'length' => 8,
         'type'   => 'string'
      )
   ),
   array(
      'name'         => 'titleFontStyle',
      'alias'        => '标题样式',
      'fieldType'    => 'Selection',
      'require'      => false,
      'system'       => true,
      'defaultValue' => 1,
      'type' => Column::TYPE_VARCHAR,
      'length' => 64,
      'uiOption'     => array(
         'type'              => 'string',
         'length'            => 64,
         'denyChangeItemNum' => true,
         'selectionType'     => 1,
         'items'             => array(0 => '常规|1', 1 => '粗体|2', 2 => '斜体|3', 3 => '粗斜|4',)
      )
   ),
   array(
      'name'      => 'author',
      'alias'     => '作者',
      'fieldType' => 'DictSelection',
      'require'   => false,
      'system'    => true,
      'type' => Column::TYPE_VARCHAR,
      'length' => 64,
      'uiOption'  => array(
         'kvDictKey'   => 'Site.Content.Author',
         'btnText'     => '选择作者',
         'textWidth'   => '150',
         'multiSelect' => false
      )
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
      'name'         => 'infoGrade',
      'alias'        => '信息评分',
      'fieldType'    => 'Selection',
      'require'      => true,
      'system'       => true,
      'defaultValue' => '无',
      'type' => Column::TYPE_CHAR,
      'length' => 5,
      'uiOption'     => array(
         'denyChangeItemNum' => true,
         'selectionType'     => 1,
         'type'              => 'string',
         'length'            => 5,
         'name'              => '★',
         'value'             => '★★★★★',
         'items'             => array(0 => '无|无', 1 => '★|★', 2 => '★★|★★', 3 => '★★★|★★★', 4 => '★★★★|★★★★', 5 => '★★★★★|★★★★★')
      )
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