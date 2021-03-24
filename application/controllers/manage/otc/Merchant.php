<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 后台用户控制器
 */
class Merchant extends CI_Controller {


	/**
	 * 每页数量
	 */
	private $pageSize = 20;

	private $otcPayTypeList = array();


	/**
	 * 构造函数
	 */
	public function __construct(){

		parent::__construct();

		//检测管理员登陆
		$this->load->model('admin_model');
		$this->admin_model->checkLogin();

		//载入模型
		$this->load->model('user_model');

		$this->otcPayTypeList = $this->config->item('otc_pay_type');
	}


	/**
	 * 用户页面
	 */
	public function index($pageIndex = 1){

		$userCount = $this->user_model->countMerchant();

		$pagingInfo = getPagingInfo($userCount, $pageIndex, $this->pageSize, $this->config->item('manage_page'), base_url('/manage/otc/merchant/index/'));

		$userList = $this->user_model->getAllMerchant($pagingInfo['pageindex'], $this->pageSize);

		$data = array(

			'pagingInfo' => $pagingInfo,
			'userList'	=> $userList,
			'otcPayTypeList' => $this->otcPayTypeList
		);

		$this->load->view('manage/otc/otc_merchant', $data);
	}


	public function login(){

		if ($_POST) {
			
			$user = $this->user_model->one($_POST['user_id']);

			$this->user_model->createLoginSession($user);
		}
	}


	/**
	 * 添加用户
	 */
	public function add(){

		if ($_POST) {
			
			$result = array(

				'status' 	=> FALSE,
				'message' 	=> '网络繁忙，请稍后再试'
			);

			$merchant = $this->input->post();

			$user = $this->user_model->getUserByUserName($merchant['user_name']);

			if ($user && count($user)) {

				if ($user['user_merchant']) {
					
					$result['message'] = '该用户已开通商户';
				}else{

					$user['user_contact'] = $merchant['user_contact'];
					$user['user_merchant'] = 1;
					$user['user_merchant_time'] = APP_TIME;
					$user['user_merchant_name'] = $merchant['user_merchant_name'];
					$user['user_merchant_pay'] = json_encode($merchant['user_merchant_pay'], JSON_UNESCAPED_UNICODE);
					$user['user_merchant_status'] = $merchant['user_merchant_status'];

					//尝试写入
					if ($this->user_model->update($user)) {
						
						$result['status'] = TRUE;
						$result['message'] = '添加成功';
					}
				}
			}else{

				$result['message'] = '用户名不存在';
			}

			echo json_encode($result, JSON_UNESCAPED_UNICODE);
		}
	}


	/**
	 * 编辑用户
	 */
	public function edit(){

		if ($_POST) {
			
			$result = array(

				'status' 	=> FALSE,
				'message' 	=> '网络繁忙，请稍后再试'
			);

			$user = $this->user_model->initUpdate($this->input->post());
			$user['user_merchant_pay'] = json_encode($user['user_merchant_pay'], JSON_UNESCAPED_UNICODE);

			//尝试修改
			if ($this->user_model->update($user)) {
				
				$result['status'] = TRUE;
				$result['message'] = '修改成功';
			}

			echo json_encode($result, JSON_UNESCAPED_UNICODE);
		}
	}


	/**
	 * 获取单个用户的信息
	 */
	public function one(){

		if ($_POST) {

			$result = array(

				'status' 	=> FALSE,
				'message' 	=> '网络繁忙，请稍后再试',
				'user'	=> array()
			);
			
			$userId = $this->input->post('user_id');

			if ($userId) {

				$user = $this->user_model->one($userId);

				if ($user) {

					$user['user_merchant_pay'] = json_decode($user['user_merchant_pay'], TRUE);
					$user['user_merchant_pay_count'] = count($user['user_merchant_pay']);
					
					$result['status'] = TRUE;
					$result['message'] = '数据读取成功';
					$result['user'] = $user;
				}
			}

			echo json_encode($result, JSON_UNESCAPED_UNICODE);
		}
	}


	/**
	 * 删除商户
	 */
	public function delete(){

		if ($_POST) {
			
			$result = array(

				'status' 	=> FALSE,
				'message' 	=> '网络繁忙，请稍后再试'
			);

			$user = array(

				'user_id' => $this->input->post('user_id'),
				'user_merchant' => 0
			);

			//尝试删除
			if ($this->user_model->update($user)) {
				
				$result['status'] = TRUE;
				$result['message'] = '解除成功';
			}

			echo json_encode($result, JSON_UNESCAPED_UNICODE);
		}
	}


	/**
	 * 用户资产页面
	 */
	public function user_asset($userId = 0){

		if ($userId > 0) {
			
			$user = $this->user_model->one($userId);

			//用户存在
			if ($user && is_array($user) && count($user)) {

				//资产信息容器
				$userAsset = $this->asset_model->getUserAsset($_SESSION['USER']['USER_ID']);
				
				$data = array(

					'user' => $user,
					'userAsset' => $userAsset
				);

				$this->load->view('manage/user/user_asset', $data);
			}
		}
	}
}