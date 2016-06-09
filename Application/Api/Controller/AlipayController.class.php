<?php
namespace Api\Controller;
use Common\Controller\HomeBaseController;
/**
 * 支付宝
 */
class AlipayController extends HomeBaseController{

    /**
     * return_url接收页面
     */
    public function alipay_return(){
        // 引入支付宝
        vendor('Alipay.AlipayNotify','','.class.php');
        $config=$config=C('ALIPAY_CONFIG');
        $notify=new \AlipayNotify($config);
        // 验证支付数据
        $status=$notify->verifyReturn();
        if($status){
            // 下面写验证通过的逻辑 比如说更改订单状态等等 $_GET['out_trade_no'] 为订单号；

            $this->success('支付成功',U('User/Order/index'));
        }else{
            $this->success('支付失败',U('User/Order/index'));
        }
    }
    
    /**
     * notify_url接收页面
     */
    public function alipay_notify(){
        // 引入支付宝
        vendor('Alipay.AlipayNotify','','.class.php');
        $config=$config=C('ALIPAY_CONFIG');
        $alipayNotify = new \AlipayNotify($config);
        // 验证支付数据
        $verify_result = $alipayNotify->verifyNotify();
        if($verify_result) {
            echo "success";
            // 下面写验证通过的逻辑 比如说更改订单状态等等 $_POST['out_trade_no'] 为订单号；

                        
        }else {
            echo "fail";
        }
    }

    
}