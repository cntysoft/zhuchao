<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author ZhiHui <liuyan2526@qq.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\ZhuChao\MessageMgr;

class Constant
{
   const MODULE_NAME = 'ZhuChao';
   const APP_NAME = 'MessageMgr';
   const APP_API_OFFER = 'InquiryOffer';
   
   const NOT_HAVE_PRICE = '<b style="color : red;">暂无报价</b>';
   
   const PK_OFFER = 'Offer';
   
   //询价单的状态
   const INQUIRY_STATUS_NO_OFFER = 1;  
   const INQUIRY_STATUS_OFFERED = 2;
   
   //报价单状态
   const OFFER_STATUS_REPLY = 1;
   const OFFER_STATUS_NO_REPLAY = 0;
}
