<?php

/**
 * 微信公众号接口操作类
 * @author Huangyu
 */
class Wechat{

	/**
	 * 微信公众号唯一标识
	 * @var String
	 */
	private $appId = '';

	/**
	 * 微信公众号应用密钥
	 * @var String
	 */
	private $appSecret = '';

	/**
	 * 调试标识
	 * @var boolean
	 */
	private $debug = FALSE;

	/**
	 * 微信公众号接口错误码对照数组
	 * @var array
	 */
	private $errCodeArr = array(
		-1 	    => '系统繁忙，此时请开发者稍候再试',
		0 	    => '请求成功',
		40001   => '获取access_token时AppSecret错误，或者access_token无效。请开发者认真比对AppSecret的正确性，或查看是否正在为恰当的公众号调用接口',
		40002   => '不合法的凭证类型',
		40003   => '不合法的OpenID，请开发者确认OpenID（该用户）是否已关注公众号，或是否是其他公众号的OpenID',
		40004   => '不合法的媒体文件类型',
		40005   => '不合法的文件类型',
		40006   => '不合法的文件大小',
		40007   => '不合法的媒体文件id',
		40008   => '不合法的消息类型',
		40009   => '不合法的图片文件大小',
		40010   => '不合法的语音文件大小',
		40011   => '不合法的视频文件大小',
		40012   => '不合法的缩略图文件大小',
		40013   => '不合法的AppID，请开发者检查AppID的正确性，避免异常字符，注意大小写',
		40014   => '不合法的access_token，请开发者认真比对access_token的有效性（如是否过期），或查看是否正在为恰当的公众号调用接口',
		40015   => '不合法的菜单类型',
		40016   => '不合法的按钮个数',
		40017   => '不合法的按钮个数',
		40018   => '不合法的按钮名字长度',
		40019   => '不合法的按钮KEY长度',
		40020   => '不合法的按钮URL长度',
		40021   => '不合法的菜单版本号',
		40022   => '不合法的子菜单级数',
		40023   => '不合法的子菜单按钮个数',
		40024   => '不合法的子菜单按钮类型',
		40025   => '不合法的子菜单按钮名字长度',
		40026   => '不合法的子菜单按钮KEY长度',
		40027   => '不合法的子菜单按钮URL长度',
		40028   => '不合法的自定义菜单使用用户',
		40029   => '不合法的oauth_code',
		40030   => '不合法的refresh_token',
		40031   => '不合法的openid列表',
		40032   => '不合法的openid列表长度',
		40033   => '不合法的请求字符，不能包含\uxxxx格式的字符',
		40035   => '不合法的参数',
		40038   => '不合法的请求格式',
		40039   => '不合法的URL长度',
		40050   => '不合法的分组id',
		40051   => '分组名字不合法',
		40117   => '分组名字不合法',
		40118   => 'media_id大小不合法',
		40119   => 'button类型错误',
		40120   => 'button类型错误',
		40121   => '不合法的media_id类型',
		40132   => '微信号不合法',
		40137   => '不支持的图片格式',
		41001   => '缺少access_token参数',
		41002   => '缺少appid参数',
		41003   => '缺少refresh_token参数',
		41004   => '缺少secret参数',
		41005   => '缺少多媒体文件数据',
		41006   => '缺少media_id参数',
		41007   => '缺少子菜单数据',
		41008   => '缺少oauth code',
		41009   => '缺少openid',
		42001   => 'access_token超时，请检查access_token的有效期，请参考基础支持-获取access_token中，对access_token的详细机制说明',
		42002   => 'refresh_token超时',
		42003   => 'oauth_code超时',
		42007   => '用户修改微信密码，accesstoken和refreshtoken失效，需要重新授权',
		43001   => '需要GET请求',
		43002   => '需要POST请求',
		43003   => '需要HTTPS请求',
		43004   => '需要接收者关注',
		43005   => '需要好友关系',
		44001   => '多媒体文件为空',
		44002   => 'POST的数据包为空',
		44003   => '图文消息内容为空',
		44004   => '文本消息内容为空',
		45001   => '多媒体文件大小超过限制',
		45002   => '消息内容超过限制',
		45003   => '标题字段超过限制',
		45004   => '描述字段超过限制',
		45005   => '链接字段超过限制',
		45006   => '图片链接字段超过限制',
		45007   => '语音播放时间超过限制',
		45008   => '图文消息超过限制',
		45009   => '接口调用超过限制',
		45010   => '创建菜单个数超过限制',
		45011   => 'API调用太频繁，请稍候再试',
		45015   => '回复时间超过限制',
		45016   => '系统分组，不允许修改',
		45017   => '分组名字过长',
		45018   => '分组数量超过上限',
		45047   => '客服接口下行条数超过上限',
		46001   => '不存在媒体数据',
		46002   => '不存在的菜单版本',
		46003   => '不存在的菜单数据',
		46004   => '不存在的用户',
		47001   => '解析JSON/XML内容错误',
		48001   => 'api功能未授权，请确认公众号已获得该接口，可以在公众平台官网-开发者中心页中查看接口权限',
		48002   => '粉丝拒收消息（粉丝在公众号选项中，关闭了“接收消息”）',
		48004   => 'api接口被封禁，请登录mp.weixin.qq.com查看详情',
		48005   => 'api禁止删除被自动回复和自定义菜单引用的素材',
		48006   => 'api禁止清零调用次数，因为清零次数达到上限',
		50001   => '用户未授权该api',
		50002   => '用户受限，可能是违规后接口被封禁',
		61451   => '参数错误(invalid parameter)',
		61452   => '无效客服账号(invalid kf_account)',
		61453   => '客服帐号已存在(kf_account exsited)',
		61454   => '客服帐号名长度超过限制(仅允许10个英文字符，不包括@及@后的公众号的微信号)(invalid   kf_acount length)',
		61455   => '客服帐号名包含非法字符(仅允许英文+数字)(illegal character in   kf_account)',
		61456   => '客服帐号个数超过限制(10个客服账号)(kf_account count exceeded)',
		61457   => '无效头像文件类型(invalid   file type)',
		61450   => '系统错误(system error)',
		61500   => '日期格式错误',
		65301   => '不存在此menuid对应的个性化菜单',
		65302   => '没有相应的用户',
		65303   => '没有默认菜单，不能创建个性化菜单',
		65304   => 'MatchRule信息为空',
		65305   => '个性化菜单数量受限',
		65306   => '不支持个性化菜单的帐号',
		65307   => '个性化菜单信息为空',
		65308   => '包含没有响应类型的button',
		65309   => '个性化菜单开关处于关闭状态',
		65310   => '填写了省份或城市信息，国家信息不能为空',
		65311   => '填写了城市信息，省份信息不能为空',
		65312   => '不合法的国家信息',
		65313   => '不合法的省份信息',
		65314   => '不合法的城市信息',
		65316   => '该公众号的菜单设置了过多的域名外跳（最多跳转到3个域名的链接）',
		65317   => '不合法的URL',
		9001001 => 'POST数据参数不合法',
		9001002 => '远端服务不可用',
		9001003 => 'Ticket不合法',
		9001004 => '获取摇周边用户信息失败',
		9001005 => '获取商户信息失败',
		9001006 => '获取OpenID失败',
		9001007 => '上传文件缺失',
		9001008 => '上传素材的文件类型不合法',
		9001009 => '上传素材的文件尺寸不合法',
		9001010 => '上传失败',
		9001020 => '帐号不合法',
		9001021 => '已有设备激活率低于50%，不能新增设备',
		9001022 => '设备申请数不合法，必须为大于0的数字',
		9001023 => '已存在审核中的设备ID申请',
		9001024 => '一次查询设备ID数量不能超过50',
		9001025 => '设备ID不合法',
		9001026 => '页面ID不合法',
		9001027 => '页面参数不合法',
		9001028 => '一次删除页面ID数量不能超过10',
		9001029 => '页面已应用在设备中，请先解除应用关系再删除',
		9001030 => '一次查询页面ID数量不能超过50',
		9001031 => '时间区间不合法',
		9001032 => '保存设备与页面的绑定关系参数错误',
		9001033 => '门店ID不合法',
		9001034 => '设备备注信息过长',
		9001035 => '设备申请参数不合法',
		9001036 => '查询起始值begin不合法'
	);


	/**
	 * 构造函数，获取公众号标识和密钥
	 * @param String $appId     微信公众号唯一标识
	 * @param String $appSecret 微信公众号应用密钥
	 */
	public function __construct($configArray){

		$this->appId 	  = $configArray['appid'];
		$this->appSecret  = $configArray['appsecret'];
		$this->debug 	  = isset($configArray['debug']) ? $configArray['debug'] : FALSE;
	}


	/**
	 * 第一次配置微信开发者模式后需要验证第三方服务器，在域名首页调用这个方法就可以了，前后不能有任何输出
	 * @param  String $token 在公众号基本配置中设置的Token令牌，用来校验服务器
	 */
	public function startDevelop($token){

		//提取来自微信的请求中的验证签名
		$signature = $_GET['signature'];
		$timeStamp = $_GET['timestamp'];
		$nonce     = $_GET['nonce'];
		
		//检验签名
		$tempArr   = array($timeStamp, $nonce, $token);
		sort($tempArr);
		
		$tempStr   = implode('', $tempArr);
		$tempStr   = sha1($tempStr);
		
		//验证请求是否来自微信
		if($tempStr == $signature && isset($_GET['echostr'])){
		    
		    $echoStr = $_GET['echostr'];
		    echo $echoStr;
		}
	}


	/**
	 * 微信公众号接口通用Get方式Curl方法
	 * @param  String $url 请求地址
	 * @return Array       以数组形式返回请求到的数据
	 */
	private function wechatCurl($url, $data = ''){

		//初始化CURL对象
		$ch = curl_init();
		//配置Curl属性
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept-Charset: utf-8'));

		//如果有传入的数据数组，则使用POST请求方式
		if ($data) {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		}

		//由于没有配置信任的服务器HTTPS验证，直接跳过ssl检查项
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		//执行请求
		$rel = curl_exec($ch);
		//关闭请求
		curl_close($ch);

		$rel = json_decode($rel, true);
		$error = $this->isError($rel);

		if ($error) {
			
			if ($this->debug) {
				
				$rel = $error;
			}else{

				$rel = FALSE;
			}
		}

		return $rel;
	}


	/**
	 * 检测微信公众号接口返回数据是否为错误信息
	 * @param  Array   $result 返回数据转化成的数组
	 * @return Boolean         返回检测结果，正常数据返回false,错误信息返回错误代码和错误提示
	 */
	public function isError($result){

		$error = false;
		if (isset($result['errcode'])) {
			if ($result['errcode'] != 0) {
				$error['errcode'] = $result['errcode'];

				if(isset($this->errCodeArr[$error['errcode']])){

					$error['errmsg'] = $this->errCodeArr[$error['errcode']];
				}else{

					$error['errmsg'] = '未知错误';
				}
			}
		}

		return $error;
	}


	/**
	 * 获取微信公众号接口调用时需要用到的基础支持凭据access_token
	 * @return String | Boolean		以数组形式返回结果
	 */
	public function getAccessToken(){

		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appId.'&secret='.$this->appSecret;
		$accessToken = $this->wechatCurl($url);

		return $accessToken;
	}


	/**
	 * 获取微信服务器IP地址，用于对比请求来源，验证微信服务器
	 * @param  String $accessToken 公众号基础支持票据access_token
	 * @return Array               以数组形式返回结果
	 */
	public function getWechatIp($accessToken){

		$url = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token='.$accessToken;
		$wechatIp = $this->wechatCurl($url);

		return $wechatIp;
	}


	/**
	 * 添加公众号用户管理标签
	 * @param  String $accessToken 	公众号基础支持票据access_token
	 * @param  String $tagName     	添加的标签名
	 * @return Array 				以数组形式返回添加结果
	 */
	public function addTag($accessToken, $tagName){

		$url  = 'https://api.weixin.qq.com/cgi-bin/tags/create?access_token='.$accessToken;
		$data = array(
			'tag' => array(
				'name' => $tagName
			)
		);
		$result = $this->wechatCurl($url, $data);

		return $result;
	}


	/**
	 * 获取公众号已创建的用户管理标签
	 * @param  String $accessToken 公众号基础支持票据access_token
	 * @return array               以数组形式返回公众号已创建标签的集合
	 */
	public function getTags($accessToken){

		$url = 'https://api.weixin.qq.com/cgi-bin/tags/get?access_token='.$accessToken;
		$tags = $this->wechatCurl($url);

		return $tags;
	}


	/**
	 * 编辑公众号的用户管理标签
	 * @param  String $accessToken 公众号基础支持票据access_token
	 * @param  Int    $tagId       标签ID
	 * @param  String $tagName     新标签名
	 * @return Array               以数组形式返回更新操作的执行结果
	 */
	public function updateTag($accessToken, $tagId, $tagName){

		$url  = 'https://api.weixin.qq.com/cgi-bin/tags/update?access_token='.$accessToken;
		$data = array(
			'tag' => array(
				'id'   => $tagId,
				'name' => $tagName
			)
		);
		$result = $this->wechatCurl($url, $data);

		return $result;
	}


	/**
	 * 删除公众号的用户管理标签
	 * @param  String $accessToken 公众号基础支持票据access_token
	 * @param  Int    $tagId       标签ID
	 * @return Array               以数组形式返回删除操作的执行结果
	 */
	public function removeTag($accessToken, $tagId){

		$url = 'https://api.weixin.qq.com/cgi-bin/tags/delete?access_token='.$accessToken;
		$data = array(
			'tag' => array(
				'id' => $tagId
			)
		);
		$result = $this->wechatCurl($url, $data);

		return $result;
	}


	/**
	 * 获取公众号用户管理标签下的粉丝列表
	 * @param  String $accessToken 公众号基础支持票据access_token
	 * @param  Int    $tagId       标签ID
	 * @param  String $nextOpenId  第一个拉取的OPENID，不填默认从头开始拉取
	 * @return Array               以数组形式返回粉丝列表
	 */
	public function getTagUsers($accessToken, $tagId, $nextOpenId = ''){

		$url = 'https://api.weixin.qq.com/cgi-bin/user/tag/get?access_token='.$accessToken;
		$data = array(
			'tagid' => $tagId,
			'next_openid' => $nextOpenId
		);
		$users = $this->wechatCurl($url, $data);

		return $users;
	}


	/**
	 * 公众号批量为用户添加管理标签
	 * @param  String $accessToken 公众号基础支持票据access_token
	 * @param  Array  $openIdArr   一个或多个用户的OpenId组成的数组
	 * @param  Int    $tagId       标签ID
	 * @return Array 			   以数组形式返回添加操作的执行结果
	 */
	public function setTagToUsers($accessToken, $openIdArr, $tagId){

		$url = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token='.$accessToken;
		$data = array(
			'openid_list' => $openIdArr,
			'tagid'		  => $tagId
		);
		$result = $this->wechatCurl($url, $data);

		return $result;
	}


	/**
	 * 公众号批量为用户移除管理标签
	 * @param  String $accessToken 公众号基础支持票据access_token
	 * @param  Array  $openIdArr   一个或多个用户的OpenId组成的数组
	 * @param  Int    $tagId       标签ID
	 * @return Array 			   以数组形式返回添加操作的执行结果
	 */
	public function removeTagToUsers($accessToken, $openIdArr, $tagId){

		$url = 'https://api.weixin.qq.com/cgi-bin/tags/members/batchuntagging?access_token='.$accessToken;
		$data = array(
			'openid_list' => $openIdArr,
			'tagid'		  => $tagId
		);
		$result = $this->wechatCurl($url, $data);

		return $result;
	}


	/**
	 * 公众号获取粉丝身上的所有标签
	 * @param  String $accessToken 公众号基础支持票据access_token
	 * @param  String $openId      粉丝对公众号的唯一标识
	 * @return Array               以数组形式返回粉丝身上拥有的标签的ID集合
	 */
	public function getTagFromUser($accessToken, $openId){

		$url = 'https://api.weixin.qq.com/cgi-bin/tags/getidlist?access_token='.$accessToken;
		$data = array('openid' => $openId);
		$result = $this->wechatCurl($url, $data);

		return $result;
	}


	/**
	 * 公众号对指定用户设置备注名
	 * @param  String $accessToken 公众号基础支持票据access_token
	 * @param  String $openId      粉丝对公众号的唯一标识
	 * @param  String $remark      需要添加的备注
	 * @return Array               以数组形式反回操作的执行结果
	 */
	public function setUserRemark($accessToken, $openId, $remark){

		$url = 'https://api.weixin.qq.com/cgi-bin/user/info/updateremark?access_token='.$accessToken;
		$data = array(
			'openid' => $openId,
			'remark' => $remark
		);
		$result = $this->wechatCurl($url, $data);

		return $result;
	}


	/**
	 * 公众号获取粉丝的基本信息
	 * @param  String $accessToken 公众号基础支持票据access_token
	 * @param  String $openId      粉丝对公众号的唯一标识
	 * @param  String $lang        返回国家地区语言版本，zh_CN 简体，zh_TW 繁体，en 英语
	 * @return Array               以数组形式返回用户的基本信息
	 */
	public function getUserInfo($accessToken, $openId, $lang = 'zh_CN'){

		$url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$accessToken.'&openid='.$openId.'&lang='.$lang;
		$userInfo = $this->wechatCurl($url);

		return $userInfo;
	}


	/**
	 * 公众号获取粉丝的列表，一次拉取调用最多拉取10000个关注者的OpenID，可以将上一次调用得到的返回中的next_openid值，作为下一次调用中的next_openid值来反复拉取所有粉丝列表
	 * @param  String $accessToken 公众号基础支持票据access_token
	 * @param  String $nextOpenId  第一个拉取的OPENID，不填默认从头开始拉取
	 * @return Array               以数组形式返回结果
	 */
	public function getUserList($accessToken, $nextOpenId = ''){

		$url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$accessToken.'&next_openid='.$nextOpenId;
		$userList = $this->wechatCurl($url);

		return $userList;
	}


	/**
	 * 获取微信JS接口临时票据
	 * @param  String $accessToken 接口调用凭据
	 * @return Array               以数组形式返回结果
	 */
	public function getJspiTicket($accessToken){

		$url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$accessToken.'&type=jsapi';
		$result = $this->wechatCurl($url);

		return $result;
	}


	/**
	 * 获取微信JS接口权限验证需要的签名
	 * @param  String $jsapiTicket JS接口临时票据
	 * @param  Int    $timeStamp   指定时间戳
	 * @param  String $nonceStr    参与生成签名的随机字符串
	 * @param  String $url         当前网页的URL，不包含#及其后面部分
	 * @return String              返回微信JS接口权限验证的签名字符串
	 */
	public function getJsSignature($jsapiTicket, $timeStamp, $nonceStr, $url){

		//官方提供的签名算法：
		//步骤1. 对所有待签名参数按照字段名的ASCII 码从小到大排序（字典序）后，使用URL键值对的格式（即key1=value1&key2=value2…）拼接成字符串string1
		$signature = 'jsapi_ticket='.$jsapiTicket.'&noncestr='.$nonceStr.'&timestamp='.$timeStamp.'&url='.$url;

		//步骤2. 对string1进行sha1签名，得到signature
		$signature = sha1($signature);

		return $signature;
	}


	/**
	 * 获取微信网页授权的标准URL
	 * @param  String $redirectUrl 需要获取授权的业务实现URL
	 * @param  string $scope       应用授权作用域，取'snsapi_base'或'snsapi_userinfo'，默认为'snsapi_userinfo'
	 * @param  string $state       验证来源的字符串，用来交换验证请求是否来自微信
	 * @return String              返回微信网页授权的标准URL
	 */
	public function getOAuthUrl($redirectUrl, $scope = 'snsapi_userinfo', $state = ''){

		$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->appId.'&redirect_uri='.urlencode($redirectUrl).'&response_type=code&scope='.$scope.'&state='.$state.'#wechat_redirect';

		return $url;
	}


	/**
	 * 获取微信网页授权之后的后续操作需要用到的临时票据，和基础接口票据不同
	 * @param  String $code 用户同意授权后获得到的用来换取临时票据的票据
	 * @return Array        以数组形式返回结果
	 */
	public function getOAuthAccessToken($code){

		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appId.'&secret='.$this->appSecret.'&code='.$code.'&grant_type=authorization_code';
		$accessToken = $this->wechatCurl($url);

		return $accessToken;
	}


	/**
	 * 通过微信网页授权获得的票据获取用户基本信息
	 * @param  String $oauthAccessToken 微信网页授权后获取到的临时票据
	 * @param  String $openId           用户对于公众号的唯一标识
	 * @param  String $lang             返回国家地区语言版本，zh_CN 简体，zh_TW 繁体，en 英语
	 * @return Array                    以数组形式返回用户基本信息
	 */
	public function getUserInfoByOAuth($oauthAccessToken, $openId, $lang = 'zh_CN'){

		$url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$oauthAccessToken.'&openid='.$openId.'&lang='.$lang;
		$userInfo = $this->wechatCurl($url);

		return $userInfo;
	}

}