/*
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
/**
 * 这个类只要为了打包将需要的ExtJs文件都包含进来
 */
Ext.define('ZhuChao.ExtJsRequires', {
   requires : [
      'Ext.panel.Panel',
      'Ext.grid.Panel',
      'Ext.data.Store',
      'Ext.menu.Menu',
      'Ext.tree.Panel',
      'Ext.EventManager',
      'Ext.String',
      'Ext.layout.container.Border',
      'Ext.layout.container.Table',
      'Ext.layout.container.Fit',
      'Ext.layout.container.Accordion',
      'Ext.grid.column.Action',
      'Ext.menu.ColorPicker',
      'Ext.grid.column.RowNumberer',
      'Ext.Img',
      'Ext.grid.column.Template',
      'Ext.grid.column.Date',
      'Ext.grid.feature.Summary',
      'Ext.form.TimeField',
      'Ext.form.field.Hidden',
      'Ext.chart.CartesianChart',
      'Ext.chart.axis.Numeric',
      'Ext.chart.axis.Time',
      'Ext.chart.axis.Category',
      'Ext.chart.series.Line',
      'Ext.chart.series.Bar',
      'Ext.tip.ToolTip',
      'Ext.ux.colorpick.Field'
   ]
});
