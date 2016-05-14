<?php
return array(
//*************************************附加设置***********************************
    'SHOW_PAGE_TRACE'        => false,                           // 是否显示调试面板
    'URL_CASE_INSENSITIVE'   => false,                           // url区分大小写
    'TAGLIB_BUILD_IN'        => 'Cx,Common\Tag\My',              // 加载自定义标签
    'LOAD_EXT_CONFIG'        => 'db,alipay,oauth',               // 加载网站设置文件
    'TMPL_PARSE_STRING'      => array(                           // 定义常用路径
        '__OSS__'            => OSS_URL,
        '__PUBLIC__'         => OSS_URL.'/Public',
        '__HOME_CSS__'       => __ROOT__.trim(TMPL_PATH,'.').'Home/Public/css',
        '__HOME_JS__'        => __ROOT__.trim(TMPL_PATH,'.').'Home/Public/js',
        '__HOME_IMAGES__'    => OSS_URL.trim(TMPL_PATH,'.').'Home/Public/images',
        '__ADMIN_CSS__'      => __ROOT__.trim(TMPL_PATH,'.').'Admin/Public/css',
        '__ADMIN_JS__'       => __ROOT__.trim(TMPL_PATH,'.').'Admin/Public/js',
        '__ADMIN_IMAGES__'   => OSS_URL.trim(TMPL_PATH,'.').'Admin/Public/images',
        '__ADMIN_ACEADMIN__' => __ROOT__.trim(TMPL_PATH,'.').'Admin/Public/aceadmin',
        '__PUBLIC_CSS__'     => __ROOT__.trim(TMPL_PATH,'.').'Public/css',
        '__PUBLIC_JS__'      => __ROOT__.trim(TMPL_PATH,'.').'Public/js',
        '__PUBLIC_IMAGES__'  => OSS_URL.trim(TMPL_PATH,'.').'Public/images',
        '__USER_CSS__'       => __ROOT__.trim(TMPL_PATH,'.').'User/Public/css',
        '__USER_JS__'        => __ROOT__.trim(TMPL_PATH,'.').'User/Public/js',
        '__USER_IMAGES__'    => OSS_URL.trim(TMPL_PATH,'.').'User/Public/images',
        '__APP_CSS__'        => __ROOT__.trim(TMPL_PATH,'.').'App/Public/css',
        '__APP_JS__'         => __ROOT__.trim(TMPL_PATH,'.').'App/Public/js',
        '__APP_IMAGES__'     => OSS_URL.trim(TMPL_PATH,'.').'App/Public/images'
    ),
//***********************************URL设置**************************************
    'MODULE_ALLOW_LIST'      => array('Home','Admin','Api','User','App'), //允许访问列表
    'URL_HTML_SUFFIX'        => '',  // URL伪静态后缀设置
    'URL_MODEL'              => 2,  //启用rewrite
//***********************************SESSION设置**********************************
    'SESSION_OPTIONS'        => array(
        'name'               => 'BJYADMIN',//设置session名
        'expire'             => 24*3600*15, //SESSION保存15天
        'use_trans_sid'      => 1,//跨页传递
        'use_only_cookies'   => 0,//是否只开启基于cookies的session的会话方式
    ),
//***********************************页面设置**************************************
    'TMPL_EXCEPTION_FILE'    => APP_DEBUG ? THINK_PATH.'Tpl/think_exception.tpl' : './Template/default/Home/Public/404.html',
    'TMPL_ACTION_ERROR'      => TMPL_PATH.'/Public/dispatch_jump.tpl', // 默认错误跳转对应的模板文件
    'TMPL_ACTION_SUCCESS'    => TMPL_PATH.'/Public/dispatch_jump.tpl', // 默认成功跳转对应的模板文件
//***********************************auth设置**********************************
    'AUTH_CONFIG'            => array(
            'AUTH_USER'      => 'users'                         //用户信息表
        ),
//***********************************邮件服务器**********************************
    'EMAIL_FROM_NAME'        => '', // 发件人
    'EMAIL_SMTP'             => '', // smtp
    'EMAIL_USERNAME'         => '', // 账号
    'EMAIL_PASSWORD'         => '', //密码
//***********************************其他设置**********************************
    'RONG_IS_DEV'            => true,//是否是在开发中
    'RONG_DEV_APP_KEY'       => '', //融云开发环境下的key
    'RONG_DEV_APP_SECRET'    => '', //融云开发环境下的SECRET
    'RONG_PRO_APP_KEY'       => '', //融云生产环境下的key
    'RONG_PRO_APP_SECRET'    => '', //融云生产环境下的SECRET
    'UMENG_ISO_APP_KEY'      => '', //友盟key
    'UMENG_ISO_TIMESTAMP'    => '', //友盟timestamp
    'UMENG_ANDROID_APP_KEY'  => '', //友盟key
    'UMENG_ANDROID_TIMESTAMP'=> '', //友盟timestamp
    'RONGLIAN_ACCOUNT_SID'   => '', //容联云通讯 主账号 accountSid
    'RONGLIAN_ACCOUNT_TOKEN' => '', //容联云通讯 主账号token accountToken
    'RONGLIAN_APPID'         => '', //容联云通讯 应用Id appid
    'RONGLIAN_TEMPLATE_ID'   => '', //容联云通讯 模板Id
//***********************************缓存设置**********************************
    'DATA_CACHE_TIME'        => 1800,        // 数据缓存有效期s
    'DATA_CACHE_PREFIX'      => 'mem_',      // 缓存前缀
    'DATA_CACHE_TYPE'        => 'Memcached', // 数据缓存类型,
    'MEMCACHED_SERVER'       => '127.0.0.1', // 服务器ip
    'ALIOSS_CONFIG'          => array(
        'KEY_ID'             => '', // 阿里云oss key_id
        'KEY_SECRET'         => '', // 阿里云oss key_secret
        'END_POINT'          => '', // 阿里云oss endpoint
        'BUCKET'             => ''  // bucken 名称
        ),
    'NEED_UPLOAD_OSS'        => array( // 需要上传的目录
        '/Upload/avatar',
        '/Upload/cover',
        '/Upload/image/webuploader',
        '/Upload/video',
        ),
);
