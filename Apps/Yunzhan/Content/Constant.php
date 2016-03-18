<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\Yunzhan\Content;
final class Constant
{
   const INFO_S_DRAFT = 1;
   const INFO_S_PEEDING = 2;
   const INFO_S_VERIFY = 3;
   const INFO_S_REJECTION = 4;
   const INFO_S_ALL = 5;
   const INFO_S_DELETE = 6;

   const INFO_M_ALL = 0;

   const NODE_ID_ALL = 0;

   //权限键值
   const PK_LIST_VIEW = 'InfoManager';
   const PK_TRASHCAN = 'Trashcan';
   /**
    * 文章默认简介字数
    */
   const INTRO_DEFAULT_LEN = 150;
   //API调用相关
   const MODULE_NAME = 'Yunzhan';
   const APP_NAME = 'Content';
   const APP_API_MANAGER = 'Manager';
   const APP_API_INFO_LIST = 'InfoList';
   
   /**   
    * 用来存放单图栏目和组图标识的常量
    */
   const QUERY_ATTR_IMAGES = 1;
   const QUERY_ATTR_SINGLE = 2;
   
   const EFFECT_CATEGORY_IDENTIFIER = 'xiaoguotu';
   
}