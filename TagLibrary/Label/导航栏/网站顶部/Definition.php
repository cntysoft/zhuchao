<?php
return array(
	'id'				 => '网站顶部',
	'category'		 => '导航栏',
	'static'			 => false,
	'description'	 => '网站顶部模板',
	'namespace'		 => 'Nav',
	'class'			 => 'Nav',
	'attributes'	 => array(
		'identifier' => array(
			'dataType'		 => \Cntysoft\STRING,
			'name'			 => '标识符',
			'require'		 => false,
			'description'	 => '节点标识符'
		),
		'type'		 => array(
			'dataType'		 => \Cntysoft\INTEGER,
			'name'			 => '类型',
			'require'		 => false,
			'description'	 => 'HTML类型'
		),
		'outputNum'	 => array(
			'dataType'		 => \Cntysoft\INTEGER,
			'name'			 => '数量',
			'require'		 => false,
			'description'	 => '输出数量'
		)
	)
);
