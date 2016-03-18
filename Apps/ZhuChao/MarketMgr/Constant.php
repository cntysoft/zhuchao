<?php
/**
 * Cntysoft Cloud Software Team
 */
namespace App\ZhuChao\MarketMgr;
/**
 * @author wql <wql1211608804@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
class Constant
{
   const MODULE_NAME = 'ZhuChao';
   const APP_NAME = 'MarketMgr';
   const APP_API_BARGAIN = 'Bargain';
   const APP_API_COUPON = 'Coupon';
   const APP_API_DISCOUNT = 'Discount';
   const APP_API_ADS = 'Ads';
   const APP_API_COMMENT = 'ProductComment';
   //权限
   const PK_APP_KEY = 'MarketMgr';
   const PK_WIDGET_BARGAIN = 'Bargain';
   const PK_WIDGET_COUPON = 'Coupon';
   const PK_WIDGET_DISCOUNT = 'Discount';
   const PK_WIDGET_USER_STATISTICS = 'UserStatistics';
   const PK_WIDGET_ORDER_STATISTICS = 'OrderStatistics';
   const PK_WIDGET_COMMENT = 'Comment';
   const PK_WIDGET_ADS = 'Ads';
   
   
   //用户优惠券状态
   const USER_COUPON_UNUSE = 0; //未使用
   const USER_COUPON_USEED = 1; //已使用
   const USER_COUPON_TIMEOUT = 2; //已过期

   const COMMENT_S_ALL = 0; //全部状态
   const COMMENT_S_NOT_VERIFY = 1; //未审核状态
   const COMMENT_S_NORMAL = 2; //正常状态
   const COMMENT_S_DELETED = 3; //已删除
}