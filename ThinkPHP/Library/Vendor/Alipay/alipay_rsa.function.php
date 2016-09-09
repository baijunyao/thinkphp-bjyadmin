<?php
/* *
 * RSA
 * 详细：RSA加密
 * 版本：3.3
 * 日期：2014-02-20
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
*/

/**
 * 签名字符串
 * @param $prestr 需要签名的字符串
 * return 签名结果
*/
function rsaSign($prestr) {
    $config=C('ALIPAY_CONFIG');
    $private_key=file_get_contents($config['private_key_path']);
    $pkeyid=openssl_get_privatekey($private_key);
    openssl_sign($prestr, $sign, $pkeyid);
    openssl_free_key($pkeyid);
    $sign=base64_encode($sign);
    return $sign;
}

/**
 * 验证签名
 * @param $prestr 需要签名的字符串
 * @param $sign 签名结果
 * return 签名结果
*/
function rsaVerify($prestr, $sign) {
    $sign=base64_decode($sign);
    $config=C('ALIPAY_CONFIG');
    $public_key=file_get_contents($config['public_key_path']);
    $pkeyid=openssl_get_publickey($public_key);
    if ($pkeyid) {
        $verify=openssl_verify($prestr, $sign, $pkeyid);
        openssl_free_key($pkeyid);
    }
    if($verify == 1){
        return true;
    }else{
        return false;
    }
 }