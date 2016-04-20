<?php
namespace User\Controller;
use Common\Controller\UserBaseController;
/**
 * 购物车
 */
class CartController extends UserBaseController{
    /**
     * 添加购物车
     */
    public function add(){
        $result=D('ShoppingCart')->addData(72,40,4);
        p($result);die;
        
    }

    /**
     * 修改购物车
     */
    public function edit(){
        $result=D('ShoppingCart')->setNumberById(72,40,4);
        p($result);die;
    }

    /**
     * 删除购物车
     */
    public function delete(){
        $result=D('ShoppingCart')->deleteData(array('uid'=>72,'goods_id'=>40));
        p($result);die;
        
    }



}