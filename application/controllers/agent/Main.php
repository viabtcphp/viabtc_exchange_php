<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *代理商公共控制器
 */
class Main extends CI_Controller {


	/**
	 * 构造函数
	 */
	public function __construct(){

		parent::__construct();

		$this->load->model('user_model');
		$this->load->model('sysconfig_model');
	}


	/**
	 *代理商首页入口
	 */
	public function index(){

		//检测代理商登陆
		$this->user_model->checkLogin(FALSE, TRUE);

		$_SESSION['SYSCONFIG'] = $this->sysconfig_model->getFormatSysconfig();

		$this->load->view('agent/index');
	}
}