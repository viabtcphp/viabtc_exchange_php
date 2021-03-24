<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 前台币币交易控制器
 */
class Exchange extends MY_Controller {


	public function __construct(){

		parent::__construct();

		$this->load->model('market_model');
		$this->load->model('coin_model');
		$this->load->model('ves_model');
		$this->load->model('user_model');
		$this->load->model('asset_model');
        $this->load->model('article_model');
        $this->load->model('order_model');

		//设置资产计算保留的小数位精度
		bcscale($this->config->item('ex_asset_scale'));
	}


    //手机交易页面，限价市价切换识别
    public function select_trade_type(){

        if ($_POST && isset($_POST['mobile_trade_type']) && in_array($_POST['mobile_trade_type'], array('limit', 'market'))) {
            
            $_SESSION['mobile_trade_type'] = $_POST['mobile_trade_type'];
        }
    }


	//币币交易页面
    public function index($stock_symbol = FALSE, $money_symbol = FALSE){

        if (! isset($_SESSION['mobile_trade_type'])) {
            
            //默认限价
            $_SESSION['mobile_trade_type'] = 'market';
        }

    	$marketStatus = FALSE;

    	$stock_coin = FALSE;
    	$money_coin = FALSE;
    	$market = FALSE;

        $marketGroup = array();
        $marketSymbolList = array();

    	$marketList = $this->market_model->getAllActiveMarketList();

    	if ($stock_symbol === FALSE && $money_symbol === FALSE) {
    		
    		if ($marketList && count($marketList)) {
    			
    			$market = $marketList[0];
    			$marketStatus = TRUE;
    		}
    	}else{

    		if ($stock_symbol && $money_symbol) {
    			
    			$stock_coin = $this->coin_model->oneActiveCoinBySymbol(strtoupper($stock_symbol . ''));
    			$money_coin = $this->coin_model->oneActiveCoinBySymbol(strtoupper($money_symbol . ''));

    			if ($stock_coin && count($stock_coin) && $money_coin && count($money_coin)) {
    				
    				$market = array(

    					'market_stock_coin' => $stock_coin['coin_id'],
    					'market_money_coin' => $money_coin['coin_id']
    				);

    				$market = $this->market_model->oneExistsMarketByStockMoney($market);

    				if ($market && count($market)) {
    					
    					$marketStatus = TRUE;
    				}
    			}
    		}
    	}
	    
	    if ($marketStatus) {

	    	if ($marketList && count($marketList)) {
	    		
	    		foreach ($marketList as $marketItem) {
	    			
	    			$marketGroup[$marketItem['market_money_symbol']][] = $marketItem;
                    $marketSymbolList[] = $marketItem['market_stock_symbol'] . $marketItem['market_money_symbol'];
	    		}
	    	}
	    	
	    	$data = array(

	    		'market' => $market,
	    		'marketList' => $marketList,
	    		'marketGroup' => $marketGroup,
                'marketSymbolList' => $marketSymbolList
	    	);

            if (isset($_GET['mobile_kline']) && isset($_GET['kline_from']) && in_array($_GET['kline_from'], array('exchange', 'futures'))) {
                
                $this->load->view('mobile/exchange/kline', $data);
            }else{

                $this->load->view($this->viewPath . '/exchange/exchange', $data);
            }
	    }else{

	    	echo '<script>alert("' . lang('controller_exchange_index_1') . '");window.location.href="/";</script>';
	    }
    }


    public function trade(){

    	if ($_POST 
            && isset($_POST['type']) 
            && in_array($_POST['type'], array('sell', 'buy'))
            && isset($_POST['trade_type']) 
            && in_array($_POST['trade_type'], array('limit', 'market'))
            && isset($_POST['price']) 
            && isset($_POST['count']) 
            && is_numeric($_POST['count']) 
            && bccomp($_POST['count'], 0) > 0 
            && isset($_POST['market_stock']) 
            && isset($_POST['market_money'])
        ) {
    		
    		$this->user_model->checkLogin();

    		$user = $this->user_model->one($_SESSION['USER']['USER_ID']);

    		if ($user && count($user)) {
    			
    			$result = array(

    				'status' => FALSE,
    				'message' => lang('controller_exchange_trade_1')
    			);

    			if ($this->user_model->checkAuth($user)) {

                    $stock_coin = $this->coin_model->oneActiveCoinBySymbol(strtoupper($_POST['market_stock'] . ''));
                    $money_coin = $this->coin_model->oneActiveCoinBySymbol(strtoupper($_POST['market_money'] . ''));

                    if ($stock_coin && count($stock_coin) && $money_coin && count($money_coin)) {
                        
                        $market = array(

                            'market_stock_coin' => $stock_coin['coin_id'],
                            'market_money_coin' => $money_coin['coin_id']
                        );

                        $market = $this->market_model->oneExistsMarketByStockMoney($market);

                        if ($market && count($market)) {

                            $userAsset = $this->asset_model->getUserAsset($user['user_id']);

                            //限价交易
                            if ($_POST['trade_type'] == 'limit' && is_numeric($_POST['price']) && is_numeric($_POST['count'])) {

                                if (bccomp($_POST['count'], $market['market_min_amount'], intval($market['market_decimal'])) >= 0) {
                                    
                                    if ($_POST['type'] == 'buy') {
                                        
                                        if (bccomp($userAsset[$market['market_money_symbol']]['asset_active'], bcmul($_POST['price'], $_POST['count'], intval($market['market_decimal'])), intval($market['market_decimal'])) >= 0) {
                                            
                                            $ret = $this->ves_model->order_put_limit($user['user_id'], $market['market_stock_symbol'] . $market['market_money_symbol'], 2, $_POST['count'], $_POST['price'], $market['market_taker_fee'], $market['market_maker_fee'], 'web');

                                            if ($ret['code'] > 0) {
                                                
                                                $result['status'] = TRUE;
                                                $result['message'] = lang('controller_exchange_trade_2');
                                            }
                                        }else{

                                            $result['message'] = $market['market_money_symbol'] . lang('controller_exchange_trade_3');
                                        }
                                    }else{

                                        if (bccomp($userAsset[$market['market_stock_symbol']]['asset_active'], $_POST['count'], intval($market['market_decimal'])) >= 0) {
                                            
                                            $ret = $this->ves_model->order_put_limit($user['user_id'], $market['market_stock_symbol'] . $market['market_money_symbol'], 1, $_POST['count'], $_POST['price'], $market['market_taker_fee'], $market['market_maker_fee'], 'web');

                                            if ($ret['code'] > 0) {
                                                
                                                $result['status'] = TRUE;
                                                $result['message'] = lang('controller_exchange_trade_4');
                                            }
                                        }else{

                                            $result['message'] = $market['market_stock_symbol'] . lang('controller_exchange_trade_5');
                                        }
                                    }
                                }else{

                                    $result['message'] = lang('controller_exchange_trade_6') . $market['market_min_amount'] . ' ' . $market['market_stock_symbol'];
                                }
                            }

                            //市价交易
                            if ($_POST['trade_type'] == 'market' && is_numeric($_POST['count'])) {
                                
                                if ($_POST['type'] == 'buy') {
                                    
                                    $ret = $this->ves_model->order_put_market($user['user_id'], $market['market_stock_symbol'] . $market['market_money_symbol'], 2, $_POST['count'], $market['market_taker_fee'], 'web');

                                    if ($ret['code'] > 0) {
                                        
                                        $result['status'] = TRUE;
                                        $result['message'] = lang('controller_exchange_trade_7');
                                    }
                                }else{

                                    if (bccomp($userAsset[$market['market_stock_symbol']]['asset_active'], $_POST['count'], intval($market['market_decimal'])) >= 0) {
                                        
                                        $ret = $this->ves_model->order_put_market($user['user_id'], $market['market_stock_symbol'] . $market['market_money_symbol'], 1, $_POST['count'], $market['market_taker_fee'], 'web');

                                        if ($ret['code'] > 0) {
                                            
                                            $result['status'] = TRUE;
                                            $result['message'] = lang('controller_exchange_trade_8');
                                        }
                                    }else{

                                        $result['message'] = $market['market_stock_symbol'] . lang('controller_exchange_trade_9');
                                    }
                                }
                            }
                        }else{

                            $result['message'] = lang('controller_exchange_trade_10');
                        }
                    }
                }else{

                    $result['message'] = lang('controller_exchange_trade_11');
                }
    		}else{

    			$result['message'] = lang('controller_exchange_trade_12');
    		}

    		echo json_encode($result, JSON_UNESCAPED_UNICODE);
    	}
    }


    public function cancel(){

    	if ($_POST && isset($_POST['order']) && isset($_POST['market'])) {

    		if (is_numeric($_POST['order'])) {
    			
    			$result = array(

    				'status' => FALSE,
    				'message' => lang('controller_exchange_cancel_1')
    			);

    			$this->user_model->checkLogin();

    			$ret = $this->ves_model->order_cancel($_SESSION['USER']['USER_ID'], $_POST['market'], $_POST['order']);

    			if ($ret['code'] > 0 && $this->order_model->deleteDealByNo(array($_POST['order']))) {
    				
    				$result['status'] = TRUE;
    				$result['message'] = lang('controller_exchange_cancel_2');
    			}

    			echo json_encode($result, JSON_UNESCAPED_UNICODE);
    		}
    	}
    }
}
