<?php
/**
 * Cntysoft Cloud Software Team
 */
namespace ZhuChao\ReleaseTools;
use Cntysoft\Kernel\ConfigProxy;
use Cntysoft\Kernel;
use Phalcon\Db\Adapter\Pdo\Mysql;
use App\Site\Setting\Constant as SITE_SETTING_CONST;
use App\Site\Category\Constant as CATEGORY_CONST;
use App\Sys\AppInstaller\Constant as INSTALLER_CONST;
use App\Site\CmMgr\Constant as CMMGR_CONST;
use App\Sys\User\Constant as SYS_USER_CONST;
use App\Sys\AppInstaller\Model\AppModule;
use App\Sys\AppInstaller\Model\InstalledApp;
use App\Sys\User\Member;
/**
 * @author SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
class SysReseter
{
   protected $baseDir;
   protected $di;
   protected $appCaller;
   protected $version;
   public function __construct($version = null)
   {
      $cfg = ConfigProxy::getGlobalConfig();
      $cfg = $cfg->db;
      if(null == $version){
         $cfg->dbname ='zhuchao_devel';
      }else{
         $cfg->dbname ='zhuchao_'.$version;
      }
      $this->version = $version;
      $this->di = Kernel\get_global_di();
      $this->di ->setShared('db', function() use($cfg){
         $db = new Mysql($cfg->toArray());
         return $db;
      });
      $this->baseDir = __DIR__ . DS .'Data';
      $this->appCaller = $this->di->get('AppCaller');
   }


   public function prepareforRelease()
   {
      set_time_limit(0);
      $this->clearTables();
      $this->setupSiteConfig();
      $this->setupDefaultPermRes();
      $this->setupSysUserRoles();
      $this->setupCmModels();
      $this->setupCategory();
   }

   protected function clearTables()
   {
      $tableNames = $this->getDataArray('TableNames');
      foreach($tableNames as $tableName){
         Kernel\truncate_table($tableName);
      }
   }
   
   protected function setupSiteConfig()
   {
      $siteInfo = $this->getDataArray('SiteConfig');
      $siteCfg = $this->appCaller->getAppObject(
         SITE_SETTING_CONST::MODULE_NAME,
         SITE_SETTING_CONST::APP_NAME,
         SITE_SETTING_CONST::APP_API_CFG
      );
      $siteInfo[] = array(
         'key' => 'dbVersion',
         'group' => 'Kernel',
         'value' => $this->version ? $this->version : 'devel'
      );
      foreach($siteInfo as $item){
         $siteCfg->setItem($item['group'], $item['key'], $item['value']);
      }
   }

   protected function setupCategory()
   {
      $nodes = $this->getDataArray('DefaultNodes');
      $this->appCaller->call(
         CATEGORY_CONST::MODULE_NAME,
         CATEGORY_CONST::APP_NAME,
         CATEGORY_CONST::APP_API_STRUCTURE,
         'createNodeStructure',
         array(
            $nodes
         )
      );
   }

   public function setupDefaultPermRes()
   {
      $defaultPermRes = $this->getDataArray('DefaultPermRes');
      $db = Kernel\get_db_adapter();
      $db->begin();
      $appModules = $this->getDataArray('AppModules');
      $defaultInstalledApps = $this->getDataArray('DefaultInstalledApps');

      foreach($appModules as $item){
         $m = new AppModule();
         $m->assignBySetter($item);
         $m->save();
      }
      foreach($defaultInstalledApps as $item){
         $m = new InstalledApp();
         $m->assignBySetter($item);
         $m->save();
      }
      $mounter = $this->appCaller->getAppObject(
         INSTALLER_CONST::MODULE_NAME,
         INSTALLER_CONST::APP_NAME,
         INSTALLER_CONST::APP_API_RES_MOUNTER
      );
      foreach($defaultPermRes as $res){
         $parts = explode('.', $res);
         $mounter->mountPermRes($parts[0], $parts[1]);
      }
      $db->commit();
   }

   public function setupSysUserRoles()
   {
      $roles = $this->getDataArray('DefaultRoles');
      $roleMgr = $this->appCaller->getAppObject(
         SYS_USER_CONST::MODULE_NAME,
         SYS_USER_CONST::APP_NAME,
         SYS_USER_CONST::APP_API_ROLE_MGR
      );
      foreach($roles as $role){
         $roleMgr->addRole($role);
      }
      //添加一个新的管理员
      $super = $this->getDataArray('SuperUser');
      $member = new Member();
      $member->addSysUser($super);
   }

   public function setupCmModels()
   {
      $cmmgr = $this->appCaller->getAppObject(
         CMMGR_CONST::MODULE_NAME,
         CMMGR_CONST::APP_NAME,
         CMMGR_CONST::APP_API_MGR
      );
      $cmmgr->generateBuildInModelFields();
   }

   /**
    * @param int $pid
    * @param array $node
    * @param \App\ZhuChao\CategoryMgr\Mgr $mgr
    */
   protected function doInsertGoodsCategoryRecursive($pid, $node, $mgr)
   {
      //添加本身
      $id = $mgr->addNode($pid, $node['name'], $node['identifier']);
      if(isset($node['children'])){
         foreach($node['children'] as $child){
            $this->doInsertGoodsCategoryRecursive($id, $child, $mgr);
         }
      }
   }

   protected function getDataArray($key)
   {
      $filename = $this->baseDir.DS.$key.'.php';
      return  include $filename;
   }
}