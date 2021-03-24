<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 前台合约交易控制器
 */
class Dm extends MY_Controller {


	public function __construct(){

		parent::__construct();

		$this->load->model('market_model');
		$this->load->model('coin_model');
		$this->load->model('ves_model');
		$this->load->model('user_model');
		$this->load->model('asset_model');
        $this->load->model('dm_model');
        $this->load->model('article_model');

		//设置资产计算保留的小数位精度
		bcscale($this->config->item('ex_asset_scale'));
	}


    //手机交易页面，限价市价切换识别
    public function select_dm_type(){

        if ($_POST && isset($_POST['mobile_dm_type']) && in_array($_POST['mobile_dm_type'], array('limit', 'market'))) {
            
            $_SESSION['mobile_dm_type'] = $_POST['mobile_dm_type'];
        }
    }


	//合约交易页面
    public function index($stock_symbol = FALSE){

        if (! isset($_SESSION['mobile_dm_type'])) {
            
            //默认限价
            $_SESSION['mobile_dm_type'] = 'market';
        }

    	$marketStatus = FALSE;

    	$stock_coin = FALSE;
    	$market = FALSE;

        $marketGroup = array();
        $marketSymbolList = array();

    	$marketList = $this->market_model->getAllActiveDmMarketList();

    	if ($stock_symbol === FALSE) {
    		
    		if ($marketList && count($marketList)) {
    			
    			$market = $marketList[0];
    			$marketStatus = TRUE;
    		}
    	}else{

    		$stock_coin = $this->coin_model->oneActiveCoinBySymbol(strtoupper($stock_symbol . ''));

            if ($stock_coin && count($stock_coin)) {

                $market = $this->market_model->oneDmMarketByStock($stock_coin['coin_id']);

                if ($market && count($market)) {
                    
                    $marketStatus = TRUE;
                }
            }
    	}
	    
	    if ($marketStatus) {

	    	if ($marketList && count($marketList)) {
	    		
	    		foreach ($marketList as $marketItem) {
	    			
                    $marketSymbolList[] = $marketItem['market_stock_symbol'] . $marketItem['market_money_symbol'];
	    		}
	    	}

            $multipleList = explode(',', $_SESSION['SYSCONFIG']['sysconfig_dm_rate_list']);
	    	
	    	$data = array(

	    		'market' => $market,
	    		'marketList' => $marketList,
                'marketSymbolList' => $marketSymbolList,
                'multipleList' => $multipleList
	    	);

	    	$this->load->view($this->viewPath . '/exchange/dm', $data);
	    }else{

	    	echo '<script>alert("' . lang('controller_dm_index_1') . '");window.location.href="/";</script>';
	    }
    }


    //合约下单
    public function trade(){

        if ($_POST 
            && isset($_POST['type']) 
            && in_array($_POST['type'], array('sell', 'buy')) 
            && isset($_POST['price']) 
            && isset($_POST['count']) 
            && is_numeric($_POST['count']) 
            && bccomp($_POST['count'], 0) > 0 
            && isset($_POST['multiple']) 
            && isset($_POST['coin']) 
            && isset($_POST['trade_type']) 
            && in_array($_POST['trade_type'], array('limit', 'market'))
        ) {

            $result = array(

                'status' => FALSE,
                'message' => lang('controller_dm_trade_1')
            );

            $multipleList = explode(',', $_SESSION['SYSCONFIG']['sysconfig_dm_rate_list']);

            $this->user_model->checkLogin();

            $user = $this->user_model->one($_SESSION['USER']['USER_ID']);

            if ($user && count($user) && in_array($_POST['multiple'], $multipleList)) {
                
                if ($this->user_model->checkAuth($user)) {
                    
                    $coin = $this->coin_model->oneActiveCoinBySymbol(strtoupper($_POST['coin'] . ''));

                    if ($coin && count($coin)) {
                        
                        $market = array(

                            'market_stock_coin' => $coin['coin_id'],
                            'market_money_coin' => $this->config->item('dm_money_coin')
                        );

                        $market = $this->market_model->oneExistsMarketByStockMoney($market);

                        if ($market && count($market) && $market['market_dm_status'] == 1) {

                            if (bccomp($_POST['count'], $market['market_dm_min_amount']) >= 0) {
                                
                                $coinAsset = $this->asset_model->getUserDmAsset($user['user_id'], $coin['coin_id']);

                                //检测可用余额
                                if (bccomp($coinAsset['asset_active'], $_POST['count']) >= 0) {
                                    
                                    //检测价格合法
                                    $priceCheck = TRUE;

                                    if ($_POST['trade_type'] == 'market') {
                                        
                                        //市价交易，获取最新价
                                        $ret = $this->ves_model->market_last($market['market_stock_symbol'] . $market['market_money_symbol']);

                                        if ($ret && isset($ret['code']) && $ret['code'] > 0) {
                                            
                                            $_POST['price'] = $ret['data'];
                                        }else{

                                            $priceCheck = FALSE;
                                        }
                                    }else{

                                        //限价交易，检测价格
                                        if (! (is_numeric($_POST['price']) && bccomp($_POST['price'], 0) > 0)) {
                                            
                                            $priceCheck = FALSE;
                                        }
                                    }

                                    if ($priceCheck) {
                                        
                                        $fee = bcmul(bcmul($_POST['count'], $_SESSION['SYSCONFIG']['sysconfig_dm_fee_rate']), $_POST['multiple']);
                                        $margin = bcsub($_POST['count'], $fee);

                                        //构建合约订单对象
                                        $dm = $this->dm_model->fieldsArray;
                                        $dm['dm_user'] = $user['user_id'];
                                        $dm['dm_coin'] = $market['market_stock_coin'];
                                        $dm['dm_market'] = $market['market_id'];
                                        $dm['dm_order_amount'] = $_POST['count'];
                                        $dm['dm_open_price'] = $_POST['price'];
                                        $dm['dm_trade_type'] = $_POST['trade_type'] == 'market' ? 1 : 2;
                                        $dm['dm_direction'] = $_POST['type'] == 'sell' ? 0 : 1;
                                        $dm['dm_multiple'] = $_POST['multiple'];
                                        $dm['dm_margin'] = $margin;
                                        $dm['dm_fee'] = $fee;
                                        $dm['dm_status'] = 1;
                                        $dm['dm_last_check_time'] = APP_TIME - 1;

                                        if ($this->dm_model->insert($dm)) {
                                            
                                            $result['status'] = TRUE;
                                            $result['message'] = lang('controller_dm_trade_2');
                                        }
                                    }
                                }else{

                                    $result['message'] = lang('controller_dm_trade_3');
                                }
                            }else{

                                $result['message'] = lang('controller_dm_trade_6') . $market['market_dm_min_amount'] . ' ' . $market['market_stock_symbol'];
                            }
                        }
                    }
                }else{

                    $result['message'] = lang('controller_dm_trade_4');
                }
            }else{

                $result['message'] = lang('controller_dm_trade_5');
            }

            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        }
    }


    //取消未开仓的合约委托
    public function cancel(){

    	if ($_POST && isset($_POST['order']) && is_numeric($_POST['order']) && isset($_SESSION['USER'])) {

            $result = array(

                'status' => FALSE,
                'message' => lang('controller_dm_cancel_1')
            );

    		$dm = $this->dm_model->one($_POST['order']);

            //校验合约用户
            if ($dm && $dm['dm_user'] == $_SESSION['USER']['USER_ID']) {
                
                if ($dm['dm_open'] == 0) {
                    
                    if ($this->dm_model->cancelDm($dm)) {
                        
                        $result['status'] = TRUE;
                        $result['message'] = lang('controller_dm_cancel_2');
                    }
                }else{

                    $result['message'] = lang('controller_dm_cancel_3');
                }
            }

            echo json_encode($result, JSON_UNESCAPED_UNICODE);
    	}
    }


    //合约平仓
    public function close(){

        if ($_POST && isset($_POST['order']) && is_numeric($_POST['order']) && isset($_SESSION['USER'])) {

            $result = array(

                'status' => FALSE,
                'message' => lang('controller_dm_close_1')
            );

            $dm = $this->dm_model->one($_POST['order']);

            //校验合约用户
            if ($dm && $dm['dm_user'] == $_SESSION['USER']['USER_ID']) {
                
                if ($dm['dm_close'] == 0) {
                    
                    if ($this->dm_model->closeDm($dm)) {
                        
                        $result['status'] = TRUE;
                        $result['message'] = lang('controller_dm_close_2');
                    }
                }else{

                    $result['message'] = lang('controller_dm_close_3');
                }
            }

            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        }
    }


    //合约资产及订单信息
    public function info(){

        if ($_POST && isset($_POST['coin']) && isset($_SESSION['USER'])) {

            $coin = $this->coin_model->oneActiveCoinBySymbol(strtoupper($_POST['coin'] . ''));

            if ($coin && count($coin)) {
                
                $market = array(

                    'market_stock_coin' => $coin['coin_id'],
                    'market_money_coin' => $this->config->item('dm_money_coin')
                );

                $market = $this->market_model->oneExistsMarketByStockMoney($market);

                if ($market && count($market) && $market['market_dm_status'] == 1) {

                    $result = array(

                        'status' => TRUE,
                        'message' => lang('controller_dm_info_1'),
                        'data' => array(

                            'total_info' => array(

                                'asset_total' => '0.00000000',
                                'asset_active' => '0.00000000',
                                'asset_frozen' => '0.00000000',
                                'asset_pledge' => '0.00000000',
                                'total_profit' => '0.00000000',
                                'asset_pledge_rate' => '--'
                            ),
                            'hold_order' => FALSE,
                            'delegate_order' => FALSE,
                            'history_order' => FALSE
                        )
                    );
                    
                    $coinAsset = $this->asset_model->getUserDmAsset($_SESSION['USER']['USER_ID'], $coin['coin_id']);

                    $result['data']['total_info']['asset_total'] = $coinAsset['asset_total'];
                    $result['data']['total_info']['asset_active'] = $coinAsset['asset_active'];

                    $userDmList = $this->dm_model->getUserDm($_SESSION['USER']['USER_ID'], $coin['coin_id']);

                    if ($userDmList && count($userDmList)) {

                        $holdCountSum = 0;
                        
                        foreach ($userDmList as $dmItem) {

                            //正在持仓
                            if ($dmItem['dm_open'] == 1 && $dmItem['dm_close'] == 0 && $dmItem['dm_status'] == 1) {

                                $result['data']['total_info']['total_profit'] = bcadd($result['data']['total_info']['total_profit'], $dmItem['dm_profit']);
                                $result['data']['total_info']['asset_pledge'] = bcadd($result['data']['total_info']['asset_pledge'], $dmItem['dm_margin']);

                                $result['data']['hold_order'][] = array(

                                    'time' => date('m/d H:i:s', $dmItem['dm_open_time']),
                                    'direction' => $dmItem['dm_direction'] == 1 ? lang('controller_dm_info_2') : lang('controller_dm_info_3'),
                                    'direction_class' => $dmItem['dm_direction'] == 1 ? 'buy' : 'sell',
                                    'multiple' => $dmItem['dm_multiple'] . '×',
                                    'open_count' => floatval($dmItem['dm_open_amount']),
                                    'hold_count' => floatval($dmItem['dm_hold_amount']),
                                    'open_price' => floatval($dmItem['dm_open_price']),
                                    'margin' => floatval($dmItem['dm_margin']),
                                    'fee' => floatval($dmItem['dm_fee']),
                                    'profit' => bccomp($dmItem['dm_profit'], 0) > 0 ? ('+' . $dmItem['dm_profit']) : $dmItem['dm_profit'],
                                    'profit_class' => bccomp($dmItem['dm_profit'], 0) >= 0 ? 'buy' : 'sell',
                                    'future_price' => $dmItem['dm_close_future_price'],
                                    'order' => $dmItem['dm_id']
                                );

                                $holdCountSum = bcadd($holdCountSum, $dmItem['dm_order_amount']);
                            }

                            //正在委托订单
                            if ($dmItem['dm_open'] == 0 && $dmItem['dm_close'] == 0 && $dmItem['dm_status'] == 1) {
                                
                                $result['data']['total_info']['asset_frozen'] = bcadd($result['data']['total_info']['asset_frozen'], $dmItem['dm_order_amount']);

                                $result['data']['delegate_order'][] = array(

                                    'time' => date('m/d H:i:s', $dmItem['dm_order_time']),
                                    'direction' => $dmItem['dm_direction'] == 1 ? lang('controller_dm_info_4') : lang('controller_dm_info_5'),
                                    'direction_class' => $dmItem['dm_direction'] == 1 ? 'buy' : 'sell',
                                    'multiple' => $dmItem['dm_multiple'] . '×',
                                    'type' => $dmItem['dm_trade_type'] == 1 ? lang('controller_dm_info_6') : lang('controller_dm_info_7'),
                                    'price' => floatval($dmItem['dm_open_price']),
                                    'count' => floatval($dmItem['dm_order_amount']),
                                    'order' => $dmItem['dm_id']
                                );
                            }

                            //历史订单
                            //已平仓或已取消的订单
                            if ($dmItem['dm_close'] == 1 || $dmItem['dm_status'] == 0) {
                                
                                $result['data']['history_order'][] = array(

                                    'delegate_time' => date('m/d H:i:s', $dmItem['dm_order_time']),
                                    'open_time' => $dmItem['dm_open_time'] > 0 ? date('m/d H:i:s', $dmItem['dm_open_time']) : '--',
                                    'direction' => $dmItem['dm_direction'] == 1 ? lang('controller_dm_info_8') : lang('controller_dm_info_9'),
                                    'direction_class' => $dmItem['dm_direction'] == 1 ? 'buy' : 'sell',
                                    'multiple' => $dmItem['dm_multiple'] . '×',
                                    'type' => $dmItem['dm_trade_type'] == 1 ? lang('controller_dm_info_10') : lang('controller_dm_info_11'),
                                    'open_count' => $dmItem['dm_open'] == 1 ? floatval($dmItem['dm_open_amount']) : '--',
                                    'open_price' => $dmItem['dm_open'] == 1 ? floatval($dmItem['dm_open_price']) : '--',
                                    'fee' => $dmItem['dm_open'] == 1 ? floatval($dmItem['dm_fee']) : '--',
                                    'profit' => $dmItem['dm_open'] == 1 ? (bccomp($dmItem['dm_profit'], 0) > 0 ? ('+' . $dmItem['dm_profit']) : $dmItem['dm_profit']) : '--',
                                    'profit_class' => $dmItem['dm_open'] == 1 ? (bccomp($dmItem['dm_profit'], 0) >= 0 ? 'buy' : 'sell') : '',
                                    'status' => $dmItem['dm_status'] == 1 ? ($dmItem['dm_close_type'] == 1 ? lang('controller_dm_info_12') : lang('controller_dm_info_13')) : lang('controller_dm_info_14'),
                                    'price' => $dmItem['dm_open_price'],
                                    'hold' => $dmItem['dm_status'] == 1 ? floatval($dmItem['dm_hold_amount']) : '--',
                                    'close_price' => $dmItem['dm_status'] == 1 ? floatval($dmItem['dm_close_price']) : '--',
                                    'close_time' => $dmItem['dm_status'] == 1 ? date('Y/m/d H:i:s', $dmItem['dm_close_time']) : '--'
                                );
                            }
                        }

                        if (bccomp($result['data']['total_info']['asset_pledge'], 0) > 0 && bccomp($holdCountSum, 0) >= 0) {
                            
                            //担保资产率 = 合约可用余额 / 所有正在持仓的合约保证金
                            $result['data']['total_info']['asset_pledge_rate'] = bcmul(bcdiv(bcadd($result['data']['total_info']['asset_active'], $result['data']['total_info']['asset_pledge']), $holdCountSum), 100, 2) . ' %';
                        }
                    }

                    echo json_encode($result, JSON_UNESCAPED_UNICODE);
                }
            }
        }
    }
}
