<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author ZhiHui <liuyan2526@qq.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace App\ZhuChao\MessageMgr\Model;
use Cntysoft\Phalcon\Mvc\Model as BaseModel;
class Offer extends BaseModel
{
   protected $id;
   protected $inquiryId;
   protected $supplierId;
   protected $lowPrice;
   protected $highPrice;
   protected $content;
   protected $inputTime;
   protected $status;
   protected $recommendGoods;

   public function getSource()
   {
      return 'app_zhuchao_messagemgr_offer';
   }

   public function initialize()
   {
      $this->belongsTo('supplierId', 'App\ZhuChao\Provider\Model\BaseInfo', 'id', array(
         'alias' => 'Provider'
      ));
   }

   public function getId()
   {
      return $this->id;
   }

   public function getInquiryId()
   {
      return $this->inquiryId;
   }

   public function getSupplierId()
   {
      return $this->supplierId;
   }

   public function getLowPrice()
   {
      return $this->lowPrice;
   }

   public function getHighPrice()
   {
      return $this->highPrice;
   }

   public function getContent()
   {
      return $this->content;
   }

   public function getInputTime()
   {
      return $this->inputTime;
   }

   public function getStatus()
   {
      return $this->status;
   }

   public function getRecommendGoods()
   {
      return unserialize($this->recommendGoods);
   }

   public function setId($id)
   {
      $this->id = $id;
   }

   public function setInquiryId($inquiryId)
   {
      $this->inquiryId = $inquiryId;
   }

   public function setSupplierId($supplierId)
   {
      $this->supplierId = $supplierId;
   }

   public function setLowPrice($lowPrice)
   {
      $this->lowPrice = $lowPrice;
   }

   public function setHighPrice($highPrice)
   {
      $this->highPrice = $highPrice;
   }

   public function setContent($content)
   {
      $this->content = $content;
   }

   public function setInputTime($inputTime)
   {
      $this->inputTime = $inputTime;
   }

   public function setStatus($status)
   {
      $this->status = $status;
   }

   public function setRecommendGoods($recommendGoods)
   {
      $this->recommendGoods = serialize($recommendGoods);
   }

}