<?php

return array(
	'id' => '帮助中心',
	'category' => '帮助中心',
	'static' => false,
	'description' => '帮助中心模板',
	'namespace' => 'HelpCenter',
	'class' => 'HelpCenter',
	'attributes'  => array(
      'identifier' => array(
         'dataType' => \Cntysoft\STRING,
         'name'    =>  '标识符',
         'require'     => false,
         'description' => '节点标识符'
      ),
		'type' => array(
         'dataType' => \Cntysoft\INTEGER,
         'name'    =>  '类型',
         'require'     => false,
         'description' => 'HTML类型'
      ),
		'outputNum' => array(
         'dataType' => \Cntysoft\INTEGER,
         'name'    =>  '数量',
         'require'     => false,
         'description' => '输出数量'
      )
   )
);
