<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace ZhuChao\Phalcon\Mvc;
use Cntysoft\Phalcon\Mvc\Application as BaseApplication;
use ZhuChao\InitFlow\Listeners;
use Phalcon\Db\Adapter\Pdo\Mysql;
/**
 * @package FengHuang\Phalcon\Mvc
 */
class Application extends BaseApplication
{

   /**
    * @var array $gcfg
    */
   protected $gcfg = null;

   public function __construct($dependencyInjector = null)
   {
      //初始化系统平台的数据库连接类
      parent::__construct($dependencyInjector);
   }

   protected function beforeInitialized()
   {
      
   }

   /**
    * 咱们只要在这里进行站点ID的探测
    */
   protected function beforeDbConnInitialized()
   {
      
   }

   protected function bindListeners()
   {
      $eventManager = $this->getEventsManager();
      $bootstrapListener = new Listeners\BootstrapListener();
      $viewListener = new Listeners\ViewListener();
      $serviceListener = new Listeners\ServiceListener();
      $serviceListener->attach($eventManager);
      $bootstrapListener->attach($eventManager);
      $viewListener->attach($eventManager);
   }

   protected function loadCoreFiles()
   {
      parent::loadCoreFiles();
      $files = array(
         ZHUCHAO_SYS_LIB_DIR . DS . 'Const.php',
         ZHUCHAO_SYS_LIB_DIR . DS . 'Kernel' . DS . 'Funcs' . DS . 'Internal.php',
         ZHUCHAO_SYS_LIB_DIR . DS . 'Version.php'
      );
      foreach ($files as $file) {
         include $file;
      }
   }

   protected function initDbConnection()
   {
      //到时候可以在这里统计一个页面的查询次数
      //初始化系统
      $cfg = $this->gcfg->db->toArray();
      $this->di->setShared('db',
         function() use($cfg) {
         $db = new Mysql($cfg);
         return $db;
      });
   }

}