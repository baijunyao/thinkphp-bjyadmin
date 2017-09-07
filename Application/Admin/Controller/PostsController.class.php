<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 文章
 */
class PostsController extends AdminBaseController{
    /**
     * 文章列表
     */
    public function index(){
        $this->display();
    }

}