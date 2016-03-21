<?php
return array(
   'id'          => '商城数据',
   'category' => '商城数据源',
   'class'       => 'Sites',
   'namespace'   => 'Sites',
   'description' => '商城数据源标签',
   'attributes'  => array(
      'itemId' => array(
        'dataType' => \Cntysoft\INTEGER,
        'name'   =>  '信息的ID',
        'require' => false,
        'description' => '指定数据源要查询的信息ID',
      )
   )
);
