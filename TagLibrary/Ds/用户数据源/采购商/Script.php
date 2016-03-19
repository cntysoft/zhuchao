<?php
/**
 * Cntysoft Web Platform 中国领先的web平台
 *
 * @category   Cntysoft
 * @author     SOFTBOY <cntysoft@163.com>
 * @copyright  Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace TagLibrary\Ds\UserModel;
use Cntysoft\Framework\Qs\Engine\Tag\AbstractDsScript;
use App\ZhuChao\Buyer\Constant as BUYER_CONST;

class BuyerInfo extends AbstractDsScript
{
   public function load()
   {
      $user = $this->appCaller->call(BUYER_CONST::MODULE_NAME, BUYER_CONST::APP_NAME, BUYER_CONST::APP_API_BUYER_ACL, 'getCurUser');
      $detail = $user->getProfile();
      $ret = array(
         'avatar' => $detail->getAvatar(),
         'name'   => $user->getName(),
         'phone'  => $user->getPhone(),
         'sex'    => $detail->getSex(),
         'level'  => $detail->getLevel()
      );
      return $ret;
   }

}