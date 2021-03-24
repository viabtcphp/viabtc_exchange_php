<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 后台合约设置控制器
 */
class Dm extends CI_Controller {


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
		$this->load->model('admin_model');
		$this->admin_model->checkLogin();

		//载入模型
		$this->load->model('sysconfig_model');
		$this->load->model('dm_model');
		$this->load->model('ves_model');
		$this->load->model('user_model');
	}


	/**
	 * 合约配置页面
	 */
	public function index($pageIndex = 1){

		//读取系统设置
		$sysconfig = $this->sysconfig_model->getSysconfig();

		$data = array(

			'sysconfig' => $sysconfig
		);

		$this->load->view('manage/dm/dm', $data);
	}


	/**
	 * 更新系统设置
	 */
	public function edit(){

		if ($_POST) {
			
			$result = array(

				'status' 	=> FALSE,
				'message' 	=> '网络繁忙，请稍后再试'
			);

			$sysconfig = $this->input->post();

			//尝试更新
			if ($this->sysconfig_model->updateSysconfig($sysconfig)) {
				
				$result['status'] = TRUE;
				$result['message'] = '更新成功';
			}

			$_SESSION['SYSCONFIG'] = $this->sysconfig_model->getFormatSysconfig();

			echo json_encode($result, JSON_UNESCAPED_UNICODE);
		}
	}


	//挂起订单
	public function delegate($pageIndex = 1){

		$search = isset($_GET['search']) ? trim($_GET['search']) : '';
		$searchUserIdList = FALSE;

		if ($search != '') {
			
			$searchUserIdList = $this->user_model->searchUserIdList($search);
		}

		$dmCount = $this->dm_model->countDelegateDm($searchUserIdList);

		$pagingInfo = getPagingInfo($dmCount, $pageIndex, $this->pageSize, $this->config->item('manage_page'), base_url('/manage/dm/dm/delegate/'));

		$dmListTemp = $this->dm_model->getDelegateDm($pagingInfo['pageindex'], $this->pageSize, $searchUserIdList);
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
			'dmList'	=> $dmList,
			'search' => $search
		);

		$this->load->view('manage/dm/delegate', $data);
	}


	//持仓订单
	public function hold($pageIndex = 1){

		$search = isset($_GET['search']) ? trim($_GET['search']) : '';
		$searchUserIdList = FALSE;

		if ($search != '') {
			
			$searchUserIdList = $this->user_model->searchUserIdList($search);
		}

		$dmCount = $this->dm_model->countHoldDm($searchUserIdList);

		$pagingInfo = getPagingInfo($dmCount, $pageIndex, $this->pageSize, $this->config->item('manage_page'), base_url('/manage/dm/dm/hold/'));

		$dmListTemp = $this->dm_model->getHoldDm($pagingInfo['pageindex'], $this->pageSize, $searchUserIdList);
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
			'dmList'	=> $dmList,
			'search' => $search
		);

		$this->load->view('manage/dm/hold', $data);
	}


	public function pin(){

		if ($_POST) {

			$result = array(

				'status' 	=> FALSE,
				'message' 	=> '网络繁忙，请稍后再试'
			);
			
			$dm = $this->dm_model->one($_POST['dm_id']);

			if ($dm) {

				//获取合约对应的用户资产
				$userDmCoinAsset = $this->asset_model->getUserDmAsset($dm['dm_user'], $dm['dm_coin']);

				if ($userDmCoinAsset) {
					
					$dm['dm_close_future_price'] = $this->dm_model->computFutureClosePrice(

					    bcadd($userDmCoinAsset['asset_active'], $dm['dm_order_amount']),
					    $dm['dm_open_amount'],
					    $dm['dm_direction'],
					    $dm['dm_open_price']
					);

					if ($this->ves_model->put_price_target($dm['dm_coin_symbol'] . $this->config->item('dm_money_symbol'), $dm['dm_close_future_price'])) {
						
						$result['status'] = TRUE;
						$result['message'] = '操作成功';
					}
				}
			}

			echo json_encode($result, JSON_UNESCAPED_UNICODE);
		}
	}


	//历史订单
	public function history($pageIndex = 1){

		$search = isset($_GET['search']) ? trim($_GET['search']) : '';
		$searchUserIdList = FALSE;

		if ($search != '') {
			
			$searchUserIdList = $this->user_model->searchUserIdList($search);
		}

		$dmCount = $this->dm_model->countHistoryDm($searchUserIdList);

		$pagingInfo = getPagingInfo($dmCount, $pageIndex, $this->pageSize, $this->config->item('manage_page'), base_url('/manage/dm/dm/history/'));

		$dmListTemp = $this->dm_model->getHistoryDm($pagingInfo['pageindex'], $this->pageSize, $searchUserIdList);
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
			'dmList'	=> $dmList,
			'search' => $search
		);

		$this->load->view('manage/dm/history', $data);
	}
}