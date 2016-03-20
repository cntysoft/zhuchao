<?php
return array(
   'id'          => '关注企业列表',
   'category'    => '采购商',
   'class'       => 'Follow',
   'namespace'   => 'Buyer',
   'static'      => false,
   'description' => '采购商关注企业的列表',
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
