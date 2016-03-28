<?php
return array(
   'id'          => '栏目SEO',
   'category' => '栏目数据源',
   'class'       => 'Category',
   'namespace'   => 'Category',
   'description' => '栏目数据源标签',
   'attributes'  => array(
      'identifier' => array(
        'dataType' => \Cntysoft\INTEGER,
        'name'   =>  '信息的ID',
        'require' => false,
        'description' => '指定数据源要查询的信息ID',
      )
   )
);
