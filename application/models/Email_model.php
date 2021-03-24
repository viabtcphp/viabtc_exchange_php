<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 邮件模型
 */
class Email_model extends MY_Model {


    /**
     * 构造函数，初始化
     */
    public function __construct(){

        parent::__construct();

        //加载发送邮件类库
        $this->load->library('sendEmail');
    }


    /**
     * 向指定邮箱发送一条验证码
     * @param  string $emailAddress 邮箱地址
     * @return bool                 返回发送结果，发送成功返回TRUE，发送失败返回FALSE
     */
    public function emailValidate($emailAddress = ''){

        //生成验证码
        $_SESSION['USER_EMAIL_VALIDATE'] = mt_rand(100000, 999999);

        $this->sendemail->mailConfig();

        $this->sendemail->mail(
            $_SESSION['SYSCONFIG']['sysconfig_email_tpl_validate_title'],
            str_replace('{code}', $_SESSION['USER_EMAIL_VALIDATE'], $_SESSION['SYSCONFIG']['sysconfig_email_tpl_validate_content']),
            FALSE,
            $_SESSION['SYSCONFIG']['sysconfig_email_account'],
            $_SESSION['SYSCONFIG']['sysconfig_email_password'],
            $_SESSION['SYSCONFIG']['sysconfig_email_smtp_port'],
            $_SESSION['SYSCONFIG']['sysconfig_email_smtp_host'],
            $emailAddress,
            FALSE,
            FALSE,
            $_SESSION['SYSCONFIG']['sysconfig_email_from']
        );

        //尝试发送验证码并返回结果
        return $this->sendemail->sendMail();
    }


    /**
     * 校验邮箱验证码
     * @param  string $validate 需要校验的验证码
     * @return bool             返回校验结果，校验一致返回TRUE，校验不一致返回FALSE
     */
    public function checkEmailValidate($validate){

        return (intval($validate) === $_SESSION['USER_EMAIL_VALIDATE']);
    }


    //注册成功
    public function emailRegister($emailAddress = ''){

        $this->sendemail->mailConfig();

        $this->sendemail->mail(
            $_SESSION['SYSCONFIG']['sysconfig_email_tpl_register_title'],
            str_replace('{email}', $emailAddress, $_SESSION['SYSCONFIG']['sysconfig_email_tpl_register_content']),
            FALSE,
            $_SESSION['SYSCONFIG']['sysconfig_email_account'],
            $_SESSION['SYSCONFIG']['sysconfig_email_password'],
            $_SESSION['SYSCONFIG']['sysconfig_email_smtp_port'],
            $_SESSION['SYSCONFIG']['sysconfig_email_smtp_host'],
            $emailAddress,
            FALSE,
            FALSE,
            $_SESSION['SYSCONFIG']['sysconfig_email_from']
        );

        //尝试发送验证码并返回结果
        return $this->sendemail->sendMail();
    }


    //充值成功
    public function emailRecharge($emailAddress = '', $amount = 0, $coinSymbol = ''){

        $this->sendemail->mailConfig();

        $content = str_replace('{email}', $emailAddress, $_SESSION['SYSCONFIG']['sysconfig_email_tpl_recharge_content']);
        $content = str_replace('{amount}', $amount, $content);
        $content = str_replace('{coin}', $coinSymbol, $content);

        $this->sendemail->mail(
            $_SESSION['SYSCONFIG']['sysconfig_email_tpl_recharge_title'],
            $content,
            FALSE,
            $_SESSION['SYSCONFIG']['sysconfig_email_account'],
            $_SESSION['SYSCONFIG']['sysconfig_email_password'],
            $_SESSION['SYSCONFIG']['sysconfig_email_smtp_port'],
            $_SESSION['SYSCONFIG']['sysconfig_email_smtp_host'],
            $emailAddress,
            FALSE,
            FALSE,
            $_SESSION['SYSCONFIG']['sysconfig_email_from']
        );

        //尝试发送验证码并返回结果
        return $this->sendemail->sendMail();
    }


    //提现成功
    public function emailWithdraw($emailAddress = '', $amount = 0, $coinSymbol = ''){

        $this->sendemail->mailConfig();

        $content = str_replace('{email}', $emailAddress, $_SESSION['SYSCONFIG']['sysconfig_email_tpl_withdraw_content']);
        $content = str_replace('{amount}', $amount, $content);
        $content = str_replace('{coin}', $coinSymbol, $content);

        $this->sendemail->mail(
            $_SESSION['SYSCONFIG']['sysconfig_email_tpl_withdraw_title'],
            $content,
            FALSE,
            $_SESSION['SYSCONFIG']['sysconfig_email_account'],
            $_SESSION['SYSCONFIG']['sysconfig_email_password'],
            $_SESSION['SYSCONFIG']['sysconfig_email_smtp_port'],
            $_SESSION['SYSCONFIG']['sysconfig_email_smtp_host'],
            $emailAddress,
            FALSE,
            FALSE,
            $_SESSION['SYSCONFIG']['sysconfig_email_from']
        );

        //尝试发送验证码并返回结果
        return $this->sendemail->sendMail();
    }
}
