<?php
return array(
   'id'          => '产品分类',
   'category'    => '供应商',
   'class'       => 'Category',
   'namespace'   => 'Provider',
   'static'      => false,
   'description' => '添加产品时显示的产品分类',
   'attributes'  => array(
      'categoryId' => array(
         'dataType' => \Cntysoft\INTEGER,
         'name'    =>  '分类的id',
         'require'     => false,
         'description' => '分类的id'
      )
   )
);
