<?php
return array(
   'id'          => '商品信息',
   'category' => '商品数据源',
   'class'       => 'Goods',
   'namespace'   => 'Goods',
   'description' => '商品模型数据源标签',
   'attributes'  => array(
      'itemId' => array(
        'dataType' => \Cntysoft\INTEGER,
        'name'   =>  '信息的ID',
        'require' => false,
        'description' => '指定数据源要查询的信息ID',
      )
   )
);
