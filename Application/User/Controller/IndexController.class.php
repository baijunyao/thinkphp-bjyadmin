<?php
namespace User\Controller;
use Common\Controller\UserBaseController;
/**
 * 认证控制器
 */
class IndexController extends UserBaseController{
 
    /**
     * 个人中心首页
     */
    public function index(){
        $this->display();
    }



}
