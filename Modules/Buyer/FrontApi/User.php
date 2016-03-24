<?php
/**
 * Cntysoft Cloud Software Team
 *
 * @author Arvin <cntyfeng@163.com>
 * @copyright  Copyright (c) 2010-2016 Cntysoft Technologies China Inc. <http://www.cntysoft.com>
 * @license    http://www.cntysoft.com/license/new-bsd     New BSD License
 */
namespace FrontApi;
use ZhuChao\Framework\OpenApi\AbstractScript;
use App\ZhuChao\Buyer\Constant as BUYER_CONST;  
use App\ZhuChao\Product\Constant as PRODUCT_CONST;
use App\ZhuChao\MessageMgr\Constant as MESSAGE_CONST;
use Cntysoft\Kernel;

/**
 * 主要是处理采购商相关的的Ajax调用
 * 
 * @package FrontApi
 */
class User extends AbstractScript
{
   /**
    * 采购商注册
    * <code>
    *    array(
    *       'phone' => 15522222222,
    *       'password' => 'dwadawdwadadawd',  加密之后的密码
    *       'smsCode' => 222222
    *    )
    * </code>
    * 
    * @param array $params
    * @return type
    */
   public function register(array $params)
   {
      $this->checkRequireFields($params, array('phone', 'password', 'smsCode'));
      
      return $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_ACL,
         'register',
         array($params['phone'], $params['password'], $params['smsCode'])
      );
   }
   
   /**
    * 采购商登陆的方法
    * 
    * @param array $params
    * @return boolean
    */
   public function login(array $params)
   {
      $this->checkRequireFields($params, array('key', 'password', 'remember'));
      
      if(isset($params['picCode'])){
         $this->checkPicCode(array('code' => $params['picCode'], BUYER_CONST::PIC_CODE_TYPE_LOGIN));
      }
      
      $type = $this->getLoginType($params['key']);
      
      return $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_ACL,
         'login',
         array($params['key'], $params['password'], $type, $params['remember'])
      );
   }
   
   /**
    * 用户注销
    * 
    * @return boolean
    */
   public function logout()
   {
      return $this->appCaller->call(
                      BUYER_CONST::MODULE_NAME, BUYER_CONST::APP_NAME, BUYER_CONST::APP_API_BUYER_ACL, 'logout', array()
      );
   }
   
   /**
    * 修改采购商的信息
    * 
    * @param array $params
    */
   public function updateBuyer(array $params)
   {
      $curUser = $this->getCurUser();
      Kernel\unset_array_values($params, array('id', 'buyerId', 'password', 'profileId', 'status', 'experience', 'level', 'point'));
      $cndServer = Kernel\get_image_cdn_server_url() .'/';
      $src = '@.src';
      
      if(count($params['avatar'])){
         $params['avatar'] = str_replace($src, '', str_replace($cndServer, '', $params['avatar']));
      }

      return $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_MGR,
         'updateBuyer',
         array($curUser->getId(), $params)
      );
   }
   
   /**
    * 添加一个收货地址
    * 
    * @param array $params
    * @return type
    */
   public function addAddress(array $params)
   {
      $this->checkRequireFields($params, array('username', 'phone', 'province', 'city', 'district', 'address', 'postCode', 'isDefault'));
      $curUser = $this->getCurUser();
      
      return $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_ADDRESS,
         'addAddress',
         array($curUser->getId(), $params)
      );
   }
   
   /**
    * 修改一个收货地址
    * 
    * @param array $params
    * @return type
    */
   public function updateAddress(array $params)
   {
      $this->checkRequireFields($params, array('id', 'username', 'phone', 'province', 'city', 'district', 'address', 'postCode', 'isDefault'));
      $curUser = $this->getCurUser();
      $id = (int)$params['id'];
      unset($params['id']);
      
      return $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_ADDRESS,
         'updateAddress',
         array($curUser->getId(), $id, $params)
      );
   }
   
   /**
    * 删除一条地址记录
    * 
    * @param array $params
    * @return type
    */
   public function deleteAddress(array $params)
   {
      $this->checkRequireFields($params, array('id'));
      $curUser = $this->getCurUser();
      
      return $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_ADDRESS,
         'deleteAddress',
         array($curUser->getId(), (int)$params['id'])
      );
   }
   /**
    * 获取地址信息
    * 
    * @param array $params
    * @return 
    */
   public function getAddress(array $params)
   {
      $this->checkRequireFields($params, array('id'));
      $curUser = $this->getCurUser();
      
      $address = $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_ADDRESS,
         'getAddressByBuyerAndId',
         array($curUser->getId(), (int)$params['id'])
      );
      $array = $address->toArray();
      Kernel\unset_array_values($array, array('id', 'buyerId', 'inputTime'));
      $array['postCode'] = $array['postCode'] ? $array['postCode'] : '';
      return $array;
   }
   
   /**
    * 设置一个地址为默认地址
    * 
    * @param array $params
    */
   public function setDefaultAddress(array $params)
   {
      $this->checkRequireFields($params, array('id'));
      $curUser = $this->getCurUser();
      
      return $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_ADDRESS,
         'setDefaultAddress',
         array($curUser->getId(), (int)$params['id'])
      );
   }
   
   /**
    * 发送手机短信
    * 
    * @param array $params
    * @return type
    */
   protected function sendSmsCode(array $params)
   {
      $this->checkRequireFields($params, array('phone', 'type'));
      
      return $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_ACL,
         'sendSmsCode',
         array($params['phone'], (int)$params['type'])
      );
   }
   
   /**
    * 找回密码的操作
    * 
    * @param array $params
    * <code>
    *    password 加密之后的密码
    * </code>
    * @return boolean
    */
   public function findPassword(array $params)
   {
      $this->checkRequireFields($params, array('phone', 'password','code'));
      
      return $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_ACL,
         'findPassword',
         array($params['phone'], $params['password'],$params['code'])
      );
   }
   
   /**
    * 用户中心修改用户的密码
    * 
    * @param array $params
    * @return 
    */
   public function resetPassword(array $params)
   {
      $this->checkRequireFields($params, array('oldPassword', 'newPassword'));
      
      return $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_ACL,
         'resetPassword',
         array($params['oldPassword'], $params['newPassword'])
      );
   }
   
   /**
    * 获取用户的登录方式
    * 
    * @param string $key
    * @return integer
    */
   protected function getLoginType($key)
   {
      if(preg_match('/^1[0-9]{10}$/', $key)){
         return BUYER_CONST::FRONT_USER_PHONE_LOGIN;
      }else{
         return BUYER_CONST::FRONT_USER_NAME_LOGIN;
      }
   }
   
   /**
    * 检查图片验证码是否正确
    * 
    * @param array $params
    * @return boolean
    */
   public function checkPicCode(array $params)
   {
      $this->checkRequireFields($params, array('code', 'type'));
      
      $flag = $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_ACL,
         'checkPicCode',
         array($params['code'], (int)$params['type'])
      );
      
      if($flag && isset($params['phone']) && $params['phone']){
         return $this->sendSmsCode(array('phone' => $params['phone'], 'type' => $params['type']));
      }
      
      return $flag;
   }
   
   /**
    * 删除选定的收藏商品
    * 
    * @param array $params
    */
   public function deleteCollects(array $params)
   {
      $this->checkRequireFields($params, array('ids'));
      $curUser = $this->getCurUser();
      $ids = $params['ids'];
      if(!is_array($params['ids'])){
         $ids = array($params['ids']);
      }
      
      $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_COLLECT,
         'deleteCollects',
         array($curUser->getId(), $ids)
      );
   }
   
   /**
    * 删除选定的关注企业
    * 
    * @param array $params
    */
   public function deleteFollows(array $params)
   {
      $this->checkRequireFields($params, array('ids'));
      $curUser = $this->getCurUser();
      $ids = $params['ids'];
      if(!is_array($params['ids'])){
         $ids = array($params['ids']);
      }
      
      $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_FOLLOW,
         'deleteFollows',
         array($curUser->getId(), $ids)
      );
   }
   
   /**
    * 检查手机号码是否存在
    * 
    * @param array $params
    * @return type
    */
   public function checkPhoneExist(array $params)
   {
      $this->checkRequireFields($params, array('phone'));
      return $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,  
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_MGR,
         'checkPhoneExist',
         array($params['phone'])
      );
   }
   
   /**
    * 检测用户名是否存在
    * 
    * @param array $params
    * @return type
    */
   public function checkNameExist(array $params)
   {
      $this->checkRequireFields($params, array('name'));
      
      return $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_MGR,
         'checkNameExist',
         array($params['name'])
      );
   }
   
   /**
    * 获取联系方式
    * 
    * @param array $params
    * @return array
    */
   public function getLinker(array $params)
   {
      $this->checkRequireFields($params, array('number'));
      
      $product = $this->appCaller->call(
         PRODUCT_CONST::MODULE_NAME,
         PRODUCT_CONST::APP_NAME,
         PRODUCT_CONST::APP_API_PRODUCT_MGR,
         'getProductByNumber',
         array($params['number'])
      );
      
      if(!$product){
         $errorType = new ErrorType();
         Kernel\throw_exception(new Exception($errorType->msg('E_PRODUCT_NOT_EXIST'), $errorType->code('E_PRODUCT_NOT_EXIST')));
      }
      $provider = $product->getProvider();
      $profile = $provider->getProfile();
      
      return array(
         'name' => $profile->getRealName().($profile->getSex() ? ' 先生' : ' 女士'),
         'phone' => $profile->getShowPhone()
      );
   }
   /**
    * 获取当前登陆的会员信息
    * 
    * @return type
    */
   protected function getCurUser()
   {
      return $this->appCaller->call(
         BUYER_CONST::MODULE_NAME,
         BUYER_CONST::APP_NAME,
         BUYER_CONST::APP_API_BUYER_ACL,
         'getCurUser'
      );
   }
   
   /**
	 * 添加询价信息
	 * @param array $params
	 * @return type
	 */
	public function addXunjiadan(array $params)
	{
		$this->checkRequireFields($params, array('number','content'));
		$goodsInfo = $this->appCaller->call(
				  PRODUCT_CONST::MODULE_NAME, 
				  PRODUCT_CONST::APP_NAME, 
				  PRODUCT_CONST::APP_API_PRODUCT_MGR, 
				  'getProductByNumber', array($params['number']));
		$provider = $goodsInfo->getProvider();
		$curUser = $this->getCurUser();
		$data = array(
			'gid' => $goodsInfo->getId(),
			'uid' => $curUser->getId(),
			'providerId' => $provider->getId(),
			'expireTime' => 30,
			'content' => $params['content']
		);
		return $this->appCaller->call(
				  MESSAGE_CONST::MODULE_NAME, 
				  MESSAGE_CONST::APP_NAME, 
				  MESSAGE_CONST::APP_API_OFFER, 
				  'addInquiry', array($data));
	}
   
   /**
    * 获取询价单列表
    * 
    * @param array $params
    * @return type
    */
   public function getXunJiaList(array $params)
   {
      $curUser = $this->getCurUser();
      $this->checkRequireFields($params, array('limit', 'page'));
      $limit = (int)$params['limit'];
      if($params['page'] > 0){
         $offset = ((int)$params['page'] - 1) * $limit;
      }else{
         $offset = 0;
      }
      
      
      $list = $this->appCaller->call(
         MESSAGE_CONST::MODULE_NAME,
         MESSAGE_CONST::APP_NAME,
         MESSAGE_CONST::APP_API_OFFER,
         'getInquiryList',
         array(array('uid='.$curUser->getId()), false, 'id DESC', $limit, $offset)
      );
      
      $ret = array();
      foreach($list as $one){
         $product = $one->getProduct();
         $offer = $one->getOffer();
         
         $item = array(
            'url' => '/quotation/'.$one->getId().'.html',
            'time' => date('Y-m-d H:i:s', $one->getInputTime()),
            'productName' => $product->getBrand() . ' ' . $product->getTitle() . ' ' . $product->getDescription(),
            'status' => $offer ? '报价结束' : '报价进行中',
            'number' => $offer ? 1 : 0
         );
         
         array_push($ret, $item);
      }
      
      return $ret;
   }
}