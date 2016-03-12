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
use App\ZhuChao\SiteMgr\Mgr as SiteMgr;
use Cntysoft\Kernel;
use ZhuChao\Kernel\Exception;
use ZhuChao\Kernel\StdErrorType;
/**
 * @package ZhuChao\Phalcon\Mvc
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
         ZHUCHAO_SYS_LIB_DIR . DS . 'DistConst.php',
         ZHUCHAO_SYS_LIB_DIR . DS . 'Kernel' . DS . 'Funcs' . DS . 'Internal.php',
         ZHUCHAO_SYS_LIB_DIR . DS . 'Version.php'
      );
      foreach ($files as $file) {
         include $file;
      }
   }

   /**
    * 初始化主数据库链接
    */
   protected function beforeInitialized()
   {
      $cfg = $this->gcfg->db->toArray();
      $cfg['dbname'] = \Cntysoft\ZHUCHAO_PLATFORM_DBNAME;
      $this->di->setShared('db', function() use($cfg) {
         $db = new Mysql($cfg);
         return $db;
      });
   }

   /**
    * 这里进行数据库的绑定
    */
   protected function beforeDbConnInitialized()
   {
      $request = $this->getDI()->get('request');
      $domain = $request->getHttpHost();
      $parts = explode('.', $domain);

      //判断是否访问的是主站
      if (count($parts) > 3) {
         $siteId = -1;
         $siteName = array_shift($parts);
         //判断主域名是否正确
         if (implode('.', $parts) === \Cntysoft\ZHUCHAO_SITE_DOMAIN_DEVEL) {
            //查看域名是否存在
            $siteMaper = new SiteMgr();
            $siteId = $siteMaper->getSiteIdByName($siteName);
            if ($siteId > 0) {
               Kernel\get_site_id($siteId);
            } else {
               if (SYS_RUNTIME_MODE == SYS_RUNTIME_MODE_DEBUG) {
                  die('站点不存在');
               }
            }
         }
      }
   }

   /**
    * 初始化数据库链接
    */
   protected function initDbConnection()
   {
      $cfg = $this->gcfg->db->toArray();
      $this->di->setShared('siteDb', function() use($cfg) {
         $cfg['dbname'] = Kernel\get_site_db_name();
         $db = new Mysql($cfg);
         return $db;
      });
   }

}