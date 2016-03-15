<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace ZhuChao\InitFlow\Listeners;
use Cntysoft\Phalcon\Mvc\Listeners\BootstrapListener as BaseBootstrapListener;
use Phalcon\Mvc\Router\Group as RouterGroup;
use Phalcon\Mvc\Router;
use Cntysoft\Kernel\ConfigProxy;
/**
 * 系统一些小东西初始化监听类
 */
class BootstrapListener extends BaseBootstrapListener
{
   /**
    * @param \Phalcon\Mvc\Router $router
    * @param \Phalcon\Config $config
    */
   protected function configRouter($router, $config)
   {
      $router->add('/' . $config->sysEntry, array(
         'module'     => 'Sys',
         'controller' => 'Index',
         'action'     => 'index'
      ))->setHostname(\Cntysoft\SYS_SITE_NAME_DEVEL);
      $router->add('/' . $config->sysEntry . 'devel', array(
         'module'     => 'Sys',
         'controller' => 'Index',
         'action'     => 'devel'
      ))->setHostname(\Cntysoft\SYS_SITE_NAME_DEVEL);
   }

   /**
    * 注册所有模块的路由信息
    *
    * @param \Phalcon\Mvc\Router $router
    */
   protected function registerModulesRouteConfigHandler(Router $router)
   {
      //这里只需要注册路由相关信息
      $globalConfig = ConfigProxy::getGlobalConfig();
      $modules = $globalConfig->modules;
      foreach ($modules as $mname => $module) {
         if ($module->hasConfig) {
            $mcfg = ConfigProxy::getModuleConfig($mname);
            if (isset($mcfg->routes)) {
               $group = new RouterGroup();
               if(isset($mcfg->hostName)) {
                  $moduleHostNames = $globalConfig->moduleHostNames->toArray();
                  $group->setHostname($moduleHostNames[$mname]);
               }
               foreach ($mcfg->routes as $route) {
                  $group->add($route->rule, $route->option->toArray());
               }
               
               $router->mount($group);
            }
         }
      }
   }

}