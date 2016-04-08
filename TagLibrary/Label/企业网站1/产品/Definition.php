<?php
return array(
   'id'          => '产品',
   'category'    => '企业网站',
   'class'       => 'Product',
   'namespace'   => 'Company',
   'static'      => false,
   'description' => '企业产品相关',
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
