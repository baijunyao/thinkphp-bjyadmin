<?php
return array(
    'ALIPAY_CONFIG'         => array(
        'partner'           => '', // partner 从支付宝商户版个人中心获取
        'seller_email'      => '', // email 从支付宝商户版个人中心获取
        'key'               => '', // key 从支付宝商户版个人中心获取
        'sign_type'         => strtoupper(trim('RSA')), // 可选md5  和 RSA 
        'input_charset'     => 'utf-8', // 编码
        'cacert'            => VENDOR_PATH.'Alipay/cacert.pem',  // cacert.pem存放的位置
        'transport'         => 'http', // 协议
        'private_key_path'  => '' //移动端生成的私有key文件存放于服务器的 绝对路径
        )
);
