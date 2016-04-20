<?php
namespace Common\Controller;
use Common\Controller\BaseController;
/**
 * 用户基类控制器
 */
class UserBaseController extends BaseController{
	/**
	 * 初始化方法
	 */
	public function _initialize(){
		parent::_initialize();
       // 添加不需要验证是否登录的连接
        $not_need_login=array(
            'User/Goods/app_buy'
            );
        $action=trim(__ACTION__,'/');
        if (!in_array($action, $not_need_login)) {
            // 检测是否登录
            if (!check_login()) {
                if (IS_APP || IS_AJAX) {
                    ajax_return('','您需要登录！',1);
                }else{
                    $this->error('您需要登录！');
                }
            }            
        }
        
	}



}

