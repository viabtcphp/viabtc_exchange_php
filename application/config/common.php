<?php
defined("BASEPATH") OR exit("No direct script access allowed");

//自定义配置文件


/*
|--------------------------------------------------------------------------
| 项目总体设置
|--------------------------------------------------------------------------
*/
//项目名称
$config['app_name'] = '';
//后端上传文件保存总路径
$config['upload_path'] = '/attachment';
//上传文件大小限制，单位(kb)
$config['upload_image_max_size'] = 128;

//客户端Cookie过期时间，当前设置： 7天
$config['cookie_out_time'] = APP_TIME + 604800;


/*
|--------------------------------------------------------------------------
| 交易所设置
|--------------------------------------------------------------------------
*/

//币币核心
$config['ves_host'] = 'http://172.29.242.181:8080';
// $config['ves_host'] = 'https://www.earthcoin.pro/qoweiavoadlada';
$config['ves_ws_host'] = 'wss://www.earthcoin.pro/wss';

//火币API配置
$config['huobi_access_key'] = '';
$config['huobi_secret_key'] = '';
$config['huobi_api_host'] = 'api.huobi.pro';
//备用
// $config['huobi_api_host'] = 'api-aws.huobi.pro';

//U盾配置
$config['udun_url'] = 'https://hk06-hk-node.uduncloud.com';
$config['udun_key'] = '3a3e2ce94fa69569ed71dda2823be543';
$config['udun_merchant'] = '';
$config['udun_wallet'] = '0';
$config['udun_recharge_callback'] = 'common/udun_callback';
$config['udun_withdraw_callback'] = 'common/udun_callback';

//摩杜云短信
$config['moduyun_config'] = array(

    'account' => array(

        'accesskey' => '',
        'secretkey' => '',
        'sign' => ''
    ),

    'template' => array(

        //模板ID
        'validate_template' => '',
        'register_template' => '',
        'recharge_template' => '',
        'withdraw_template' => '',
    )
);

//默认语言，必须在启用的语言里有这一个语言
$config['_lang'] = 'traditional';
//启用的语言
$config['_language_list'] = array(

    'traditional'   => '繁體中文',
    'english'       => 'English',
    'japanese'      => '日本語',
    'korean'        => '한국어'
);

//OTC支付方式
$config['otc_pay_type'] = array(

    'bank' => array(

        //后台名称
        'name' => '银行卡',
        //多语言字段
        'language_key' => 'otc_pay_bank',
        //支付方式是否需要上传图片
        'need_image' => FALSE,
        //银行名称
        'bank_name' => array(
            '工商银行',
            '建设银行',
            '交通银行',
            '农村商业银行',
            '中国银行',
            '招商银行',
            '农业银行',
            '民生银行',
            '邮政储蓄',
            '华夏银行',
            '广发银行'
        ),
        //开户行
        'bank_address' => TRUE,
        //支付方式支持的货币
        'unit' => 'CNY'
    ),

    'wechat' => array(

        'name' => '微信',
        'language_key' => 'otc_pay_wechat',
        'need_image' => TRUE,
        'bank_name' => FALSE,
        'bank_address' => FALSE,
        'unit' => 'CNY'
    ),

    'alipay' => array(

        'name' => '支付宝',
        'language_key' => 'otc_pay_alipay',
        'need_image' => TRUE,
        'bank_name' => FALSE,
        'bank_address' => FALSE,
        'unit' => 'CNY'
    ),

    'paypal' => array(

        'name' => 'Paypal',
        'language_key' => 'otc_pay_paypal',
        'need_image' => FALSE,
        'bank_name' => FALSE,
        'bank_address' => FALSE,
        'unit' => 'CNY,USD,GBP,JPY,FRF,HKD,CHF,CAD,NLG,DEM,BEF,AUD'
    )
);

//OTC法币单位列表
$config['otc_adv_unit'] = array(

    'CNY',  //人民币
    'USD',  //美元
    'GBP',  //英镑
    'JPY',  //日元
    'FRF',  //法国法郎
    'HKD',  //港元
    'CHF',  //瑞士法郎
    'CAD',  //加拿大元
    'NLG',  //荷兰盾
    'DEM',  //德国马克
    'BEF',  //比利时法郎
    'AUD'   //澳大利亚元
);

//文章分类
$config['article_type'] = array(

    0 => '滚动图',
    1 => '公告中心',
    2 => '关于我们',
    3 => '产品服务',
    4 => '用户支持',
    5 => '客户服务'
);

//手机国家码列表
$config['phone_area_code'] = array(

    '+1', '+1242', '+1246', '+1264', '+1268', '+1284', '+1340', '+1345', '+1441', '+1473', '+1649', '+1664', '+1671', '+1758', '+1767', '+1784', '+1787', '+1809', '+1868', '+1869', '+1876', '+20', '+211', '+212', '+213', '+216', '+218', '+220', '+221', '+222', '+223', '+224', '+225', '+226', '+227', '+228', '+229', '+230', '+231', '+232', '+233', '+234', '+235', '+236', '+237', '+238', '+239', '+240', '+241', '+242', '+243', '+244', '+245', '+247', '+248', '+249', '+250', '+251', '+252', '+253', '+254', '+255', '+256', '+257', '+258', '+260', '+261', '+262', '+263', '+264', '+265', '+266', '+267', '+268', '+269', '+27', '+291', '+297', '+298', '+299', '+30', '+31', '+32', '+33', '+34', '+350', '+351', '+352', '+353', '+354', '+355', '+356', '+357', '+358', '+359', '+36', '+370', '+371', '+372', '+373', '+374', '+375', '+376', '+377', '+378', '+380', '+381', '+382', '+385', '+386', '+387', '+389', '+39', '+40', '+41', '+420', '+421', '+423', '+43', '+44', '+45', '+46', '+47', '+48', '+49', '+500', '+501', '+502', '+503', '+504', '+505', '+506', '+507', '+508', '+509', '+51', '+52', '+53', '+54', '+55', '+56', '+57', '+58', '+590', '+591', '+592', '+593', '+594', '+595', '+596', '+597', '+598', '+599', '+60', '+61', '+62', '+63', '+64', '+65', '+66', '+670', '+673', '+675', '+676', '+677', '+678', '+679', '+680', '+682', '+684', '+685', '+687', '+688', '+689', '+691', '+7', '+784', '+809', '+81', '+82', '+84', '+852', '+853', '+855', '+856', '+880', '+886', '+90', '+91', '+92', '+93', '+94', '+95', '+960', '+961', '+962', '+963', '+964', '+965', '+966', '+967', '+968', '+970', '+971', '+972', '+973', '+974', '+975', '+976', '+977', '+98', '+992', '+993', '+994', '+995', '+996', '+998'
);
//默认国家码列表
$config['phone_area_code_default'] = '+81';

//合约结算币种ID
$config['dm_money_coin'] = 2;
$config['dm_money_symbol'] = 'USDT';

//合约插针循环次数
$config['dm_pin_loop'] = 20;
//合约插针每次数量
$config['dm_pin_amount'] = 1000;

//U盾主链配置
$config['udun_chain'] = array(

    0       => 'BTC',
    60      => 'ETH',
    520     => 'CNT',
    5       => 'DASH',
    133     => 'ZEC',
    145     => 'BCH',
    61      => 'ETC',
    2       => 'LTC',
    2301    => 'QTUM',
    502     => 'GCC',
    144     => 'XRP',
    194     => 'EOS',
    2304    => 'IOTE',
    2303    => 'VDS',
    195     => 'TRX',
    -1       => '多链'
);

//memo基数
$config['udun_memo_start'] = 1000000;

//用户资产计算时保留的小数位精度
$config['ex_asset_scale'] = 8;

//版块组约定
$config['ex_plate_list'] = array(

	1 => array(

		'plate_id' 	 	=> 1,
		'plate_text' 	=> '币币',
		'plate_status'	=> 1
	),
	2 => array(

		'plate_id' 	 	=> 2,
		'plate_text' 	=> '法币',
		'plate_status'	=> 0
	),
	3 => array(

		'plate_id' 	 	=> 3,
		'plate_text' 	=> '杠杆',
		'plate_status'	=> 0
	),
	4 => array(

		'plate_id' 	 	=> 4,
		'plate_text' 	=> '合约',
		'plate_status'	=> 0
	)
);

//用户资产操作映射约定
$config['ex_asset_action_list'] = array(

	1 => array(

		'asset_action_id' 	 	=> 1,
		'asset_action_text' 	=> '充值',
	),
	2 => array(

		'asset_action_id' 	 	=> 2,
		'asset_action_text' 	=> '提现',
	),
	3 => array(

		'asset_action_id' 	 	=> 3,
		'asset_action_text' 	=> '转入',
	),
	4 => array(

		'asset_action_id' 	 	=> 4,
		'asset_action_text' 	=> '转出',
	),
	5 => array(

		'asset_action_id' 	 	=> 5,
		'asset_action_text' 	=> '后台增加',
	),
	6 => array(

		'asset_action_id' 	 	=> 6,
		'asset_action_text' 	=> '后台扣除',
	),
    7 => array(

        'asset_action_id'       => 7,
        'asset_action_text'     => '合约盈利',
    ),
    8 => array(

        'asset_action_id'       => 8,
        'asset_action_text'     => '合约开仓手续费',
    ),
    9 => array(

        'asset_action_id'       => 9,
        'asset_action_text'     => '合约亏损',
    )
);

/*
|--------------------------------------------------------------------------
| 项目后台设置
|--------------------------------------------------------------------------
*/
//管理员登陆超时时间：当前设置为 30 分钟
$config['manage_logout_time'] = 86400;
//后台数据分页，在当前页码左右两侧显示的页码数量
$config['manage_page']  = 5;

/*
|--------------------------------------------------------------------------
| 项目前台设置
|--------------------------------------------------------------------------
*/
//用户登陆超时时间：当前设置为 30 分钟
$config['user_logout_time'] = 86400;
//设置前后台分页页码数量
$config['home_page']	= 5;
