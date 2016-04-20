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
            // 获取订单id
            $order_id=D('Order')->getFieldByOrderSn($_GET['out_trade_no'],'id');
            // 更改订单状态
            D('Order')->changeStatus($order_id,1);
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
            // 获取订单id
            $order_id=D('Order')->getFieldByOrderSn($_POST['out_trade_no'],'id');
            // 更改订单状态
            D('Order')->changeStatus($order_id,1);
            // 将支付宝返回的数据保存到本地数据库
            $alipay_order=M('Alipay_order')
                ->field('id')
                ->where(array('product_order_sn'=>$_POST['out_trade_no']))
                ->find();
            if(empty($alipay_order)){
                $data=array(
                    'product_order_sn'=>$_POST['out_trade_no'],
                    'alipay_sn'=>$_POST['trade_no'],
                    'price'=>$_POST['price'],
                    'buyer_email'=>$_POST['buyer_email'],
                    'pay_time'=>strtotime($_POST['gmt_create']),
                    'detail'=>json_encode($_POST),
                    );
                M('Alipay_order')->add($data);
            }
            echo "success";
        }else {
            echo "fail";
        }
    }

    
}