<?php

/**
 * 短信宝短信发送类
 */
class Smsbao {
	

	/**
	 * 错误对照
	 */
	private $status_str = array(

		'0'  => '短信发送成功',
		'-1' => '参数不全',
		'-2' => '服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！',
		'30' => '密码错误',
		'40' => '账号不存在',
		'41' => '余额不足',
		'42' => '帐户已过期',
		'43' => 'IP地址限制',
		'50' => '内容含有敏感词',
		'51' => '手机号码不正确'
	);


	/**
	 * 请求域名
	 */
	private $host = 'http://api.smsbao.com/';


	/**
	 * 帐户和密码
	 */
	private $user = '';
	private $password = '';

	/**
	 * 调试开关
	 */
	private $debug = FALSE;

	
	/**
	 * 构造函数,初始化设置
	 * @param array $params array(
	 *                      	'user' => 短信宝用户名,
	 *                      	'password' => 短信宝帐户密码,
	 *                      	'debug' => 可选,调试开关,开启调试传入TRUE
	 * 						)
	 */
	public function __construct($params = array()){

		$this->user = isset($params['user']) ? $params['user'] : '';
		$this->password = isset($params['password']) ? md5($params['password']) : '';

		$this->debug = isset($params['debug']) ? $params['debug'] : FALSE;
	}


	/**
	 * 发送短信
	 * @param  string $_phone   		手机号码
	 * @param  string $_content 		发送内容
	 * @param  bool   $_phoneArea		国际区号
	 * @return bool|string           	未开启调试返回布尔型，发送成功返回TURE，发送失败返回FALSE，
	 *         					     	开启调试后，返回发送状态对应的字符串
	 */
	public function send($_phone = '', $_content = '', $_phoneArea = '+86'){

		$sendurl = '';

		if ($_phoneArea == '+86') {
			
			$sendurl = $this->china_send($_phone, $_content);
		}else{

			$_phone = $_phoneArea . $_phone;
			$sendurl = $this->international_send($_phone, $_content);
		}

		$result = file_get_contents($sendurl);

		return $this->debug ? $this->status_str[$result] : ($result === '0' ? TRUE : FALSE);
	}


	/**
	 * 国内发送
	 * @param  string $_phone   手机号码
	 * @param  string $_content 发送内容
	 * @return string           返回发送结果
	 */
	public function china_send($_phone = '', $_content = ''){

		return $this->host . 'sms?u=' . $this->user . '&p=' . $this->password . '&m=' . $_phone . '&c=' . urlencode($_content);
	}


	/**
	 * 国际发送
	 * @param  string $_phone   手机号码，格式为 +[国际地区号][手机号],base64编码
	 * @param  string $_content 发送内容
	 * @return string           返回发送结果
	 */
	public function international_send($_phone = '', $_content = ''){

		return $this->host . 'wsms?u=' . $this->user . '&p=' . $this->password . '&m=' . urlencode($_phone) . '&c=' . urlencode($_content);
	}
}