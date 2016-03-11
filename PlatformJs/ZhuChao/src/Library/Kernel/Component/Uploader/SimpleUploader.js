/*
 * Cntysoft Cloud Software Team
 * 
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * WEBOS简单上传组建
 */
Ext.define('ZhuChao.Kernel.Component.Uploader.SimpleUploader', {
   extend : 'WebOs.Component.Uploader.SimpleUploader',
   alias : 'widget.zhuchaosimpleuploader',
   /**
    * 设置最大的上传
    * 
    * @returns {Number}
    */
   getUploadLimitSize : function ()
   {
      var phpSetting = ZC.getSysEnv().get(ZhuChao.Kernel.Const.ENV_PHP_SETTING);
      var size = parseInt(phpSetting.uploadMaxFileSize);
      return 1 > size ? size : 1;
   }
});