<?php
/**
 * Cntysoft Cloud Software Team
 */
namespace App\ZhuChao\Provider\Model;
use Cntysoft\Phalcon\Mvc\Model as BaseModel;
/**
 * @author Changwang <chenyongwang1104@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
class Map extends BaseModel
{
   private $domain;
   private $companyId;
   
   public function getSource()
   {
      return 'app_zhuchao_provider_domain_map';
   }
   
   public function getDomain()
   {
      return $this->domain;
   }

   public function setDomain($domain)
   {
      $this->domain = $domain;
      return $this;
   }

   public function getCompanyId()
   {
      return (int)$this->companyId;
   }

   public function setCompanyId($companyId)
   {
      $this->companyId = (int)$companyId;
      return $this;
   }

}
