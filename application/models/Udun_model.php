<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * U盾钱包操作模型
 */
class Udun_model extends MY_Model{


    public $requestUrl = '';

    public $apiKey = '';

    public $merchant = '';

    public $rechargeCallUrl = '';

    public $withdrawCallUrl = '';

    public function __construct(){

        $this->requestUrl = $this->config->item('udun_url');
        $this->apiKey = $this->config->item('udun_key');
        $this->merchant = $this->config->item('udun_merchant');
        $this->rechargeCallUrl = $this->config->item('base_url') . $this->config->item('udun_recharge_callback');
        $this->withdrawCallUrl = $this->config->item('base_url') . $this->config->item('udun_withdraw_callback');
    }


    // 获取商户支持的币种信息
    public function supportCoins($showBalance = true){

        $result = array(

            'status' => FALSE,
            'data' => FALSE
        );

        $body = array(
            'merchantId' => $this->merchant,
            'showBalance' => $showBalance
        );

        $uri = '/mch/support-coins';

        $ret = $this->http_post($uri, $body);

        if ($ret && isset($ret['code']) && $ret['code'] == 200) {
            
            $result['status'] = TRUE;
            $result['data'] = $ret['data'];
        }

        return $result;
    }


    // 生成地址
    public function createAddress($coinType, $alias = '', $walletId = '0'){

        $result = array(

            'status' => FALSE,
            'data' => FALSE
        );

        $body = array(
            
            array(

                'merchantId' => $this->merchant,
                'coinType' => $coinType,
                'callUrl' => $this->rechargeCallUrl,
                'alias' => $alias
            )
        );

        if ($walletId != '0') {
            
            $body[0]['walletId'] = $walletId;
        }

        $uri = '/mch/address/create';

        $ret = $this->http_post($uri, $body);

        if ($ret && isset($ret['code']) && $ret['code'] == 200) {
            
            $result['status'] = TRUE;
            $result['data'] = $ret['data']['address'];
        }

        return $result;
    }


    // 校验地址合法性
    public function checkAddress($mainCoinType, $address){

        $result = FALSE;

        $body = array(
            
            array(

                'merchantId' => $this->merchant,
                'mainCoinType' => $mainCoinType,
                'address' => $address
            )
        );

        $uri = '/mch/check/address';

        $ret = $this->http_post($uri, $body);

        if ($ret && isset($ret['code']) && $ret['code'] == 200) {
            
            $result = TRUE;
        }

        return $result;
    }


    // 发送提币申请
    public function withdraw($mainCoinType, $coinType, $amount, $address, $businessId, $memo){

        $result = FALSE;

        $body = array(

            array(

                'merchantId' => $this->merchant,
                'mainCoinType' => $mainCoinType,
                'address' => $address,
                'amount' => $amount,
                'coinType' => $coinType,
                'callUrl' => $this->withdrawCallUrl,
                'businessId' => $businessId,
                'memo' => $memo
            )
        );

        $uri = '/mch/withdraw';

        $ret = $this->http_post($uri, $body);

        if ($ret && isset($ret['code']) && $ret['code'] == 200) {
            
            $result = TRUE;
        }

        return $result;
    }


    // 代付
    public function proxypay($mainCoinType, $coinType, $amount, $address, $callUrl, $businessId, $memo){

        $result = FALSE;

        $body = array(
            
            array(

                'merchantId' => $this->merchant,
                'mainCoinType' => $mainCoinType,
                'address' => $address,
                'amount' => $amount,
                'coinType' => $coinType,
                'callUrl' => $callUrl,
                'businessId' => $businessId,
                'memo' => $memo
            )
        );

        $uri = '/mch/withdraw/proxypay';

        $ret = $this->http_post($uri, $body);

        if ($ret && isset($ret['code']) && $ret['code'] == 200) {
            
            $result = TRUE;
        }

        return $result;
    }


    public function checkSign($data){

        $result = FALSE;

        if (isset($data['body']) && isset($data['nonce']) && isset($data['timestamp']) && isset($data['sign'])) {
            
            $sign = $this->createSign($data);

            if ($sign !== FALSE && $sign === $data['sign']) {
                
                $result = TRUE;
            }
        }

        return $result;
    }


    public function createSign($data){

        $sign = FALSE;

        if (isset($data['body']) && isset($data['nonce']) && isset($data['timestamp'])) {
            
            $sign = md5($data['body'] . $this->apiKey . $data['nonce'] . $data['timestamp']);
        }

        return $sign;
    }


    public function create_params($body){

        $data = array(

            'timestamp' => APP_TIME,
            'nonce' => rand(100000, 999999),
            'body' => json_encode($body)
        );

        $data['sign'] = $this->createSign($data);

        return $data;
    }


    public function http_post($uri, $body){

        $url = $this->requestUrl . $uri;
        $data_string = json_encode($this->create_params($body));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(

            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($data_string))
        );
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, TRUE);
    }
}
