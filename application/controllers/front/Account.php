<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 前台用户页面控制器
 */
class Account extends MY_Controller {


    //资产帐户映射
    public $assetMap = array(

        'exchange' => 1,
        'futures' => 4
    );


	/**
	 * 构造函数，初始化
	 */
	public function __construct(){

		parent::__construct();

		//载入模型
		$this->load->model('user_model');
		$this->load->model('email_model');
        $this->load->model('coin_model');
        $this->load->model('asset_model');
        $this->load->model('ves_model');
        $this->load->model('recharge_model');
        $this->load->model('withdraw_model');
        $this->load->model('wallet_model');
        $this->load->model('asset_log_model');
        $this->load->model('article_model');

		//用户中心,除了注册和登陆,其它都需要验证登陆状态
		if ($this->uri->segment(2) != 'login' && $this->uri->segment(2) != 'register' && $this->uri->segment(2) != 'forgot' && $this->uri->segment(2) != 'core_auth') {
			
			$this->user_model->checkLogin();
		}
	}


    public function index(){

        $user = $this->user_model->one($_SESSION['USER']['USER_ID']);

        if ($user) {
            
            $data = array(

                'user' => $user
            );

            $this->load->view($this->viewPath . '/account/account', $data);
        }else{

            echo '<script>alert("' . lang('controller_account_index_1') . '");window.location.href="/";</script>';
        }
    }


    public function core_auth(){

        if (isset($_SERVER['HTTP_AUTHORIZATION']) && $_SERVER['HTTP_AUTHORIZATION'] != '') {

            $result = array(

                'code' => 1,
                'message' => 'error'
            );
            
            $token = $_SERVER['HTTP_AUTHORIZATION'];

            $user = $this->user_model->oneUserByToken($token);

            if ($user && count($user)) {
                
                $result['code'] = 0;
                $result['message'] = 'success';
                $result['data'] = array(

                    'user_id' => intval($user['user_id'])
                );
            }

            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        }
    }


    //绑定手机
    public function phone(){

        $user = $this->user_model->one($_SESSION['USER']['USER_ID']);

        if ($_POST) {

            if (isset($_POST['user_phone']) && $_POST['user_phone'] != '' && isset($_POST['user_phone_area']) && $_POST['user_phone_area'] != '' && isset($_POST['validate']) && $_POST['validate'] != '' && isset($_POST['user_ex_password']) && $_POST['user_ex_password'] != '') {
                
                $result = array(

                    'status' => FALSE,
                    'message' => lang('controller_account_phone_1')
                );

                if ($this->user_model->checkSmsValidate($_POST['validate'])) {
                    
                    if ($user['user_ex_password'] == pwd_encode($_POST['user_ex_password'])) {
                        
                        $user['user_phone'] = $_POST['user_phone'];
                        $user['user_phone_area'] = $_POST['user_phone_area'];

                        $existPhone = $this->user_model->oneUserByPhone($user);

                        if ($existPhone && count($existPhone)) {
                            
                            $result['message'] = lang('controller_account_phone_2');
                        }else{

                            if ($this->user_model->update($user)) {
                                
                                $result['status'] = TRUE;
                                $result['message'] = lang('controller_account_phone_3');
                            }
                        }
                    }else{

                        $result['message'] = lang('controller_account_phone_4');
                    }
                }else{

                    $result['message'] = lang('controller_account_phone_5');
                }

                echo json_encode($result, JSON_UNESCAPED_UNICODE);
            }
        }else{

            $defaultAreaCode = $this->config->item('phone_area_code_default');

            if ($user['user_phone'] != '') {
                
                $defaultAreaCode = $user['user_phone_area'];
            }

            $data = array(

                'user' => $user,
                'defaultAreaCode' => $defaultAreaCode
            );

            $this->load->view($this->viewPath . '/account/phone', $data);
        }
    }


    //绑定邮箱
    public function email(){

        $user = $this->user_model->one($_SESSION['USER']['USER_ID']);

        if ($_POST) {

            if (isset($_POST['user_email']) && $_POST['user_email'] != '' && isset($_POST['validate']) && $_POST['validate'] != '' && isset($_POST['user_ex_password']) && $_POST['user_ex_password'] != '') {
                
                $result = array(

                    'status' => FALSE,
                    'message' => lang('controller_account_email_1')
                );

                if (checkEmailFomat($_POST['user_email'])) {

                    if ($this->user_model->checkEmailValidate($_POST['validate'])) {
                        
                        if ($user['user_ex_password'] == pwd_encode($_POST['user_ex_password'])) {
                            
                            $user['user_email'] = $_POST['user_email'];

                            $existEmail = $this->user_model->oneUserByEmail($user);

                            if ($existEmail && count($existEmail)) {
                                
                                $result['message'] = lang('controller_account_email_2');
                            }else{

                                if ($this->user_model->update($user)) {
                                    
                                    $result['status'] = TRUE;
                                    $result['message'] = lang('controller_account_email_3');
                                }
                            }
                        }else{

                            $result['message'] = lang('controller_account_email_4');
                        }
                    }else{

                        $result['message'] = lang('controller_account_email_5');
                    }
                }else{

                    $result['message'] = lang('controller_account_email_6');
                }

                echo json_encode($result, JSON_UNESCAPED_UNICODE);
            }
        }else{

            $data = array(

                'user' => $user
            );

            $this->load->view($this->viewPath . '/account/email', $data);
        }
    }


    //资产
    public function asset(){

        $userAssetUsdTotal = '0.00';

        $userAsset = $this->asset_model->getUserAsset($_SESSION['USER']['USER_ID']);

        //计算并统计资产折合
        foreach ($userAsset as $key => $assetItem) {
            
            $userAsset[$key]['asset_usd'] = bcmul($assetItem['asset_total'], $assetItem['coin_usd'], 2);
            $userAssetUsdTotal = bcadd($userAssetUsdTotal, $userAsset[$key]['asset_usd'], 2);
        }

        $data = array(

            'current_page' => 'asset',
            'userAsset' => $userAsset,
            'userAssetUsdTotal' => $userAssetUsdTotal
        );

        $this->load->view($this->viewPath . '/account/asset', $data);
    }


    //合约资产
    public function asset_dm(){

        $userAssetUsdTotal = '0.00';

        //计算并统计资产折合
        $coinList = $this->coin_model->get();
        $coinList = array_column($coinList, NULL, 'coin_id');
        $userDmAsset = $this->asset_model->getUserDmAsset($_SESSION['USER']['USER_ID']);
        foreach ($userDmAsset as $key => $dmAssetItem) {
            
            $userDmAsset[$key]['asset_usd'] = bcmul($dmAssetItem['asset_total'], $coinList[$dmAssetItem['market_stock_coin']]['coin_usd'], 2);
            $userAssetUsdTotal = bcadd($userAssetUsdTotal, $userDmAsset[$key]['asset_usd'], 2);
        }

        $userAssetTemp = $this->asset_model->getUserAsset($_SESSION['USER']['USER_ID']);

        $userAsset = array();

        if ($userAssetTemp && count($userAssetTemp)) {
            
            foreach ($userAssetTemp as $coinSymbol => $assetTemp) {

                $userAsset[$coinSymbol] = $assetTemp['asset_active'];
            }
        }

        $data = array(

            'userDmAsset' => $userDmAsset,
            'userAsset' => $userAsset,
            'userAssetUsdTotal' => $userAssetUsdTotal
        );

        $this->load->view($this->viewPath . '/account/asset_dm', $data);
    }


    //资产划转
    public function asset_move(){

        if ($_POST && isset($_POST['from']) && isset($_POST['to']) && isset($_POST['count']) && is_numeric($_POST['count']) && isset($_POST['coin']) && isset($this->assetMap[$_POST['from']]) && isset($this->assetMap[$_POST['to']])) {

            $result = array(

                'status' => FALSE,
                'message' => lang('controller_account_asset_move_1')
            );
            
            $coin = FALSE;

            if ($_POST['coin'] != '') {
                
                $coin_symbol = strtoupper($_POST['coin']);

                $coin = $this->coin_model->oneActiveCoinBySymbol($coin_symbol);
            }

            if ($coin) {
                
                $fromPlate = intval($this->assetMap[$_POST['from']]);
                $toPlate = intval($this->assetMap[$_POST['to']]);

                if (bccomp($_POST['count'], 0, 8) > 0) {
                    
                    $userAsset = array(

                        //币币资产
                        1 => $this->asset_model->getUserAsset($_SESSION['USER']['USER_ID'], $coin['coin_symbol'])['asset_active'],

                        //合约资产
                        4 => $this->asset_model->getUserDmAsset($_SESSION['USER']['USER_ID'], $coin['coin_id'])['asset_active']
                    );

                    if (bccomp($userAsset[$fromPlate], $_POST['count'], 8) >= 0) {
                        
                        if ($this->asset_model->asset_move($_SESSION['USER']['USER_ID'], $fromPlate, $toPlate, $coin['coin_id'], $coin['coin_symbol'], $_POST['count'])) {
                            
                            $result['status'] = TRUE;
                            $result['message'] = lang('controller_account_asset_move_2');
                        }
                    }else{

                        $result['message'] = lang('controller_account_asset_move_3');
                    }
                }else{

                    $result['message'] = lang('controller_account_asset_move_4');
                }
            }

            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        }
    }


    public function record($coin_symbol = '', $pageIndex = 1){

        $coin = FALSE;

        if ($coin_symbol && $coin_symbol != '') {
            
            $coin_symbol = strtoupper($coin_symbol);

            $coin = $this->coin_model->oneActiveCoinBySymbol($coin_symbol);
        }

        if ($coin) {

            $pageSize = 20;

            $recordCount = $this->asset_log_model->countUserAssetLog($_SESSION['USER']['USER_ID'], $coin['coin_id'], 1);

            $pagingInfo = getPagingInfo($recordCount, $pageIndex, $pageSize, $this->config->item('home_page'), base_url('/account/record/' . $coin_symbol . '/'));
            
            $recordList = $this->asset_log_model->getUserAssetLog($pagingInfo['pageindex'], $pageSize, $_SESSION['USER']['USER_ID'], $coin['coin_id'], 1);

            $data = array(

                'coin' => $coin,
                'recordList' => $recordList,
                'pagingInfo' => $pagingInfo,
                'recordCount' => $recordCount,
                'pageSize' => $pageSize,
                'assetSymbol' => 'exchange',
                'assetSymbolText' => lang('controller_account_record_1'),
                'backUrl' => '/account/asset'
            );

            $this->load->view($this->viewPath . '/account/asset_record', $data);
        }else{

            $this->asset();
        }
    }


    public function record_dm($coin_symbol = '', $pageIndex = 1){

        $coin = FALSE;

        if ($coin_symbol && $coin_symbol != '') {
            
            $coin_symbol = strtoupper($coin_symbol);

            $coin = $this->coin_model->oneActiveCoinBySymbol($coin_symbol);
        }

        if ($coin) {

            $pageSize = 20;

            $recordCount = $this->asset_log_model->countUserAssetLog($_SESSION['USER']['USER_ID'], $coin['coin_id'], 4);

            $pagingInfo = getPagingInfo($recordCount, $pageIndex, $pageSize, $this->config->item('home_page'), base_url('/account/record_futures/' . $coin_symbol . '/'));
            
            $recordList = $this->asset_log_model->getUserAssetLog($pagingInfo['pageindex'], $pageSize, $_SESSION['USER']['USER_ID'], $coin['coin_id'], 4);

            $data = array(

                'coin' => $coin,
                'recordList' => $recordList,
                'pagingInfo' => $pagingInfo,
                'recordCount' => $recordCount,
                'pageSize' => $pageSize,
                'assetSymbol' => 'futures',
                'assetSymbolText' => lang('controller_account_record_dm_1'),
                'backUrl' => '/account/asset_futures'
            );

            $this->load->view($this->viewPath . '/account/asset_record', $data);
        }else{

            $this->asset();
        }
    }


    public function auth(){

        $user = $this->user_model->one($_SESSION['USER']['USER_ID']);

        if ($_POST) {
            if (isset($_POST['auth_name']) && isset($_POST['auth_number']) && isset($_POST['image_1']) && isset($_POST['image_2'])) {
                
                $result = array(

                    'status' => FALSE,
                    'message' => lang('controller_account_auth_1')
                );

                $user['user_id'] = $_SESSION['USER']['USER_ID'];
                $user['user_auth'] = 1;
                $user['user_auth_time'] = APP_TIME;
                $user['user_auth_name'] = $_POST['auth_name'];
                $user['user_auth_number'] = $_POST['auth_number'];
                $user['user_auth_image'] = json_encode(array($_POST['image_1'], $_POST['image_2']));

                if ($this->user_model->update($user)) {
                    
                    $result['status'] = TRUE;
                    $result['message'] = lang('controller_account_auth_2');
                }

                echo json_encode($result, JSON_UNESCAPED_UNICODE);
            }
        }else{

            $authImage = array('', '', '');

            if ($user['user_auth'] > 0) {
                
                $authImage = json_decode($user['user_auth_image'], TRUE);
            }

            $data = array(

                'current_page' => 'auth',
                'user' => $user,
                'authImage' => $authImage
            );

            $this->load->view($this->viewPath . '/account/auth', $data);
        }
    }


    public function repass(){

        $user = $this->user_model->one($_SESSION['USER']['USER_ID']);

        if ($_POST) {

            if (isset($_POST['old_password']) && isset($_POST['new_password']) && isset($_POST['re_new_password']) && isset($_POST['validate'])) {
                
                $result = array(

                    'status' => FALSE,
                    'message' => lang('controller_account_repass_1')
                );

                if ($_POST['new_password'] == $_POST['re_new_password']) {

                    if ($this->user_model->checkSmsValidate($_POST['validate']) || $this->user_model->checkEmailValidate($_POST['validate'])) {

                        if ($user && $user['user_password'] == pwd_encode($_POST['old_password'])) {
                            
                            $user['user_password'] = pwd_encode($_POST['new_password']);

                            if ($this->user_model->update($user)) {
                                
                                $result['status'] = TRUE;
                                $result['message'] = lang('controller_account_repass_2');
                            }
                        }else{

                            $result['message'] = lang('controller_account_repass_3');
                        }
                    }else{

                        $result['message'] = lang('controller_account_repass_4');
                    }
                }else{

                    $result['message'] = lang('controller_account_repass_5');
                }

                echo json_encode($result, JSON_UNESCAPED_UNICODE);
            }
        }else{

            $data = array(

                'user' => $user,
                'current_page' => 'repass'
            );

            $this->load->view($this->viewPath . '/account/repass', $data);
        }
    }


    public function reexpass(){

        $user = $this->user_model->one($_SESSION['USER']['USER_ID']);

        if ($_POST) {

            if (isset($_POST['user_password']) && isset($_POST['new_expassword']) && isset($_POST['re_new_expassword']) && isset($_POST['validate'])) {
                
                $result = array(

                    'status' => FALSE,
                    'message' => lang('controller_account_reexpass_1')
                );

                if ($_POST['new_expassword'] == $_POST['re_new_expassword']) {

                    if ($this->user_model->checkSmsValidate($_POST['validate']) || $this->user_model->checkEmailValidate($_POST['validate'])) {

                        if ($user && $user['user_password'] == pwd_encode($_POST['user_password'])) {
                            
                            $user['user_ex_password'] = pwd_encode($_POST['new_expassword']);

                            if ($this->user_model->update($user)) {
                                
                                $result['status'] = TRUE;
                                $result['message'] = lang('controller_account_reexpass_2');
                            }
                        }else{

                            $result['message'] = lang('controller_account_reexpass_3');
                        }
                    }else{

                        $result['message'] = lang('controller_account_reexpass_4');
                    }
                }else{

                    $result['message'] = lang('controller_account_reexpass_5');
                }

                echo json_encode($result, JSON_UNESCAPED_UNICODE);
            }
        }else{

            $data = array(

                'user' => $user,
                'current_page' => 'reexpass'
            );

            $this->load->view($this->viewPath . '/account/reexpass', $data);
        }
    }


    public function invite($pageIndex = 1){

        $pageSize = 20;

        $user = $this->user_model->one($_SESSION['USER']['USER_ID']);

        if ($user && count($user)) {

            $pagingInfo = getPagingInfo($user['user_invite_count'], $pageIndex, $pageSize, $this->config->item('home_page'), base_url('/account/invite/'));

            $inviteList = $this->user_model->getUserInviteList($user['user_id'], $pagingInfo['pageindex'], $pageSize);
            
            $data = array(

                'current_page' => 'invite',
                'user' => $user,
                'inviteList' => $inviteList,
                'pagingInfo' => $pagingInfo,
                'pageSize' => $pageSize
            );

            $this->load->view($this->viewPath . '/account/invite', $data);
        }
    }


    //提现
    public function withdraw($coin_symbol = '', $pageIndex = 1){

        $coin = FALSE;

        if ($coin_symbol && $coin_symbol != '') {
            
            $coin_symbol = strtoupper($coin_symbol);

            $coin = $this->coin_model->oneActiveCoinBySymbol($coin_symbol);
        }

        $user = $this->user_model->one($_SESSION['USER']['USER_ID']);

        if ($coin && $user) {

            if ($_POST && isset($_POST['wallet_symbol']) && $_POST['wallet_symbol'] != '' && isset($_POST['withdraw_address']) && $_POST['withdraw_address'] != '' && isset($_POST['withdraw_address_memo']) && $_POST['withdraw_address_memo'] != '' && isset($_POST['withdraw_amount']) && $_POST['withdraw_amount'] != '' && isset($_POST['validate']) && $_POST['validate'] != '' && isset($_POST['expassword']) && $_POST['expassword'] != '') {

                $result = array(

                    'status' => FALSE,
                    'message' => lang('controller_account_withdraw_1')
                );

                if ($this->user_model->checkSmsValidate($_POST['validate']) || $this->user_model->checkEmailValidate($_POST['validate'])) {

                    $coinChain = FALSE;
                    $coinContract = FALSE;
                    $coinMemo = FALSE;
                    $coinChainSymbol = FALSE;

                    //多链
                    if ($coin['coin_chain'] == -1) {
                        
                        $chainList = json_decode($coin['coin_contract'], TRUE);

                        if ($chainList && count($chainList)) {
                            
                            foreach ($chainList as $chainSymbol => $chainInfo) {

                                if ($chainSymbol == $_POST['wallet_symbol'] && isset($chainInfo[0]) && isset($chainInfo[1])) {
                                    
                                    $coinChain = $chainInfo[0];
                                    $coinContract = $chainInfo[1];
                                    $coinMemo = '';
                                    $coinChainSymbol = $chainSymbol;
                                }
                            }
                        }  
                    //单链
                    }else{

                        $coinChain = $coin['coin_chain'];
                        $coinContract = $coin['coin_contract'];
                        $coinChainSymbol = $coin['coin_symbol'];
                        $coinMemo = '';

                        //EOS或XRP
                        if ($coin['coin_chain'] == 144 || $coin['coin_chain'] == 194) {
                            
                            $coinMemo = $user['user_memo'];
                        }
                    }
                    if (is_numeric($_POST['withdraw_amount']) && $coinChain !== FALSE && $coinContract !== FALSE && $coinMemo !== FALSE && $coinChainSymbol !== FALSE) {

                        if ($this->user_model->checkAuth($user)) {

                            if (pwd_encode($_POST['expassword']) == $user['user_ex_password']) {
                                
                                if (bccomp($_POST['withdraw_amount'], 0, $this->config->item('ex_asset_scale')) > 0) {

                                    if (bccomp($_POST['withdraw_amount'], $coin['coin_withdraw_amount'], $this->config->item('ex_asset_scale')) >= 0) {
                                        
                                        $coinAsset = $this->asset_model->getUserAsset($_SESSION['USER']['USER_ID'], $coin['coin_symbol']);

                                        if (bccomp($coinAsset['asset_active'], $_POST['withdraw_amount'], $this->config->item('ex_asset_scale')) >= 0) {

                                            $this->load->model('udun_model');

                                            $checkResult = $this->udun_model->checkAddress($coinChain, $_POST['withdraw_address']);

                                            if ($checkResult) {

                                                $localAddress = $this->wallet_model->oneWalletByChainAndAddress($coinChain, $_POST['withdraw_address']);
                                                
                                                //扣款
                                                $assetResult = $this->asset_model->assetChange($_SESSION['USER']['USER_ID'], $coin['coin_symbol'], $_POST['withdraw_amount'], 4, $_POST['withdraw_address']);

                                                if ($assetResult['status']) {
                                                    
                                                    $withdraw = $this->withdraw_model->initInsert($_POST);

                                                    $withdraw['withdraw_user'] = $_SESSION['USER']['USER_ID'];
                                                    $withdraw['withdraw_coin'] = $coin['coin_id'];
                                                    $withdraw['withdraw_to_address'] = $_POST['withdraw_address'];
                                                    $withdraw['withdraw_to_address_memo'] = $coinMemo;
                                                    $withdraw['withdraw_amount'] = $_POST['withdraw_amount'];
                                                    $withdraw['withdraw_fee'] = $coin['coin_withdraw_fee'];
                                                    $withdraw['withdraw_finally_amount'] = bcsub($_POST['withdraw_amount'], $coin['coin_withdraw_fee'], $this->config->item('ex_asset_scale'));
                                                    $withdraw['withdraw_local'] = $localAddress ? 1 : 0;
                                                    $withdraw['withdraw_chain'] = $coinChain;
                                                    $withdraw['withdraw_contract'] = $coinContract;
                                                    $withdraw['withdraw_chain_symbol'] = $coinChainSymbol;

                                                    if ($this->withdraw_model->insert($withdraw)) {
                                                        
                                                        $result['status'] = TRUE;
                                                        $result['message'] = lang('controller_account_withdraw_2');
                                                    }
                                                }
                                            }else{

                                                $result['message'] = lang('controller_account_withdraw_3');
                                            }
                                        }else{

                                            $result['message'] = lang('controller_account_withdraw_4');
                                        }
                                    }else{

                                        $result['message'] = lang('controller_account_withdraw_5') . floatval($coin['coin_withdraw_amount']) . ' ' . $coin['coin_symbol'];
                                    }
                                }else{

                                    $result['message'] = lang('controller_account_withdraw_6');
                                }
                            }else{

                                $result['message'] = lang('controller_account_withdraw_7');
                            }
                        }else{

                            $result['message'] = lang('controller_account_withdraw_8');
                        }
                    }else{

                        $result['message'] = lang('controller_account_withdraw_9');
                    }
                }else{

                    $result['message'] = lang('controller_account_withdraw_10');
                }

                echo json_encode($result, JSON_UNESCAPED_UNICODE);
            }else{

                $pageSize = 20;

                $withdrawCount = $this->withdraw_model->countUserWithdraw($_SESSION['USER']['USER_ID'], $coin['coin_id']);
                $pagingInfo = getPagingInfo($withdrawCount, $pageIndex, $pageSize, $this->config->item('home_page'), base_url('/account/withdraw/' . $coin_symbol . '/'));

                $withdrawList = $this->withdraw_model->getUserWithdraw($pagingInfo['pageindex'], $pageSize, $_SESSION['USER']['USER_ID'], $coin['coin_id']);
                $coinAsset = $this->asset_model->getUserAsset($_SESSION['USER']['USER_ID'], $coin['coin_symbol']);

                $coinChainList = NULL;

                if ($coin['coin_chain'] == -1) {
                    
                    $chainList = json_decode($coin['coin_contract'], TRUE);

                    if ($chainList && count($chainList)) {
                        
                        foreach ($chainList as $chainSymbol => $chainInfo) {
                            
                            $coinChainList[] = $chainSymbol;
                        }
                    }
                }

                $data = array(

                    'coin' => $coin,
                    'withdrawList' => $withdrawList,
                    'coinAsset' => $coinAsset,
                    'withdrawCount' => $withdrawCount,
                    'pageSize' => $pageSize,
                    'pagingInfo' => $pagingInfo,
                    'user' => $user,
                    'coinChainList' => $coinChainList
                );

                $this->load->view($this->viewPath . '/account/withdraw', $data);
            }
        }
    }


    public function get_wallet(){

        if ($_POST && isset($_POST['coin'])) {
            
            $result = array(

                'status' => FALSE,
                'message' => lang('controller_account_get_wallet_1')
            );

            $coin = $this->coin_model->oneActiveCoinBySymbol(strtoupper($_POST['coin']));

            if ($coin && count($coin) && $coin['coin_chain'] != 144 && $coin['coin_chain'] != 194) {

                $userWallet = NULL;
                $chainCode = FALSE;

                //多链
                if ($coin['coin_chain'] == -1 && isset($_POST['wallet_symbol']) && $_POST['wallet_symbol'] != '') {
                    
                    $chainSymbol = $_POST['wallet_symbol'];
                    $chainList = json_decode($coin['coin_contract'], TRUE);

                    if (isset($chainList[$chainSymbol]) && isset($chainList[$chainSymbol][0])) {
                        
                        $userWallet = $this->wallet_model->oneWallet($_SESSION['USER']['USER_ID'], $chainList[$chainSymbol][0]);

                        $chainCode = $chainList[$chainSymbol][0];
                    }
                //单链
                }else{

                    $userWallet = $this->wallet_model->oneWallet($_SESSION['USER']['USER_ID'], $coin['coin_chain']);
                    $chainCode = $coin['coin_chain'];
                }

                if ($userWallet) {
                    
                    $result['message'] = lang('controller_account_get_wallet_2');
                }else{

                    $this->load->model('udun_model');

                    $ret = $this->udun_model->createAddress($chainCode, $_SESSION['USER']['USER_ID'] . '|' . $_SESSION['USER']['USER_NAME'], $this->config->item('udun_wallet'));

                    if ($ret['status']) {
                        
                        $userWallet = array(

                            'wallet_id' => 0,
                            'wallet_user' => $_SESSION['USER']['USER_ID'],
                            'wallet_value' => $ret['data'],
                            'wallet_chain' => $chainCode
                        );

                        if ($this->wallet_model->insert($userWallet)) {
                            
                            $result['status'] = TRUE;
                            $result['message'] = lang('controller_account_get_wallet_3');
                        }
                    }
                }
            }

            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        }
    }


    //充值
    public function recharge($coin_symbol = '', $pageIndex = 1){

        $coin = FALSE;

        if ($coin_symbol && $coin_symbol != '') {
            
            $coin_symbol = strtoupper($coin_symbol);

            $coin = $this->coin_model->oneActiveCoinBySymbol($coin_symbol);
        }

        if ($coin) {

            $pageSize = 20;

            $rechargeCount = $this->recharge_model->countUserRecharge($_SESSION['USER']['USER_ID'], $coin['coin_id']);

            $pagingInfo = getPagingInfo($rechargeCount, $pageIndex, $pageSize, $this->config->item('home_page'), base_url('/account/recharge/' . $coin_symbol . '/'));
            
            $rechargeList = $this->recharge_model->getUserRecharge($pagingInfo['pageindex'], $pageSize, $_SESSION['USER']['USER_ID'], $coin['coin_id']);

            $userWallet = NULL;

            //多链
            if ($coin['coin_chain'] == -1) {
                
                $chainList = json_decode($coin['coin_contract'], TRUE);

                if ($chainList && count($chainList)) {
                    
                    foreach ($chainList as $chainSymbol => $chainInfo) {
                        
                        $userWallet[$chainSymbol] = $this->wallet_model->oneWallet($_SESSION['USER']['USER_ID'], $chainInfo[0]);
                    }
                }
            //EOS或XRP
            }elseif($coin['coin_chain'] == 144 || $coin['coin_chain'] == 194){

                $userWallet[$coin['coin_symbol']] = array(

                    $coin['coin_memo'],
                    $_SESSION['USER']['USER_MEMO']
                );
            //单链
            }else{

                $userWallet[$coin['coin_symbol']] = $this->wallet_model->oneWallet($_SESSION['USER']['USER_ID'], $coin['coin_chain']);
            }

            $data = array(

                'coin' => $coin,
                'rechargeList' => $rechargeList,
                'userWallet' => $userWallet,
                'pagingInfo' => $pagingInfo,
                'rechargeCount' => $rechargeCount,
                'pageSize' => $pageSize
            );

            $this->load->view($this->viewPath . '/account/recharge', $data);
        }else{

            $this->asset();
        }
    }


    //登陆
    public function login(){

        if ($_POST && isset($_POST['user_name']) && isset($_POST['user_password']) && isset($_POST['validate'])) {
            
            $result = array(

                'status' => FALSE,
                'message' => lang('controller_account_login_1')
            );

            if ($_POST['user_name'] == '' || $_POST['user_password'] == '') {
                
                $result['message'] = lang('controller_account_login_2');
            }else{

                if ($this->user_model->checkImageValidate($_POST['validate'])) {
                    
                    $user = $this->user_model->login($_POST['user_name'], pwd_encode($_POST['user_password']));

                    if ($user && count($user)) {
                        
                        $this->user_model->createLoginSession($user);

                        $this->user_model->flushLastLogin($user['user_id']);

                        $result['status'] = TRUE;
                        $result['message'] = lang('controller_account_login_3');
                    }else{

                        $result['message'] = lang('controller_account_login_4');
                    }
                }else{

                    $result['message'] = lang('controller_account_login_5');
                }
            }

            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        }else{

            $this->load->view($this->viewPath . '/account/login');
        }
    }


    //退出登陆
    public function logout(){

        $this->user_model->logout();
    }


    public function forgot(){

        if ($_POST && isset($_POST['user_phone']) && isset($_POST['user_phone_area']) && isset($_POST['user_email']) && isset($_POST['validate']) && isset($_POST['user_password']) && isset($_POST['repassword'])) {

            $result = array(

                'status' => FALSE,
                'message' => lang('controller_account_forgot_1')
            );

            if ($_POST['user_password'] == $_POST['repassword']) {

                if ($this->user_model->checkSmsValidate($_POST['validate']) || $this->user_model->checkEmailValidate($_POST['validate'])) {

                    $userInfo = array(

                        'user_phone' => $_POST['user_phone'],
                        'user_phone_area' => $_POST['user_phone_area'],
                        'user_email' => $_POST['user_email']
                    );

                    $user = FALSE;

                    if ($userInfo['user_phone'] != '') {
                        
                        $user = $this->user_model->oneUserByPhone($userInfo);
                    }

                    if ((! $user) && $userInfo['user_email'] != '') {
                        
                        $user = $this->user_model->oneUserByEmail($userInfo);
                    }

                    if ($user) {
                        
                        $user['user_password'] = pwd_encode($_POST['user_password']);

                        if ($this->user_model->update($user)) {
                            
                            if (isset($_SESSION['USER'])) {
                                
                                unset($_SESSION['USER']);
                            }

                            $result['status'] = TRUE;
                            $result['message'] = lang('controller_account_forgot_2');
                        }
                    }else{

                        $result['message'] = lang('controller_account_forgot_3');
                    }
                }else{

                    $result['message'] = lang('controller_account_forgot_4');
                }
            }else{

                $result['message'] = lang('controller_account_forgot_5');
            }

            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        }else{

            $defaultAreaCode = $this->config->item('phone_area_code_default');

            $data = array(

                'defaultAreaCode' => $defaultAreaCode
            );

            $this->load->view($this->viewPath . '/account/forgot', $data);
        }
    }


    //用户注册
    public function register($step = 1){

    	if ($_POST) {
    		
    		$result = array(

    		    'status' => FALSE,
    		    'message' => lang('controller_account_register_1')
    		);

    		if ($_POST['user_password'] == $_POST['repassword']) {

                if ($_POST['user_ex_password'] == $_POST['reexpassword']) {

                    if ($_POST['user_password'] == $_POST['user_ex_password']) {

                        $result['message'] = lang('controller_account_register_2');
                    }else{

                        if ($this->user_model->checkSmsValidate($_POST['validate']) || $this->user_model->checkEmailValidate($_POST['validate'])) {

                            $parentUser = $this->user_model->oneUserByInviteCode(strtoupper($_POST['invite_code']));
                            
                            $user = $this->user_model->initInsert($_POST);

                            if ($parentUser) {

                                $parentUser['user_invite_count'] ++;
                                $this->user_model->update($parentUser);

                                $user['user_parent'] = $parentUser['user_id'];

                                $existUser = $this->user_model->oneUserByEmailPhoneName($user);

                                if ($existUser && count($existUser)) {
                                    
                                    $result['message'] = lang('controller_account_register_3');
                                }else{

                                    $user = $this->user_model->insert($user);

                                    if ($user && isset($user['user_id']) && $user['user_id'] > 0) {

                                        $user = $this->user_model->one($user['user_id']);

                                        $result['status'] = TRUE;
                                        $result['message'] = lang('controller_account_register_4');

                                        $this->user_model->registerNotice();
                                    }
                                }
                            }else{

                                $result['message'] = lang('controller_account_register_5');
                            }
                        }else{

                            $result['message'] = lang('controller_account_register_6');
                        }
                    }
                }else{

                    $result['message'] = lang('controller_account_register_7');
                }
    		}else{

    			$result['message'] = lang('controller_account_register_8');
    		}

    		echo json_encode($result, JSON_UNESCAPED_UNICODE);
    	}else{

            $inviteCode = FALSE;

            if (isset($_GET['i'])) {
                
                $inviteCode = $_GET['i'];
            }

            $defaultAreaCode = $this->config->item('phone_area_code_default');

    		$data = array(

                'inviteCode' => $inviteCode,
                'defaultAreaCode' => $defaultAreaCode
    		);

    		$this->load->view($this->viewPath . '/account/register', $data);
    	}
    }
}
