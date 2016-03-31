<?php
return array(
	'id'				 => '商品类',
	'category'		 => '商品内容页',
	'static'			 => false,
	'description'	 => '商品内容页模板',
	'namespace'		 => 'Goods',
	'class'			 => 'Goods',
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
		),
		'floor'		 => array(
			'dataType'		 => \Cntysoft\INTEGER,
			'name'			 => '楼数',
			'require'		 => false,
			'description'	 => '输出数量'
		)
	)
);
