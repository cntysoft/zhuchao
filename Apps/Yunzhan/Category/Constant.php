<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\Yunzhan\Category;
class Constant
{
   //节点类型
   const N_TYPE_SINGLE = 1;
   const N_TYPE_LINK = 2;
   const N_TYPE_GENERAL = 3;
   const N_TYPE_INDEX = 4;
   const OPEN_TYPE_ORG = 0;
   const OPEN_TYPE_NEW = 1;
   const SYS_PARENT_ID = -1;
   //节点树根节点ID
   const TREE_ROOT_ID = 0;
   //首页的节点ID
   const INDEX_NODE_ID = 1;
   //节点权限键值相关的常量, 节点结构管理
   const PK_STRUCTURE_MANAGE = 'Structure';
   //节点排序权限
   const PK_NODE_SORT = 'Sorter';

   const ID_SEED_START = 1;

   // getAppCaller() 调用方法对应的值
   const MODULE_NAME = 'Yunzhan';
   const APP_NAME = 'Category';
   const APP_API_STRUCTURE = 'Structure';
   //栏目
   const NODE_COMPANY_ID = 2; //企业新闻栏目
   const NODE_INDUSTRY_ID = 3; //行业新闻栏目
   const NODE_ABOUT_ID = 4; //关于我们栏目
   const NODE_JOIN_ID = 5; //招聘中心栏目
   const NODE_CASE_ID = 6; //案例中心栏目
}