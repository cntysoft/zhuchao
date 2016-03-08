/*
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 封面图片UI设置面板
 */
Ext.define('App.Site.CmMgr.Lib.FieldOptSetter.CoverImage', {
    extend : 'App.Site.CmMgr.Lib.FieldOptSetter.AbstractSetter',
    /**
     * @inheritdoc
     */
    fieldType : 'CoverImage',
    /**
     * @inheritdoc
     */
    langTextKey : 'FIELD_OPT_SETTER.COVER_IMAGE',
    initComponent : function()
    {
        Ext.apply(this, {
            html : this.GET_LANG_TEXT('FIELD_OPT_SETTER.EMPTY_HTML')
        });
        this.callParent();
    },
    /**
     * @inheritdoc
     */
    doGetValuesHandler : function()
    {
        var values = {};
        if(this.mode == 1){
            values.type = 'varchar';
            values.length = 512;
        }
        return values;
    }
});