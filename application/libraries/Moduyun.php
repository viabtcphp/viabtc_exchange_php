<?php

class Moduyun {
    var $url;
    var $accesskey;
    var $secretkey;
    var $util;
    var $sign;

    function __construct($params = array()) {

        $this->accesskey = isset($params['accesskey']) ? $params['accesskey'] : '';
        $this->secretkey = isset($params['secretkey']) ? $params['secretkey'] : '';
        $this->sign = isset($params['sign']) ? $params['sign'] : '';
        $this->util = new SmsSenderUtil();
    }

    /**
     * 普通单发，明确指定内容，如果有多个签名，请在内容中以【】的方式添加到信息内容中，否则系统将使用默认签名
     * @param int $type 短信类型，0 为普通短信，1 营销短信
     * @param string $nationCode 国家码，如 86 为中国
     * @param string $phoneNumber 不带国家码的手机号
     * @param string $msg 信息内容，必须与申请的模板格式一致，否则将返回错误
     * @param string $extend 扩展码，可填空串
     * @param string $ext 服务端原样返回的参数，可填空串
     * @return string json string { "result": xxxxx, "errmsg": "xxxxxx" ... }，被省略的内容参见协议文档
     */
    function send($type, $nationCode, $phoneNumber, $msg, $extend = "", $ext = "") {
/*
请求包体
{
    "tel": {
        "nationcode": "86",
        "mobile": "13788888888"
    },
    "type": 0,
    "msg": "你的验证码是1234",
    "sig": "fdba654e05bc0d15796713a1a1a2318c",
    "time": 1479888540,
    "extend": "",
    "ext": ""
}
应答包体
{
    "result": 0,
    "errmsg": "OK",
    "ext": "",
    "sid": "xxxxxxx",
    "fee": 1
}
*/
        $this->url = "https://live.mordula.com/sms/v1/sendsinglesms";
        $random = $this->util->getRandom();
        $curTime = time();
        $wholeUrl = $this->url . "?accesskey=" . $this->accesskey . "&random=" . $random;

        // 按照协议组织 post 包体
        $data = new \stdClass();
        $tel = new \stdClass();
        $tel->nationcode = "".$nationCode;
        $tel->mobile = "".$phoneNumber;

        $data->tel = $tel;
        $data->type = (int)$type;
        $data->msg = $msg;
        $data->sig = hash("sha256",
            "secretkey=".$this->secretkey."&random=".$random."&time=".$curTime."&mobile=".$phoneNumber, FALSE);
        $data->time = $curTime;
        $data->extend = $extend;
        $data->ext = $ext;

        $ret = $this->util->sendCurlPost($wholeUrl, $data);
        $ret = json_decode($ret, TRUE);

        $result = FALSE;

        if ($ret && isset($ret['errmsg']) && $ret['errmsg'] == 'OK') {
            
            $result = TRUE;
        }

        return $result;
    }

    /**
     * 指定模板单发
     * @param string $nationCode 国家码，如 86 为中国
     * @param string $phoneNumber 不带国家码的手机号
     * @param int $templId 模板 id
     * @param array $params 模板参数列表，如模板 {1}...{2}...{3}，那么需要带三个参数
     * @param string $extend 扩展码，可填空串
     * @param string $ext 服务端原样返回的参数，可填空串
     * @return string json string { "result": xxxxx, "errmsg": "xxxxxx"  ... }，被省略的内容参见协议文档
     */
    function sendWithParam($nationCode, $phoneNumber, $templId , $params = array(), $extend = "", $ext = "") {
/*
请求包体
{
    "tel": {
        "nationcode": "86",
        "mobile": "13788888888"
    },
    "sign": "Kewail",
    "tpl_id": 19,
    "params": [
        "验证码",
        "1234",
        "4"
    ],
    "sig": "fdba654e05bc0d15796713a1a1a2318c",
    "time": 1479888540,
    "extend": "",
    "ext": ""
}
应答包体
{
    "result": 0,
    "errmsg": "OK",
    "ext": "",
    "sid": "xxxxxxx",
    "fee": 1
}
*/      $this->url = "https://live.moduyun.com/sms/v2/sendsinglesms";
        $random = $this->util->getRandom();
        $curTime = time();
        $wholeUrl = $this->url . "?accesskey=" . $this->accesskey . "&random=" . $random;

        // 按照协议组织 post 包体
        $data = new \stdClass();
        $tel = new \stdClass();
        $tel->nationcode = "".$nationCode;
        $tel->mobile = "".$phoneNumber;

        $data->tel = $tel;
        $data->sig = hash("sha256",
            "secretkey=".$this->secretkey."&random=".$random."&time=".$curTime."&mobile=".$phoneNumber, FALSE);
        $data->templateId = $templId;
        $data->params = $params;
        $data->signId = $this->sign;
        $data->time = $curTime;
        $data->extend = $extend;
        $data->ext = $ext;

        $ret = $this->util->sendCurlPost($wholeUrl, $data);
        $ret = json_decode($ret, TRUE);

        $result = FALSE;

        if ($ret && isset($ret['errmsg']) && $ret['errmsg'] == 'OK') {
            
            $result = TRUE;
        }

        return $result;
    }
}

class SmsSenderUtil {
    function getRandom() {
        return rand(100000, 999999);
    }

    function calculateSig($secretkey, $random, $curTime, $phoneNumbers) {
        $phoneNumbersString = $phoneNumbers[0];
        for ($i = 1; $i < count($phoneNumbers); $i++) {
            $phoneNumbersString .= ("," . $phoneNumbers[$i]);
        }
        return hash("sha256", "secretkey=".$secretkey."&random=".$random
            ."&time=".$curTime."&mobile=".$phoneNumbersString);
    }

    function calculateSigForTemplAndPhoneNumbers($secretkey, $random, $curTime, $phoneNumbers) {
        $phoneNumbersString = $phoneNumbers[0];
        for ($i = 1; $i < count($phoneNumbers); $i++) {
            $phoneNumbersString .= ("," . $phoneNumbers[$i]);
        }
        return hash("sha256", "secretkey=".$secretkey."&random=".$random
            ."&time=".$curTime."&mobile=".$phoneNumbersString);
    }

    function phoneNumbersToArray($nationCode, $phoneNumbers) {
        $i = 0;
        $tel = array();
        do {
            $telElement = new \stdClass();
            $telElement->nationcode = $nationCode;
            $telElement->mobile = $phoneNumbers[$i];
            array_push($tel, $telElement);
        } while (++$i < count($phoneNumbers));
        return $tel;
    }

    function calculateSigForTempl($secretkey, $random, $curTime, $phoneNumber) {
        $phoneNumbers = array($phoneNumber);
        return $this->calculateSigForTemplAndPhoneNumbers($secretkey, $random, $curTime, $phoneNumbers);
    }

    function sendCurlPost($url, $dataObj) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");  
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($dataObj));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen(json_encode($dataObj)))); 
        $ret = curl_exec($curl);
        if (false == $ret) {
            // curl_exec failed
            $result = "{ \"result\":" . -2 . ",\"errmsg\":\"" . curl_error($curl) . "\"}";
        } else {
            $rsp = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if (200 != $rsp) {
                $result = "{ \"result\":" . -1 . ",\"errmsg\":\"". $rsp . " " . curl_error($curl) ."\"}";
            } else {
                $result = $ret;
            }
        }
        curl_close($curl);
        return $result;
    }
}
