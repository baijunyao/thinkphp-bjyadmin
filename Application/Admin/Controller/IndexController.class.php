<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台首页控制器
 */
class IndexController extends AdminBaseController{
	/**
	 * 首页
	 */
	public function index(){
		$this->display();
	}
	/**
	 * elements
	 */
	public function elements(){

		$this->display();
	}
	
	/**
	 * welcome
	 */
	public function welcome(){
	    $this->display();
	}



}
