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
class Inquiry extends BaseModel
{
   protected $id;
   protected $gid;
   protected $uid;
   protected $providerId;
   protected $inputTime;
   protected $expireTime;
   protected $content;
   protected $status;

   public function getSource()
   {
      return 'app_zhuchao_messagemgr_inquiry';
   }

   public function initialize()
   {
      $this->hasOne('id', 'App\ZhuChao\MessageMgr\Model\Offer', 'inquiryId', array(
         'alias' => 'Offer'
      ));
      $this->belongsTo('uid', 'App\ZhuChao\Buyer\Model\BaseInfo', 'id', array(
         'alias' => 'Buyer'
      ));
      $this->belongsTo('gid', 'App\ZhuChao\Product\Model\Product', 'id', array(
         'alias' => 'Product'
      ));
   }

   public function getProviderId()
   {
      return (int)$this->providerId;
   }

   public function setProviderId($providerId)
   {
      $this->providerId = (int)$providerId;
   }

   public function getId()
   {
      return (int)$this->id;
   }

   public function getGid()
   {
      return (int)$this->gid;
   }

   public function getUid()
   {
      return (int)$this->uid;
   }

   public function getInputTime()
   {
      return (int)$this->inputTime;
   }

   public function getExpireTime()
   {
      return (int)$this->expireTime;
   }

   public function getContent()
   {
      return $this->content;
   }

   public function setId($id)
   {
      $this->id = (int)$id;
   }

   public function setGid($gid)
   {
      $this->gid = (int)$gid;
   }

   public function setUid($uid)
   {
      $this->uid = (int)$uid;
   }

   public function setInputTime($inputTime)
   {
      $this->inputTime = (int)$inputTime;
   }

   public function setExpireTime($expireTime)
   {
      $this->expireTime = (int)$expireTime;
   }

   public function setContent($content)
   {
      $this->content = $content;
   }

   public function getStatus()
   {
      return (int)$this->status;
   }

   public function setStatus($status)
   {
      $this->status = (int)$status;
      return $this;
   }

}