<?php
return array(
   'id'          => '产品列表',
   'category'    => '供应商',
   'class'       => 'ProductList',
   'namespace'   => 'Provider',
   'static'      => false,
   'description' => '供应商自己的产品的列表',
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
