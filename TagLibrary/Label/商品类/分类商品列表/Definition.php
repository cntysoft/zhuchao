<?php
return array(
   'id'          => '商品内容页',
   'category'    => '商品类',
   'class'       => 'ProductClassify',
   'namespace'   => 'Goods',
   'static'      => false,
   'description' => '分类商品列表页',
   'attributes'  => array(
      'outputNum' => array(
         'dataType'    => \Cntysoft\INTEGER,
         'name'        => '输出数量',
         'require'     => false,
         'description' => '输出数量'
      )
   ),
   'enablePage'  => array(
      'dataType'    => \Cntysoft\BOOLEAN,
      'name'        => '是否启用分页',
      'require'     => true,
      'default'     => false,
      'description' => '是否启用分页， 如果不启用就可以使用 outputNum 参数指定输出的信息数量'
   ),
);
