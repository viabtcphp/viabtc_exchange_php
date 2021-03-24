<?php

/**
 * 赛邮类
 */
class Mysubmail {


	/**
	 * 帐户和密码
	 */
	private $config = array();

	
	/**
	 * 构造函数,初始化设置
	 */
	public function __construct($params = array()){

		//载入SDK
		require_once(dirname(__FILE__) . '/SUBMAIL_PHP_SDK/SUBMAILAutoload.php');

		$this->config = $params;
	}


	/**
	 * 发送短信
	 * @param  string $phone     手机号
	 * @param  string $phoneArea 地区号
	 * @param  string $template  赛邮短信模板ID
	 * @param  array  $params    模板变量数组，变量名 => 变量值
	 * @return bool              返回操作结果，操作成功返回true，操作失败返回false
	 */
	public function sms($phone = '', $phoneArea = '+86', $template, $params = array()){

		$result = FALSE;

		if ($phoneArea == '+86') {
			
			$result = $sendurl = $this->china_sms($phone, $template, $params);
		}else{

			$phone = $phoneArea . $phone;
			$result = $sendurl = $this->international_sms($phone, $template, $params);
		}

		return $result;
	}


	/**
	 * 发送国内短信
	 * @param  string $phone     手机号
	 * @param  string $template  赛邮短信模板ID
	 * @param  array  $params    模板变量数组，变量名 => 变量值
	 * @return bool              返回操作结果，操作成功返回true，操作失败返回false
	 */
	public function china_sms($phone, $template, $params = array()){

		$result = FALSE;

		$submail = new MESSAGEXsend($this->config['sms']['sms']['config']);

		$submail->SetTo($phone);
		$submail->SetProject($template);

		if (count($params)) {
			
			foreach ($params as $key => $value) {
				
				$submail->AddVar($key, $value);
			}
		}
		$ret = $submail->xsend();

		if (is_array($ret) && isset($ret['status']) && $ret['status'] == 'success') {
			
			$result = TRUE;
		}

		return $result;
	}


	/**
	 * 发送国际短信，不能发国内短信
	 * @param  string $phone     前面包含地区码的完整手机号
	 * @param  string $template  赛邮短信模板ID
	 * @param  array  $params    模板变量数组，变量名 => 变量值
	 * @return bool              返回操作结果，操作成功返回true，操作失败返回false
	 */
	public function international_sms($phone, $template, $params = array()){

		$result = FALSE;

		$submail = new INTERNATIONALSMSXsend($this->config['sms']['internactionalsms']['config']);

		$submail->SetTo($phone);
		$submail->SetProject($template);

		if (count($params)) {
			
			foreach ($params as $key => $value) {
				
				$submail->AddVar($key, $value);
			}
		}

		$ret = $submail->xsend();

		if (is_array($ret) && isset($ret['status']) && $ret['status'] == 'success') {
			
			$result = TRUE;
		}

		return $result;
	}
}