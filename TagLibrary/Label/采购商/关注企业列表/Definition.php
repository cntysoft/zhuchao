<?php
return array(
   'id'          => '收藏产品列表',
   'category'    => '采购商',
   'class'       => 'Collection',
   'namespace'   => 'Buyer',
   'static'      => false,
   'description' => '采购商收藏产品的列表',
   'attributes'  => array(
      'outputNum' => array(
         'dataType' => \Cntysoft\INTEGER,
         'name'    =>  '数量',
         'require'     => false,
         'description' => '输出数量'
      ),
      'enablePage' => array(
         'dataType' => \Cntysoft\BOOLEAN,
         'name'    =>  '是否分页',
         'require'     => false,
         'description' => '是否分页'
      )
   )
);
