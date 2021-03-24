<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 前台法币交易控制器
 */
class Otc extends MY_Controller {


	public function __construct(){

		parent::__construct();

		$this->load->model('market_model');
		$this->load->model('coin_model');
		$this->load->model('ves_model');
		$this->load->model('user_model');
		$this->load->model('asset_model');
        $this->load->model('article_model');

		//设置资产计算保留的小数位精度
		bcscale($this->config->item('ex_asset_scale'));
	}


    public function index(){

        $otcType = 'buy';

        $data = array(

            'otcType' => $otcType
        );

        $this->load->view($this->viewPath . '/otc/otc', $data);
    }


    //收款方式
    public function pay(){

    	$this->user_model->checkLogin();

    	$user = $this->user_model->one($_SESSION['USER']['USER_ID']);

    	$data = array(

    	    'user' => $user
    	);

    	$this->load->view($this->viewPath . '/otc/pay', $data);
    }
}
