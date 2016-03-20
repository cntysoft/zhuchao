<?php
return array(
   'id'          => '报价单列表',
   'category'    => '采购商',
   'class'       => 'QuotationList',
   'namespace'   => 'Buyer',
   'static'      => false,
   'description' => '采购商报价单的列表',
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
