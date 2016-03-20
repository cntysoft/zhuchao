<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace Provider;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Cntysoft\Kernel;
use App\ZhuChao\Provider\Acl;
use Cntysoft\Kernel\ConfigProxy;
use Phalcon\Db\Adapter\Pdo\Mysql;
/**
 * 前端模块初始化代码
 */
class Module implements ModuleDefinitionInterface
{
   /**
    * Registers an autoloader related to the module
    *
    * @param \Phalcon\DiInterface $dependencyInjector
    */
   public function registerAutoloaders(\Phalcon\DiInterface $dependencyInjector = null)
   {
      $di = Kernel\get_global_di();
      $loader = $di->getShared('loader');
      $loader->registerNamespaces(array(
         'ProviderFrontApi' => __DIR__ . DS . 'FrontApi'
              ), true);
      $loader->registerDirs(array(
         __DIR__ . DS . 'Controllers'
      ))->register();
   }

   /**
    * Registers services related to the module
    *
    * @param \Phalcon\DiInterface $dependencyInjector
    */
   public function registerServices(\Phalcon\DiInterface $dependencyInjector)
   {
      $acl = new Acl();
      $dependencyInjector->set('ProviderAcl', function()use($acl) {
         return $acl;
      });

      //在这里需要初始化云站系统的数据库，保证前台提交信息能够保存到对应的数据库中
      if ($acl->isLogin()) {
         $user = $acl->getCurUser();
         $company = $user->getCompany();

         if ($company && $company->getSubAttr()) {
            Kernel\get_site_id($company->getId());
            
            $config = ConfigProxy::getGlobalConfig();
            $cfg = $config->db->toArray();
            $dependencyInjector->setShared('siteDb', function() use($cfg) {
               $cfg['dbname'] = Kernel\get_site_db_name();
               $db = new Mysql($cfg);
               return $db;
            });
         }
      }
   }

}