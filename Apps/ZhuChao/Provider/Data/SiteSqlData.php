<?php
return array(
   //栏目表
   "CREATE TABLE IF NOT EXISTS `app_site_category_tree` (
`id` int(10) unsigned NOT NULL COMMENT '节点的ID',
 `pid` int(10) unsigned NOT NULL COMMENT '栏目父节点ID',
 `text` varchar(255) NOT NULL COMMENT '节点的名称',
 `nodeIdentifier` varchar(128) NOT NULL COMMENT '节点标识符',
 `dirname` varchar(128) DEFAULT NULL COMMENT '站点栏目的名称',
 `priority` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '栏目优先级',
 `createDate` int(10) unsigned NOT NULL COMMENT '栏目创建日期',
 `nodeType` tinyint(3) unsigned NOT NULL COMMENT '栏目类型',
 `openType` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '栏目节点打开类型',
 `showOnMenu` tinyint(1) NOT NULL DEFAULT '1' COMMENT '实现在首页顶部菜单显示',
 `showOnListParent` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否在父节点处显示出来，这个属性控制导航栏很重要',
 `listTemplateFile` varchar(512) DEFAULT NULL COMMENT '列表页模板',
 `coverTemplateFile` varchar(512) DEFAULT NULL COMMENT '封面页模板',
 `itemOpenType` tinyint(4) DEFAULT '1' COMMENT '该节点下内容页打开方式',
 `linkUrl` varchar(255) DEFAULT NULL COMMENT '外部链接栏目的链接地址',
 `description` varchar(255) DEFAULT NULL COMMENT '栏目的简单描述',
 `metaKeywords` varchar(512) DEFAULT NULL COMMENT '页面关键字',
 `metaDescription` varchar(512) DEFAULT NULL COMMENT '栏目简单描述'
) ENGINE = InnoDB AUTO_INCREMENT = 6 DEFAULT CHARSET = utf8 COMMENT = '网站节点表';",
   //插入栏目数据
   "INSERT INTO `app_site_category_tree` (`id`, `pid`, `text`, `nodeIdentifier`, `dirname`, `priority`, `createDate`, `nodeType`, `openType`, `showOnMenu`, `showOnListParent`, `listTemplateFile`, `coverTemplateFile`, `itemOpenType`, `linkUrl`, `description`, `metaKeywords`, `metaDescription`) VALUES
(1, 0, '新闻中心', 'newscenter', 'newscenter', 0, 1458460624, 3, 1, 1, 0, '企业网站/新闻列表.phtml', '企业网站/新闻列表.phtml', 1, NULL, NULL, NULL, NULL),
 (2, 1, '公司新闻', 'companynews', 'companynews', 0, 1458460624, 3, 1, 0, 0, '企业网站/新闻列表.phtml', '企业网站/新闻列表.phtml', 1, NULL, NULL, NULL, NULL),
 (3, 1, '行业新闻', 'industrynews', 'industrynews', 1, 1458460625, 3, 1, 0, 0, '企业网站/新闻列表.phtml', '企业网站/新闻列表.phtml', 1, NULL, NULL, NULL, NULL),
 (4, 0, '关于我们', 'about', 'about', 1, 1458460625, 3, 1, 0, 0, '企业网站/新闻列表.phtml', '企业网站/新闻列表.phtml', 1, NULL, NULL, NULL, NULL),
 (5, 0, '加入我们', 'joinus', 'joinus', 1, 1458460625, 3, 1, 1, 0, '企业网站/招聘列表.phtml', '企业网站/招聘列表.phtml', 1, NULL, NULL, NULL, NULL),
 (6, 0, '成功案例', 'case', 'case', 1, 1458460625, 3, 1, 0, 0, '企业网站/案例列表.phtml', '企业网站/案例列表.phtml', 1, NULL, NULL, NULL, NULL);",
   //内容模型表
   "CREATE TABLE IF NOT EXISTS `app_site_cmmgr_cmodel` (
`id` tinyint(10) unsigned NOT NULL COMMENT '内容模型id',
 `key` varchar(64) NOT NULL COMMENT '模型描述KEY',
 `buildIn` tinyint(1) NOT NULL COMMENT '是否内置',
 `name` varchar(128) NOT NULL COMMENT '内容模型名称',
 `description` varchar(512) DEFAULT NULL COMMENT '内容模型描述信息',
 `itemName` varchar(64) NOT NULL COMMENT '内容模型数据项名称',
 `icon` varchar(255) DEFAULT NULL COMMENT '模型小图标',
 `itemUnit` varchar(64) NOT NULL COMMENT '内容模型单位名称',
 `enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否开启',
 `defaultTemplateFile` varchar(255) NOT NULL COMMENT '默认内容模型模板',
 `editor` varchar(255) DEFAULT NULL COMMENT '内容编辑器',
 `dataSaver` varchar(128) NOT NULL COMMENT '数据保存器',
 `extraConfig` varchar(512) DEFAULT NULL COMMENT '模型的额外配置'
) ENGINE = InnoDB AUTO_INCREMENT = 3 DEFAULT CHARSET = utf8 COMMENT = '系统的内容模型';",
   //内容模型数据
   "INSERT INTO `app_site_cmmgr_cmodel` (`id`, `key`, `buildIn`, `name`, `description`, `itemName`, `icon`, `itemUnit`, `enabled`, `defaultTemplateFile`, `editor`, `dataSaver`, `extraConfig`) VALUES
(1, 'Article', 1, '文章模型', '系统默认文章内容模型，这个模型是CMS的基础模型', '文章', NULL, '篇', 1, '新闻内容页.phtml', 'ArticleEditor', 'StdSaver', 'a:0:{}'),
 (2, 'Job', 1, '招聘模型', '系统的招聘信息模型', '信息', NULL, '篇', 1, '招聘信息内容页模板.phtml', 'StdEditor', 'StdSaver', 'a:0:{}'),
 (3, 'CaseInfo', 1, '案例模型', '系统的案例信息模型', '信息', NULL, '篇', 1, '案例内容页模板.phtml', 'StdEditor', 'StdSaver', 'a:0:{}');",
   //内容模型字段表
   "CREATE TABLE IF NOT EXISTS `app_site_cmmgr_cmodel_fields` (
`id` int(10) unsigned NOT NULL,
 `mid` mediumint(8) unsigned NOT NULL COMMENT '模型ID',
 `tip` varchar(255) DEFAULT NULL COMMENT '模型提示',
 `description` varchar(255) DEFAULT NULL COMMENT '模型的简单描述',
 `system` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为系统字段',
 `virtual` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为虚拟字段',
 `name` varchar(255) NOT NULL COMMENT '字段的名称',
 `alias` varchar(255) NOT NULL COMMENT '字段别名',
 `fieldType` varchar(255) NOT NULL COMMENT '字段的类型',
 `require` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否必要',
 `display` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
 `defaultValue` text COMMENT '默认值',
 `priority` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '优先级',
 `uiOption` text COMMENT '字段UI配置',
 `type` int(10) unsigned NOT NULL COMMENT '字段类型'
) ENGINE = InnoDB AUTO_INCREMENT = 18 DEFAULT CHARSET = utf8 COMMENT = '内容模型的字段数据表';",
   //内容模型字段数据
   "INSERT INTO `app_site_cmmgr_cmodel_fields` (`id`, `mid`, `tip`, `description`, `system`, `virtual`, `name`, `alias`, `fieldType`, `require`, `display`, `defaultValue`, `priority`, `uiOption`, `type`) VALUES
(1, 1, NULL, NULL, 1, 0, 'nodeId', '所属节点', 'Category', 1, 1, NULL, 0, NULL, 0),
 (2, 1, NULL, NULL, 1, 0, 'title', '信息标题', 'Title', 1, 1, NULL, 0, NULL, 2),
 (3, 1, NULL, NULL, 1, 0, 'intro', '信息简介', 'MultiLineText', 0, 1, NULL, 0, '', 2),
 (4, 1, NULL, NULL, 1, 0, 'defaultPicUrl', '默认封面图片', 'CoverImage', 0, 1, NULL, 0, 'a:0:{}', 2),
 (5, 1, NULL, NULL, 0, 0, 'content', '文章内容', 'WordEditor', 1, 1, NULL, 0, NULL, 6),
 (6, 1, NULL, NULL, 1, 0, 'status', '状态', 'Status', 1, 1, NULL, 0, NULL, 8),
 (7, 1, NULL, NULL, 0, 0, 'imgRefMap', '文件引用映射', 'MultiLineText', 0, 0, NULL, 0, NULL, 6),
 (8, 1, NULL, NULL, 0, 0, 'fileRefs', '文件引用', 'SingleLineText', 0, 0, NULL, 0, NULL, 2),
 (9, 2, NULL, NULL, 1, 0, 'nodeId', '所属节点', 'Category', 1, 1, NULL, 0, NULL, 0),
 (10, 2, NULL, NULL, 1, 0, 'title', '职位名称', 'Title', 1, 1, NULL, 0, NULL, 2),
 (11, 2, NULL, NULL, 1, 0, 'intro', '职位简介', 'MultiLineText', 0, 1, NULL, 0, '', 2),
 (12, 2, NULL, NULL, 0, 0, 'content', '职位内容', 'WordEditor', 1, 1, NULL, 0, NULL, 6),
 (13, 2, NULL, NULL, 1, 0, 'status', '状态', 'Status', 1, 1, NULL, 0, NULL, 8),
 (14, 2, NULL, NULL, 0, 0, 'department', '招聘部门', 'SingleLineText', 1, 1, NULL, 0, NULL, 2),
 (15, 2, NULL, NULL, 0, 0, 'number', '招聘人数', 'Number', 1, 1, NULL, 0, NULL, 0),
 (16, 2, NULL, NULL, 0, 0, 'tel', '招聘电话', 'SingleLineText', 1, 1, NULL, 0, NULL, 2),
 (17, 2, NULL, NULL, 0, 0, 'endTime', '截止时间', 'SingleLineText', 1, 1, NULL, 0, NULL, 0),
 (18, 3, NULL, NULL, 1, 0, 'nodeId', '所属节点', 'Category', 1, 1, NULL, 0, NULL, 0),
(19, 3, NULL, NULL, 1, 0, 'title', '案例名称', 'Title', 1, 1, NULL, 0, NULL, 2),
(20, 3, NULL, NULL, 1, 0, 'intro', '案例简介', 'MultiLineText', 0, 1, NULL, 0, '', 2),
(21, 3, NULL, NULL, 0, 0, 'content', '案例内容', 'WordEditor', 1, 1, NULL, 0, NULL, 6),
(22, 3, NULL, NULL, 1, 0, 'status', '状态', 'Status', 1, 1, NULL, 0, NULL, 8),
(23, 3, NULL, NULL, 0, 0, 'fileRefs', '文件引用', 'SingleLineText', 0, 0, NULL, 0, NULL, 2);",
   //信息基本表
   "CREATE TABLE IF NOT EXISTS `app_site_cmmgr_general_info` (
`id` int(11) NOT NULL,
 `nodeId` int(10) unsigned NOT NULL COMMENT '关联的节点id',
 `cmodelId` tinyint(10) unsigned NOT NULL COMMENT '内容模型ID',
 `isDeleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
 `itemId` int(10) unsigned NOT NULL COMMENT '子模型数据ID',
 `title` varchar(255) NOT NULL COMMENT '信息标题',
 `priority` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '信息的优先级',
 `intro` varchar(255) DEFAULT NULL COMMENT '信息简介',
 `editor` varchar(64) NOT NULL COMMENT '编辑',
 `author` varchar(64) NOT NULL COMMENT '作者',
 `hits` int(10) unsigned NOT NULL COMMENT '文章点击数',
 `inputTime` int(10) unsigned NOT NULL COMMENT ' 发布时间',
 `updateTime` int(10) unsigned DEFAULT NULL COMMENT '信息更新时间',
 `infoGrade` char(5) DEFAULT NULL COMMENT '信息评分',
 `status` tinyint(4) NOT NULL COMMENT '文章状态',
 `passTime` int(10) unsigned DEFAULT NULL COMMENT '审核通过时间',
 `defaultPicUrl` varchar(512) DEFAULT NULL COMMENT '默认封面图片',
 `indexGenerated` tinyint(1) DEFAULT NULL COMMENT '搜索索引是否生成'
) ENGINE = InnoDB AUTO_INCREMENT = 5 DEFAULT CHARSET = utf8 COMMENT = '内容模型基本信息数据表';",
   //信息基本表数据
   "INSERT INTO `app_site_cmmgr_general_info` (`id`, `nodeId`, `cmodelId`, `isDeleted`, `itemId`, `title`, `priority`, `intro`, `editor`, `author`, `hits`, `inputTime`, `updateTime`, `infoGrade`, `status`, `passTime`, `defaultPicUrl`, `indexGenerated`) VALUES
(1, 4, 1, 0, 1, '企业介绍', 0, NULL, '', '', 1005, 1458725201, 1458727508, NULL, 3, 1458725201, NULL, NULL),
 (2, 4, 1, 0, 2, '企业文化', 0, NULL, '', '', 1002, 1458725201, 1458727626, NULL, 3, 1458725201, NULL, NULL),
 (3, 4, 1, 0, 3, '企业资质', 0, NULL, '', '', 1000, 1458725201, 1458725201, NULL, 3, 1458725201, NULL, NULL),
 (4, 4, 1, 0, 4, '发展历程', 0, NULL, '', '', 1004, 1458725201, 1458727700, NULL, 3, 1458725201, NULL, NULL);",
   //文章信息表
   "CREATE TABLE IF NOT EXISTS `app_site_cmmgr_u_article` (
`id` int(11) NOT NULL,
 `content` text NOT NULL,
 `imgRefMap` text,
 `fileRefs` varchar(512) DEFAULT NULL
) ENGINE = InnoDB AUTO_INCREMENT = 5 DEFAULT CHARSET = utf8;",
   "INSERT INTO `app_site_cmmgr_u_article` (`id`, `content`, `imgRefMap`, `fileRefs`) VALUES
(1, '', 'a:0:{}', ''),
 (2, '', 'a:0:{}', ''),
 (3, '', 'a:0:{}', NULL),
 (4, '', 'a:0:{}', '');",
   //招聘信息表
   "CREATE TABLE IF NOT EXISTS `app_site_cmmgr_u_job` (
`id` int(11) NOT NULL,
 `content` text NOT NULL,
 `department` varchar(128) NOT NULL,
 `number` int(10) NOT NULL,
 `tel` varchar(64) NOT NULL,
 `endTime` int(10) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8;",
   //案例信息表
   "CREATE TABLE IF NOT EXISTS `app_site_cmmgr_u_caseinfo` (
  `id` int(11) NOT NULL,
  `content` text NOT NULL,
  `fileRefs` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
   //商品基本信息
   "CREATE TABLE IF NOT EXISTS `app_zhuchao_product_base_info` (
`id` int(10) unsigned NOT NULL,
 `number` varchar(20) NOT NULL COMMENT '产品编号',
 `brand` varchar(20) NOT NULL COMMENT '品牌名称',
 `title` varchar(20) NOT NULL COMMENT '产品的名称与型号',
 `description` varchar(40) NOT NULL COMMENT '产品的特征描述',
 `hits` int(10) unsigned NOT NULL COMMENT '产品的点击率',
 `defaultImage` varchar(255) NOT NULL COMMENT '默认产品图片',
 `price` decimal(15, 2) DEFAULT NULL COMMENT '价格',
 `grade` tinyint(1) unsigned NOT NULL COMMENT '产品评分',
 `star` tinyint(1) unsigned NOT NULL COMMENT '产品的星级',
 `isBatch` tinyint(1) unsigned NOT NULL COMMENT '是否批发',
 `inputTime` int(10) unsigned NOT NULL COMMENT '录入时间',
 `updateTime` int(10) unsigned NOT NULL COMMENT '修改时间',
 `detailId` int(10) unsigned NOT NULL,
 `status` tinyint(2) unsigned NOT NULL COMMENT '产品的状态'
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COMMENT = '产品基本信息表';",
   //商品详细信息
   "CREATE TABLE IF NOT EXISTS `app_zhuchao_product_detail` (
`id` int(10) unsigned NOT NULL,
 `advertText` varchar(60) NOT NULL COMMENT '广告语',
 `keywords` varchar(255) NOT NULL COMMENT '产品关键词，以,号分隔',
 `attribute` text NOT NULL COMMENT '产品的属性，数组的序列化值，一个属性包括 name,value,type 三个值，type 1 分类自带， 2 用户自定义',
 `unit` varchar(15) NOT NULL COMMENT '单位',
 `minimum` int(11) unsigned NOT NULL COMMENT '起订量',
 `stock` int(11) unsigned NOT NULL COMMENT '库存',
 `images` text NOT NULL COMMENT '产品的图片，数组序列化值，一张图片包括url,sort,',
 `introduction` text NOT NULL COMMENT '产品的描述'
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COMMENT = '产品信息详情表';",
   //商品分组信息
   "CREATE TABLE IF NOT EXISTS `app_zhuchao_product_group` (
`id` int(10) unsigned NOT NULL,
 `pid` int(10) unsigned NOT NULL DEFAULT '0',
 `name` varchar(255) NOT NULL COMMENT '产品分组名',
 `inputTime` int(10) unsigned NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COMMENT = '产品分组信息';",
   //栏目模板映射表
   "CREATE TABLE IF NOT EXISTS `join_category_content_model_template` (
`categoryId` int(10) unsigned NOT NULL,
 `modelId` int(10) unsigned NOT NULL,
 `defaultTemplateFile` varchar(512) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COMMENT = '节点模型映射表';",
   "INSERT INTO `join_category_content_model_template` (`categoryId`, `modelId`, `defaultTemplateFile`) VALUES
(1, 1, '企业网站/新闻内容.phtml'),
 (2, 1, '企业网站/新闻内容.phtml'),
 (3, 1, '企业网站/新闻内容.phtml'),
 (5, 2, '企业网站/招聘内容.phtml');",
   //商品分组信息映射表
   "CREATE TABLE IF NOT EXISTS `join_product_group` (
`productId` int(11) NOT NULL,
 `groupId` int(11) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COMMENT = '产品分组关系';",
   "CREATE TABLE IF NOT EXISTS `sys_m_std_config` (
`key` varchar(255) NOT NULL COMMENT '站点配置信息键',
 `group` varchar(128) NOT NULL COMMENT '配置分组',
 `value` text NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8 COMMENT = '系统配置信息表';",
"CREATE TABLE IF NOT EXISTS `app_zhuchao_product_used_category` (
  `id` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL COMMENT '分类id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
   "ALTER TABLE `app_site_category_tree`
ADD PRIMARY KEY (`id`),
 ADD UNIQUE KEY `nodeIdentifier` (`nodeIdentifier`);",
   "ALTER TABLE `app_site_cmmgr_cmodel`
ADD PRIMARY KEY(`id`);",
   "ALTER TABLE `app_site_cmmgr_cmodel_fields`
ADD PRIMARY KEY(`id`);",
   "ALTER TABLE `app_site_cmmgr_general_info`
ADD PRIMARY KEY(`id`);",
   "ALTER TABLE `app_site_cmmgr_u_article`
ADD PRIMARY KEY(`id`);",
   "ALTER TABLE `app_site_cmmgr_u_job`
ADD PRIMARY KEY(`id`);",
   "ALTER TABLE `app_site_cmmgr_u_caseinfo`
ADD PRIMARY KEY(`id`);",
   "ALTER TABLE `app_zhuchao_product_base_info`
ADD PRIMARY KEY(`id`);",
   "ALTER TABLE `app_zhuchao_product_detail`
ADD PRIMARY KEY(`id`);",
   "ALTER TABLE `app_zhuchao_product_group`
ADD PRIMARY KEY(`id`);",
   "ALTER TABLE `join_category_content_model_template`
ADD PRIMARY KEY(`categoryId`, `modelId`);",
   "ALTER TABLE `join_product_group`
ADD PRIMARY KEY(`productId`, `groupId`);",
   "ALTER TABLE `sys_m_std_config`
ADD PRIMARY KEY(`key`, `group`);",
   "ALTER TABLE `app_zhuchao_product_used_category`
  ADD PRIMARY KEY (`id`);",
   "ALTER TABLE `app_site_category_tree`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '节点的ID', AUTO_INCREMENT = 7;",
   "ALTER TABLE `app_site_cmmgr_cmodel`
MODIFY `id` tinyint(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '内容模型id', AUTO_INCREMENT = 4;",
   "ALTER TABLE `app_site_cmmgr_cmodel_fields`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT, AUTO_INCREMENT = 24;",
   "ALTER TABLE `app_site_cmmgr_general_info`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT = 5;",
   "ALTER TABLE `app_site_cmmgr_u_article`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT = 5;",
   "ALTER TABLE `app_site_cmmgr_u_job`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;",
   "ALTER TABLE `app_site_cmmgr_u_caseinfo`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;",
   "ALTER TABLE `app_zhuchao_product_base_info`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;",
   "ALTER TABLE `app_zhuchao_product_detail`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;",
   "ALTER TABLE `app_zhuchao_product_group`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;",
   "ALTER TABLE `app_zhuchao_product_used_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;",
   "INSERT INTO `sys_m_std_config` (`key`,`group`,`value`) VALUES ('product','Nav',1),('case','Nav',1),('news','Nav',1),('zhaopin','Nav',1),('aboutus','Nav',1);",
   "INSERT INTO `sys_m_std_config` (`key`,`group`,`value`) VALUES ('Banner','Site','a:0:{}');",
);
