<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author Arvin <cntyfeng@163.com>
 * @copyright Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
*/
namespace App\ZhuChao\Buyer\Model;
use Cntysoft\Phalcon\Mvc\Model as BaseModel;

class Profile extends BaseModel
{
   protected $id;
   protected $avatar;
   protected $experience;
   protected $level;
   protected $point;
   protected $sex;
   
   public function getSource()
   {
      return 'app_zhuchao_buyer_profile';
   }
   
   public function getId()
   {
      return (int)$this->id;
   }

   public function getAvatar()
   {
      return $this->avatar;
   }
   
   public function getExperience()
   {
      return (int)$this->experience;
   }

   public function getLevel()
   {
      return (int)$this->level;
   }

   public function getPoint()
   {
      return (int)$this->point;
   }

   public function getSex()
   {
      return (int)$this->sex;
   }

   public function setId($id)
   {
      $this->id = (int)$id;
   }

   public function setAvatar($avatar)
   {
      $this->avatar = $avatar;
   }
   
   public function setExperience($experience)
   {
      $this->experience = (int)$experience;
   }

   public function setLevel($level)
   {
      $this->level = (int)$level;
   }

   public function setPoint($point)
   {
      $this->point = (int)$point;
   }

   public function setSex($sex)
   {
      $this->sex = (int)$sex;
   }

}
