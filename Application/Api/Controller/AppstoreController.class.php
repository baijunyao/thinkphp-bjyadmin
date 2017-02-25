<?php
namespace Api\Controller;
use Common\Controller\HomeBaseController;
/**
 * paypal支付
 */
class AppstoreController extends HomeBaseController{
    
    // 支付回调
    public function result(){
        //苹果内购的验证收据
        $receipt_data = I('post.apple_receipt'); 

        // 验证支付状态
        $result=validate_apple_pay($receipt_data);

        if($result['status']){
            // 验证通过 此处可以是修改数据库订单状态等操作
            
        }else{
            // 验证不通过

        }
    }


}