<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
*/
namespace App\ZhuChao\Product;

class Constant
{
   const MODULE_NAME = 'ZhuChao';
   const APP_NAME = 'Product';
   const APP_API_PRODUCT_MGR = 'ProductMgr';
   const APP_API_GROUP_MGR = 'GroupMgr';
   
   //产品状态
   const PRODUCT_STATUS_DRAFT = 1; //草稿
   const PRODUCT_STATUS_PEEDING = 2; //审核中
   const PRODUCT_STATUS_VERIFY = 3; //审核通过
   const PRODUCT_STATUS_REJECTION = 4; //拒绝
   const PRODUCT_STATUS_SHELF = 5; //下架
   const PRODUCT_STATUS_DELETE = 6; //删除

   //权限相关
   const PK_APP_KEY = 'Product';
   const PK_WIDGET_PRODUCT = 'ProductMgr';
   
   const ATTR_FILTER_REGEX = '/[\s:\/\(\)\*&@!#$%\^\-+~\?]/is';
    const SORT_HITS = 'hits'; //这种方式程序自己排列
   const SORT_PRICE = 'price';
   const SORT_TIME = 'inputTime';
   const SORT_GRADE = 'grade';
   const FLAG_UP = '+';
   const FLAG_DOWN = '-';
}
