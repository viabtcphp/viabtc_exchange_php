<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 短信模型
 */
class Sms_model extends MY_Model {


    /**
     * 构造函数，初始化
     */
    public function __construct(){

        parent::__construct();

        //加载短信类库
        $this->load->library('moduyun', $this->config->item('moduyun_config')['account']);
    }


    /**
     * 向用户绑定的手机号码发送一条短信验证码
     * @param  string $phoneNumber 手机号码
     * @return bool                返回发送结果，发送成功返回TRUE，发送失败返回FALSE
     */
    public function smsValidate($phoneNumber = '', $areaCode = '+86'){

        //生成验证码
        $_SESSION['USER_SMS_VALIDATE'] = mt_rand(100000, 999999);

        $params = array(

            $_SESSION['USER_SMS_VALIDATE']
        );

        //尝试发送验证码并返回结果
        return $this->moduyun->sendWithParam(
            substr($areaCode, 1),
            $phoneNumber,
            $this->config->item('moduyun_config')['template']['validate_template'],
            $params
        );
    }


    //注册成功
    public function smsRegister($phoneNumber = '', $areaCode = '+86'){

        //尝试发送验证码并返回结果
        return $this->moduyun->sendWithParam(
            substr($areaCode, 1),
            $phoneNumber,
            $this->config->item('moduyun_config')['template']['register_template']
        );
    }


    //充值成功
    public function smsRecharge($phoneNumber = '', $areaCode = '+86', $amount = 0, $coinSymbol = ''){

        $params = array(
            'member',
            $amount,
            $coinSymbol
        );

        //尝试发送验证码并返回结果
        return $this->moduyun->sendWithParam(
            substr($areaCode, 1),
            $phoneNumber,
            $this->config->item('moduyun_config')['template']['recharge_template'],
            $params
        );
    }


    //提现成功
    public function smsWithdraw($phoneNumber = '', $areaCode = '+86', $amount = 0, $coinSymbol = ''){

        $params = array(
            'member',
            $amount,
            $coinSymbol
        );

        //尝试发送验证码并返回结果
        return $this->moduyun->sendWithParam(
            substr($areaCode, 1),
            $phoneNumber,
            $this->config->item('moduyun_config')['template']['withdraw_template'],
            $params
        );
    }
}
