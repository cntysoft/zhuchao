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
   protected $inputTime;
   protected $expireTime;
   protected $content;

   public function getSource()
   {
      return 'app_zhuchao_messagemgr_inquiry';
   }

   public function initialize()
   {
      $this->hasOne('id', 'App\ZhuChao\MessageMgr\Model\Offer', 'inquiryId', array(
         'alias' => 'Offer'
      ));
      $this->belongsTo('uid','App\ZhuChao\Buyer\Model\BaseInfo','id',array(
         'alias' => 'Buyer'
      ));
   }

   public function getId()
   {
      return $this->id;
   }

   public function getGid()
   {
      return $this->gid;
   }

   public function getUid()
   {
      return $this->uid;
   }

   public function getInputTime()
   {
      return $this->inputTime;
   }

   public function getExpireTime()
   {
      return $this->expireTime;
   }

   public function getContent()
   {
      return $this->content;
   }

   public function setId($id)
   {
      $this->id = $id;
   }

   public function setGid($gid)
   {
      $this->gid = $gid;
   }

   public function setUid($uid)
   {
      $this->uid = $uid;
   }

   public function setInputTime($inputTime)
   {
      $this->inputTime = $inputTime;
   }

   public function setExpireTime($expireTime)
   {
      $this->expireTime = $expireTime;
   }

   public function setContent($content)
   {
      $this->content = $content;
   }

}