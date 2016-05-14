<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;
/**
 * 商城首页Controller
 */
class IndexController extends HomeBaseController{
	/**
	 * 首页
	 */
	public function index(){
        if(IS_POST){
            // 做一个简单的登录 组合where数组条件 
            $map=I('post.');
            $map['password']=md5($map['password']);
            $data=M('Users')->where($map)->find();
            if (empty($data)) {
                $this->error('账号或密码错误');
            }else{
                $_SESSION['user']=array(
                    'id'=>$data['id'],
                    'username'=>$data['username'],
                    'avatar'=>$data['avatar']
                    );
                $this->success('登录成功、前往管理后台',U('Admin/Index/index'));
            }
        }else{
            echo '当前状态：';
            echo check_login() ? $_SESSION['user']['username'].'已登录' : '未登录';
            $this->display();
        }
	}

    /**
     * 退出
     */
    public function logout(){
        session('user',null);
        $this->success('退出成功、前往登录页面',U('Home/Index/index'));
    }





}

