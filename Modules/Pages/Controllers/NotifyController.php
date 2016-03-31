<?php
/**
 * Cntysoft Cloud Software Team
 * 
 * @author ZhiHui <liuyan2526@qq.com>
 * @copyright Copyright (c) 2010-2011 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license http://www.cntysoft.com/license/new-bsd     New BSD License
 */
use Cntysoft\Phalcon\Mvc\AbstractController;
use App\ZhuChao\Buyer\Constant as BUYER_CONST;
use Cntysoft\Kernel\ConfigProxy;
/**
 * 系统的回接口
 */
class NotifyController extends AbstractController
{
	public function changyanGetAclAction()
	{
		try {
			$curUser = $this->getAppCaller()->call(
					  BUYER_CONST::MODULE_NAME, BUYER_CONST::APP_NAME, BUYER_CONST::APP_API_BUYER_ACL, 'getCurUser');
			if ($curUser) {
				$detail = $curUser->getProfile();
				$data = array(
					'is_login'	 => 1,
					'user'		 => array(
						'user_id'		 => $curUser->getId(),
						'nickname'		 => $curUser->getName() ? $curUser->getName() : $curUser->getPhone(),
						'img_url'		 => \Cntysoft\Kernel\get_image_cdn_url($detail->getAvatar(), 100, 100),
						'profile_url'	 => 'http://' . \Cntysoft\RT_SYS_SITE_NAME,
						'sign'			 => $this->sign(\Cntysoft\Kernel\get_image_cdn_url($detail->getAvatar(), 100, 100), $curUser->getName() ? $curUser->getName() : $curUser->getPhone(), 'http://' . \Cntysoft\RT_SYS_SITE_NAME, $curUser->getId())
					)
				);
				echo $_GET['callback'] . '(' . json_encode($data) . ')';
			}
		} catch (\Exception $exc) {
			$data = array(
				'is_login' => 0
			);
			echo $_GET['callback'] . '(' . json_encode($data) . ')';
		}
	}

	/**
	 * 生成changyan签名
	 * @param type $imgUrl
	 * @param type $nickname
	 * @param type $profileUrl
	 * @param type $isvUserId
	 * @return type
	 */
	private function sign($imgUrl, $nickname, $profileUrl, $isvUserId)
	{
		$mate = $this->getChangyanConfig();
		$toSign = "img_url=" . $imgUrl . "&nickname=" . $nickname . "&profile_url=" . $profileUrl . "&user_id=" . $isvUserId;
		$signature = base64_encode(hash_hmac("sha1", $toSign, $mate['appkey'], true));
		return $signature;
	}

	/**
	 * 获取changyanID和secret
	 * @return array
	 */
	private function getChangyanConfig()
	{
		$netCfg = ConfigProxy::getFrameworkConfig('Net');
		if (!isset($netCfg['changYan']) || !isset($netCfg['changYan']['appid']) || !isset($netCfg['changYan']['appkey'])) {
			$errorType = ErrorType::getInstance();
			Kernel\throw_exception(new Exception(
					  $errorType->msg('E_SDK_CONFIG_NOT_EXIST'), $errorType->code('E_SDK_CONFIG_NOT_EXIST')
			));
		}
		return $netCfg->changYan;
	}

}