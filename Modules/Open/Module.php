<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author changwang <chenyongwang@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace Open;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Cntysoft\Kernel;
use App\Shop\UserCenter\Acl;
use App\Sys\User\Acl as SysUserAcl;
use App\Sys\User\PermManager;
/**
 * 前端AJAX模块初始化代码
 */
class Module implements ModuleDefinitionInterface
{
   /**
    * Registers an autoloader related to the module
    */
   public function registerAutoloaders(\Phalcon\DiInterface $dependencyInjector = null)
   {
      $di = Kernel\get_global_di();
      $loader = $di->getShared('loader');
      $loader->registerNamespaces(array(
         'FrontApi'  => __DIR__ . DS . 'FrontApi',
         'MobileApi' => __DIR__ . DS . 'MobileApi',
         'EditApi'   => __DIR__ . DS . 'EditApi'
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
      $dependencyInjector->set('FrontUserAcl', function() {
         return new Acl();
      });
      $dependencyInjector->set('SysUserAcl', function() {
         return new SysUserAcl();
      });
      $dependencyInjector->set('SysPermMgr', function() {
         return new PermManager();
      });
   }

}