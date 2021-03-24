<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 后台用户控制器
 */
class User extends CI_Controller {


	/**
	 * 每页数量
	 */
	private $pageSize = 20;


	/**
	 * 构造函数
	 */
	public function __construct(){

		parent::__construct();

		//检测管理员登陆
		$this->load->model('user_model');
		$this->user_model->checkLogin(FALSE, TRUE);

		//载入模型
		$this->load->model('asset_model');
		$this->load->model('coin_model');
		$this->load->model('recharge_model');
		$this->load->model('withdraw_model');
		$this->load->model('dm_model');
	}


	/**
	 * 用户页面
	 */
	public function index($pageIndex = 1){

		$userCount = $_SESSION['USER']['USER_INVITE_COUNT'];

		$pagingInfo = getPagingInfo($userCount, $pageIndex, $this->pageSize, $this->config->item('manage_page'), base_url('/agent/user/user/index/'));

		$userList = $this->user_model->getUserInviteList($_SESSION['USER']['USER_ID'], $pagingInfo['pageindex'], $this->pageSize);

		$data = array(

			'pagingInfo' => $pagingInfo,
			'userList'	=> $userList
		);

		$this->load->view('agent/user/user_index', $data);
	}


	/**
	 * 用户资产页面
	 */
	public function user_asset($userId = 0, $plateId = 1){

		if ($userId > 0) {
			
			$user = $this->user_model->one($userId);

			//用户存在
			if ($user && is_array($user) && count($user) && $user['user_parent'] == $_SESSION['USER']['USER_ID']) {

				//资产信息容器
				$userAsset = $this->asset_model->getUserAsset($userId);
				$userDmAsset = $this->asset_model->getUserDmAsset($userId);
				
				$data = array(

					'user' => $user,
					'userAsset' => $userAsset,
					'userDmAsset' => $userDmAsset,
					'plateId' => $plateId
				);

				$this->load->view('agent/user/user_asset', $data);
			}
		}
	}


	/**
	 * 用户充值记录页面
	 */
	public function user_recharge($userId = 0, $pageIndex = 1){

		if ($userId > 0) {
			
			$user = $this->user_model->one($userId);

			//用户存在
			if ($user && is_array($user) && count($user) && $user['user_parent'] == $_SESSION['USER']['USER_ID']) {

				$rechargeCount = $this->recharge_model->countUserRecharge($userId);

				$pagingInfo = getPagingInfo($rechargeCount, $pageIndex, $this->pageSize, $this->config->item('manage_page'), base_url('/agent/user/user/user_recharge/' . $userId . '/'));

				$rechargeList = $this->recharge_model->getUserRecharge($pagingInfo['pageindex'], $this->pageSize, $userId);

				$data = array(

					'pagingInfo' => $pagingInfo,
					'rechargeList'	=> $rechargeList
				);

				$this->load->view('agent/user/user_recharge', $data);
			}
		}
	}


	/**
	 * 用户提现记录页面
	 */
	public function user_withdraw($userId = 0, $pageIndex = 1){

		if ($userId > 0) {
			
			$user = $this->user_model->one($userId);

			//用户存在
			if ($user && is_array($user) && count($user) && $user['user_parent'] == $_SESSION['USER']['USER_ID']) {

				$withdrawCount = $this->withdraw_model->countUserWithdraw($userId);

				$pagingInfo = getPagingInfo($withdrawCount, $pageIndex, $this->pageSize, $this->config->item('manage_page'), base_url('/agent/user/user/user_withdraw/' . $userId . '/'));

				$withdrawList = $this->withdraw_model->getUserWithdraw($pagingInfo['pageindex'], $this->pageSize, $userId);

				$data = array(

					'pagingInfo' => $pagingInfo,
					'withdrawList'	=> $withdrawList
				);

				$this->load->view('agent/user/user_withdraw', $data);
			}
		}
	}


	/**
	 * 用户提现记录页面
	 */
	public function user_dm($userId = 0, $pageIndex = 1){

		if ($userId > 0) {
			
			$user = $this->user_model->one($userId);

			//用户存在
			if ($user && is_array($user) && count($user) && $user['user_parent'] == $_SESSION['USER']['USER_ID']) {

				$dmCount = $this->dm_model->countUserDm($userId);

				$pagingInfo = getPagingInfo($dmCount, $pageIndex, $this->pageSize, $this->config->item('manage_page'), base_url('/agent/user/user/user_dm/' . $userId . '/'));

				$dmListTemp = $this->dm_model->getUserDm($userId, FALSE, FALSE, FALSE, $pagingInfo['pageindex'], $this->pageSize);
				$dmList = array();
				$coinPrice = array();

				if ($dmListTemp && count($dmListTemp)) {
					
					foreach ($dmListTemp as $dm) {

						if (isset($coinPrice[$dm['dm_coin_symbol']])) {
							
							$dm['current_price'] = $coinPrice[$dm['dm_coin_symbol']];
						}else{

							$ret = $this->ves_model->market_last($dm['dm_coin_symbol'] . $this->config->item('dm_money_symbol'));

							if ($ret && isset($ret['code']) && $ret['code'] > 0) {
								
								$dm['current_price'] = $ret['data'];
								$coinPrice[$dm['dm_coin_symbol']] = $ret['data'];
							}
						}

						$dmList[] = $dm;
					}
				}

				$data = array(

					'pagingInfo' => $pagingInfo,
					'dmList'	=> $dmList
				);

				$this->load->view('agent/user/user_dm', $data);
			}
		}
	}
}