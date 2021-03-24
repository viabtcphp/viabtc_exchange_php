<?php
defined("BASEPATH") OR exit("No direct script access allowed");

/**
 * 前台公共控制器
 */
class Common extends MY_Controller {


    public function __construct(){

        parent::__construct();

        $this->load->model('user_model');
        $this->load->model('article_model');
    }


    /**
     * 生成并获取文字验证码
     * @param  integer $_width  宽
     * @param  integer $_height 高
     */
    public function validate($_width = 0, $_height = 0){

        $this->load->library("validatecode");
        $this->validatecode->doimg($_width, $_height);

        $_SESSION["USER_VALIDATE"] = $this->validatecode->getCode();
    }


    //更换语言
    public function change_language(){

        if ($_POST && isset($_POST['_language']) && in_array($_POST['_language'], array_keys($this->config->item('_language_list')))) {
            
            $_SESSION['_language'] = $_POST['_language'];
        }
    }


    /**
     * 发送短信验证码
     */
    public function user_sms_validate(){

        if ($_POST) {
            
            $result = array(

                "status" => FALSE,
                "message" => lang('controller_common_user_sms_validate_1')
            );

            $this->user_model->checkLogin();

            if ($this->user_model->checkImageValidate($_POST["validate"])) {
                
                $phone = $_SESSION["USER"]["USER_PHONE"];

                $areaCode = $_SESSION["USER"]["USER_PHONE_AREA"];

                $this->load->model("sms_model");

                if ($this->sms_model->smsValidate($phone, $areaCode)) {
                    
                    $result["status"] = TRUE;
                    $result["message"] = lang('controller_common_user_sms_validate_2');
                }
            }else{

                $result["message"] = lang('controller_common_user_sms_validate_3');
            }

            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        }
    }


    /**
     * 发送短信验证码
     */
    public function sms_validate(){

        if ($_POST) {
            
            $result = array(

                "status" => FALSE,
                "message" => lang('controller_common_sms_validate_1')
            );

            if ($this->user_model->checkImageValidate($_POST["validate"])) {
                
                $phone = $_POST["phone"];
                $areaCode = $_POST['area_code'];

                if ($phone != '' && $areaCode != '') {
                    
                    $this->load->model("sms_model");

                    if ($this->sms_model->smsValidate($phone, $areaCode)) {
                        
                        $result["status"] = TRUE;
                        $result["message"] = lang('controller_common_sms_validate_2');
                    }
                }else{

                    $result["message"] = lang('controller_common_sms_validate_3');
                }
            }else{

                $result["message"] = lang('controller_common_sms_validate_4');
            }

            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        }
    }


    /**
     * 发送邮箱验证码
     */
    public function user_email_validate(){

        if ($_POST) {
            
            $result = array(

                "status" => FALSE,
                "message" => lang('controller_common_user_email_validate_1')
            );

            $this->user_model->checkLogin();

            if ($this->user_model->checkImageValidate($_POST["validate"])) {
                
                $email = $_SESSION["USER"]["USER_EMAIL"];

                if (checkEmailFomat($email)) {
                    
                    $this->load->model("email_model");

                    if ($this->email_model->emailValidate($email)) {
                        
                        $result["status"] = TRUE;
                        $result["message"] = lang('controller_common_user_email_validate_2');
                    }
                }else{

                    $result["message"] = lang('controller_common_user_email_validate_3');
                }
            }else{

                $result["message"] = lang('controller_common_user_email_validate_4');
            }

            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        }
    }


    /**
     * 发送邮箱验证码
     */
    public function email_validate(){

        if ($_POST) {
            
            $result = array(

                "status" => FALSE,
                "message" => lang('controller_common_email_validate_1')
            );

            if ($this->user_model->checkImageValidate($_POST["validate"])) {
                
                $email = $_POST["email"];

                if (checkEmailFomat($email)) {
                    
                    $this->load->model("email_model");

                    if ($this->email_model->emailValidate($email)) {
                        
                        $result["status"] = TRUE;
                        $result["message"] = lang('controller_common_email_validate_2');
                    }
                }else{

                    $result["message"] = lang('controller_common_email_validate_3');
                }
            }else{

                $result["message"] = lang('controller_common_email_validate_4');
            }

            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        }
    }


    /**
     * 异步上传文件
     * 如果有多个文件同时上传，只要有一个上传失败，则返回失败
     * @param  string $dir 需要保存的分类文件夹名称
     */
    public function upload($dir = ""){

        if ($_FILES && count($_FILES)) {

            $result = array(

                "status"    => FALSE,
                "message"   => '',
                "filename"  => array()
            );
            
            foreach ($_FILES as $file) {
                
                if ($file["error"] == 0) {

                    $fileStr = file_get_contents($file['tmp_name']);

                    if ($this->security->xss_clean($fileStr, TRUE) === FALSE) {
                        
                        $fileName = $this->config->item("upload_path") . ($dir == "" ? "" : ('/' . $dir)) . autoSavePath(APP_TIME, getFileType($file["name"]));

                        if (uploadFile($file, FCPATH . '/' . $fileName)) {
                            
                            $result["status"]       = TRUE;
                            $result["message"]      = lang('controller_common_upload_1');
                            $result["filename"][]   = $fileName;
                        }else{

                            $result["status"] = FALSE;
                            $result["message"] = lang('controller_common_upload_2') . " [ " . $file["name"] . " ] " . lang('controller_common_upload_3');
                            break;
                        }
                    }
                }
            }

            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        }
    }


    //提币回调
    public function udun_callback(){

        $this->load->model('udun_model');
        $this->load->model('withdraw_model');
        $this->load->model('recharge_model');
        $this->load->model('coin_model');
        $this->load->model('wallet_model');
        $this->load->model('user_model');

        if ($_POST && isset($_POST['timestamp']) && isset($_POST['nonce']) && isset($_POST['sign']) && isset($_POST['body'])) {

            $result = FALSE;
            $logText = 'U盾回调|';

            if ($this->udun_model->checkSign($_POST)) {
                
                $body = json_decode($_POST['body'], TRUE);

                //充币回调
                if ($body && $body['tradeType'] == 1 && isset($body['status']) && $body['status'] == 3) {
                    
                    $logText .= '充币回调|';

                    $recharge = $this->recharge_model->oneRechargeByTradeId($body['txId']);

                    if (! $recharge) {
                        
                        $coinChain = $body['mainCoinType'];
                        $coinContract = $body['coinType'];

                        $coinSymbol = '';

                        //单链
                        $coin = $this->coin_model->oneActiveCoinByChainAndContract($coinChain, $coinContract);

                        //多链
                        if ($coin) {
                            
                            $coinSymbol = $coin['coin_symbol'];
                        }else{

                            $coinList = $this->coin_model->getActiveCoinByChain(-1);

                            if ($coinList && count($coinList)) {
                                
                                foreach ($coinList as $coinItem) {
                                    
                                    $chainList = json_decode($coinItem['coin_contract'], TRUE);

                                    if ($chainList && count($chainList)) {
                                        
                                        foreach ($chainList as $chainSymbol => $chainInfo) {
                                            
                                            if ($coinChain == $chainInfo[0] && $coinContract == $chainInfo[1]) {
                                                
                                                $coin = $coinItem;
                                                $coinSymbol = $chainSymbol;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        if ($coin) {

                            $amount = bcdiv($body['amount'], bcpow(10, $body['decimals']) , $body['decimals']);

                            if (bccomp($amount, $coin['coin_recharge_min_amount'], 8) >= 0) {
                                
                                $user = NULL;

                                if (isset($body['memo']) && $body['memo'] != '') {

                                    $user = $this->user_model->oneActiveUserByMemo($body['memo']);
                                }else{

                                    $userWallet = $this->wallet_model->oneWalletByChainAndAddress($coinChain, $body['address']);

                                    if ($userWallet) {
                                        
                                        $user = $this->user_model->oneActiveUser($userWallet['wallet_user']);
                                    }
                                }

                                if ($user) {
                                    
                                    $recharge = $this->recharge_model->fieldsArray;

                                    $recharge['recharge_user'] = $user['user_id'];
                                    $recharge['recharge_coin'] = $coin['coin_id'];
                                    $recharge['recharge_from_address'] = $body['address'];
                                    $recharge['recharge_amount'] = $amount;
                                    $recharge['recharge_no'] = $body['tradeId'];
                                    $recharge['recharge_txid'] = $body['txId'];
                                    $recharge['recharge_trade_id'] = $body['tradeId'];
                                    $recharge['recharge_coin_symbol'] = $coinSymbol;
                                    $recharge['recharge_memo'] = isset($body['memo']) ? $body['memo'] : '';

                                    $result = $this->recharge_model->insert($recharge);

                                    if ($result) {
                                        
                                        // $this->user_model->rechargeNotice($user, $recharge['recharge_amount'], $coinSymbol);
                                    }
                                }
                            }else{

                                $logText .= '数量小于最低充值|';
                            }
                        }
                    }
                }

                //提币回调
                if ($body && $body['tradeType'] == 2) {

                    $logText .= '提币回调|';
                    
                    $withdraw = $this->withdraw_model->oneWithdrawByNo($body['businessId']);

                    $user = $this->user_model->oneActiveUser($withdraw['withdraw_user']);

                    if ($withdraw && $user) {

                        $result = FALSE;
                        
                        switch ($body['status']) {

                            case 0:
                                
                                if ($withdraw['withdraw_status'] != 4 && $withdraw['withdraw_status'] != 5 && $withdraw['withdraw_status'] != 6 && $withdraw['withdraw_status'] != 7) {
                                    
                                    $withdraw['withdraw_status'] = 3;
                                    $withdraw['withdraw_txid'] = $body['txId'];
                                    $withdraw['withdraw_trade_id'] = $body['tradeId'];

                                    $result = $this->withdraw_model->update($withdraw);
                                }
                            break;

                            case 1:
                                
                                if ($withdraw['withdraw_status'] != 6 && $withdraw['withdraw_status'] != 7) {
                                    
                                    $withdraw['withdraw_status'] = 4;
                                    $withdraw['withdraw_txid'] = $body['txId'];
                                    $withdraw['withdraw_trade_id'] = $body['tradeId'];

                                    $result = $this->withdraw_model->update($withdraw);
                                }
                            break;

                            case 2:
                                
                                if ($withdraw['withdraw_status'] != 6 && $withdraw['withdraw_status'] != 7) {
                                    
                                    $withdraw['withdraw_status'] = 5;
                                    $withdraw['withdraw_txid'] = $body['txId'];
                                    $withdraw['withdraw_trade_id'] = $body['tradeId'];

                                    $result = $this->withdraw_model->update($withdraw);
                                }
                            break;

                            case 3:

                                if ($withdraw['withdraw_status'] != 6 && $withdraw['withdraw_status'] != 7) {

                                    $withdraw['withdraw_status'] = 6;
                                    $withdraw['withdraw_txid'] = $body['txId'];
                                    $withdraw['withdraw_trade_id'] = $body['tradeId'];

                                    $result = $this->withdraw_model->successWithdraw($withdraw);

                                    if ($result) {
                                        
                                        // $this->user_model->withdrawNotice($user, $withdraw['withdraw_amount'], $withdraw['withdraw_chain_symbol']);
                                    }
                                }
                            break;

                            case 4:
                                
                                if ($withdraw['withdraw_status'] != 6 && $withdraw['withdraw_status'] != 7) {
                                    
                                    $withdraw['withdraw_status'] = 7;
                                    $withdraw['withdraw_txid'] = $body['txId'];
                                    $withdraw['withdraw_trade_id'] = $body['tradeId'];

                                    $result = $this->withdraw_model->update($withdraw);
                                }
                            break;
                        }
                    }
                }
            }else{

                $logText .= '签名校验失败|';
            }

            $returnText = '';

            if ($result) {
                
                $logText .= '处理成功|';
                $returnText = 'SUCCESS';
            }else{

                $logText .= '处理失败|';
                $returnText = 'ERROR';
            }

            $logText .= json_encode($_POST, JSON_UNESCAPED_UNICODE);

            log_message('error', $logText);

            echo $returnText;
        }
    }
}
