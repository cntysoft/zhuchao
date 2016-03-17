<?php
return array(
   'id'          => '老板内参',
   'category'    => '文章',
   'static'      => false,
   'namespace'   => 'Category',
   'class'       => 'NewsList',
   'description' => '获取自定义信息列表',
   'attributes'  => array(
      'nodeIdentifier' => array(
         'dataType'    => \Cntysoft\STRING,
         'name'        => '节点唯一标识',
         'require'     => false,
         'description' => '栏目identifier'
      ),
      'renderTpl'      => array(
         'dataType'    => \Cntysoft\STRING,
         'name'        => '调用的模板',
         'require'     => true,
         'default'     => 'Default',
         'description' => '添加标签使用的模板文件名称'
      ),
      'titleLength'    => array(
         'dataType'    => \Cntysoft\INTEGER,
         'name'        => '标题长度',
         'require'     => true,
         'default'     => 30,
         'description' => '输出标题的长度'
      ),
      'introLength'    => array(
         'dataType'    => \Cntysoft\INTEGER,
         'name'        => '简介长度',
         'require'     => false,
         'default'     => 100,
         'description' => '输出简介的长度'
      ),
      'enablePage'     => array(
         'dataType'    => \Cntysoft\BOOLEAN,
         'name'        => '是否启用分页',
         'require'     => true,
         'default'     => false,
         'description' => '是否启用分页， 如果不启用就可以使用 outputNum 参数指定输出的信息数量'
      ),
      'outputNum'      => array(
         'dataType'    => \Cntysoft\INTEGER,
         'name'        => '输出数量',
         'require'     => true,
         'default'     => 3,
         'description' => '没有开启分页则输出指定数量的信息，开启分页且不为0则为分页信息数量'
      ),
      'modelId'        => array(
         'dataType'    => \Cntysoft\INTEGER,
         'name'        => '模型ID',
         'require'     => true,
         'default'     => 0,
         'description' => '查询的模型类型，这个模型ID需要自己看数据库'
      ),
      'listSubNode'    => array(
         'dataType'    => \Cntysoft\BOOLEAN,
         'name'        => '是否查询子栏目',
         'require'     => true,
         'default'     => true,
         'description' => '查询列表的时候，是否也查询指定节点的子节点'
      )
   )
);
