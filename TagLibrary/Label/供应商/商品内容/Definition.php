<?php
return array(
   'id'          => '商品内容',
   'category'    => '供应商',
   'class'       => 'ProductChange',
   'namespace'   => 'Provider',
   'static'      => false,
   'description' => '显示商品的内容',
   'attributes'  => array(
      'number' => array(
         'dataType' => \Cntysoft\STRING,
         'name'    =>  '商品编码',
         'require'     => false,
         'description' => '商品编码'
      )
   )
);
