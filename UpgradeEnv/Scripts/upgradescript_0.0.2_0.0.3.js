UpgradeEnv.writeLogMsg("开始升级数据库");
UpgradeEnv.dbQuery("ALTER TABLE `app_zhuchao_provider_company_info` ADD `rid` INT UNSIGNED NOT NULL COMMENT '文件引用' AFTER `subAttr`;");
UpgradeEnv.dbQuery("ALTER TABLE `app_zhuchao_buyer_profile` ADD `fileRefs` VARCHAR(255) NULL AFTER `sex`;");
UpgradeEnv.dbQuery("ALTER TABLE `app_zhuchao_provider_company_info` ADD `pcTpl` TINYINT(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '电脑模板编号' AFTER `subAttr`, ADD `mobileTpl` TINYINT(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '手机模板编号' AFTER `pcTpl`;");
UpgradeEnv.dbQuery("CREATE TABLE IF NOT EXISTS `app_zhuchao_provider_domain_map` ( \
  `domain` varchar(64) NOT NULL COMMENT '域名', \
  `companyId` int(10) unsigned NOT NULL COMMENT '企业ID' \
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
UpgradeEnv.dbQuery("ALTER TABLE `app_zhuchao_provider_domain_map`\
  ADD PRIMARY KEY (`domain`,`companyId`), \
  ADD UNIQUE KEY `domain` (`domain`);");
UpgradeEnv.writeLogMsg("数据库升级完成");
UpgradeEnv.writeLogMsg("开始修改配置文件");
var config = UpgradeEnv.getConfig("Module/Provider.config.json");
var len = config.routes.length;
for(var i = 0; i < len; i++) {
    var route = config.routes[i];
    if('ProviderProductGroup' == route.key) {
        config.routes.splice(i, 1);
        break;
    }
}
UpgradeEnv.saveConfig("Module/Provider.config.json", config)
UpgradeEnv.writeLogMsg('配置文件修改完成');