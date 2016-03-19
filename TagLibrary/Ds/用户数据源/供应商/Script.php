<?php
/**
 * Cntysoft Web Platform 中国领先的web平台
 *
 * @category   Cntysoft
 * @author     SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Ds\UserModel;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractDsScript;
use App\ZhuChao\Provider\Constant as UserContent;
class ProviderInfo extends AbstractDsScript
{
   public function load()
   {
      $user = $this->appCaller->call(UserContent::MODULE_NAME, UserContent::APP_NAME, UserContent::APP_API_MGR, 'getCurUser',array());
      $details = $user->getProfile();
      $ret = array(
         'name'=>$user->getName(),
         'phone'=>$user->getPhone(),
         'realName'=>$details->getRealName(),
         'sex' => $details->getSex(),
         'department' => $details->getDepartment(),
         'position' => $details->getPosition(),
         'email' => $details->getEmail(),
         'showPhone' => $details->getShowPhone(),
         'qq' => $details->getQq(),
         'tel' => $details->getTel(),
         'fax' => $details->getFax()
      );
      return $ret;
   }

}