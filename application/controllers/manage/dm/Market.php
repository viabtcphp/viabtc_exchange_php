<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 后台合约交易市场控制器
 */
class Market extends CI_Controller {


	/**
	 * 构造函数
	 */
	public function __construct(){

		parent::__construct();

		//检测管理员登陆
		$this->load->model('admin_model');
		$this->admin_model->checkLogin();

		//载入模型
		$this->load->model('market_model');
		$this->load->model('robot_model');
	}


	/**
	 * 交易市场页面
	 */
	public function index(){

		$marketListTemp = $this->market_model->getAllMarketList($this->config->item('dm_money_coin'));

		$marketList = FALSE;

		if ($marketListTemp && count($marketListTemp)) {
			
			foreach ($marketListTemp as $marketItem) {
				
				$marketList[$marketItem['market_money_symbol']][] = $marketItem;
			}
		}

		$this->load->model('coin_model');
		$coinList = $this->coin_model->getActiveCoinList();

		$data = array(

			'marketList' => $marketList,
			'coinList' => $coinList
		);

		$this->load->view('manage/dm/market_index', $data);
	}


	/**
	 * 获取单个交易市场的信息
	 */
	public function one(){

		if ($_POST) {

			$result = array(

				'status' 	=> FALSE,
				'message' 	=> '网络繁忙，请稍后再试',
				'market'	=> array()
			);
			
			$marketId = $this->input->post('market_id');

			if ($marketId) {

				$market = $this->market_model->one($marketId);

				if ($market) {

					$market['market_min_amount'] = floatval($market['market_min_amount']);
					$market['market_taker_fee'] = floatval($market['market_taker_fee']);
					$market['market_maker_fee'] = floatval($market['market_maker_fee']);
					$market['market_dm_min_amount'] = floatval($market['market_dm_min_amount']);
					$market['market_dm_fee'] = floatval($market['market_dm_fee']);
					$market['market_symbol'] = $market['market_stock_symbol'] . ' / ' . $market['market_money_symbol'];
					
					$result['status'] = TRUE;
					$result['message'] = '数据读取成功';
					$result['market'] = $market;
				}
			}

			echo json_encode($result, JSON_UNESCAPED_UNICODE);
		}
	}


	/**
	 * 编辑交易市场
	 */
	public function edit(){

		if ($_POST) {
			
			$result = array(

				'status' 	=> FALSE,
				'message' 	=> '网络繁忙，请稍后再试'
			);

			$market = $this->market_model->initUpdate($this->input->post());

			//尝试修改
			if ($this->market_model->update($market)) {
				
				$result['status'] = TRUE;
				$result['message'] = '修改成功';
			}

			echo json_encode($result, JSON_UNESCAPED_UNICODE);
		}
	}
}