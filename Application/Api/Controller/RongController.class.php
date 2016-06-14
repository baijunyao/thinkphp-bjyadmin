<?php
namespace Api\Controller;
use Common\Controller\UserBaseController;
/**
 * 融云api接口
 */
class RongController extends UserBaseController{
    /**
     * 获取token
     */
    public function get_token(){
        // 获取用户id
        $uid=get_uid();
        // 获取token
        $token=get_rongcloud_token($uid);
        $data=array(
            'token'=>$token
            );
        ajax_return($data,'获取成功',0);
    }
    
    /**
     * 传递一个、或者多个用户id
     * 获取用户头像用户名；用来组合成好友列表
     */
    public function get_user_info(){
        $uids=I('post.uids');
        // 组合where数组条件
        $map=array(
            'id'=>array('in',$uids)
            );
        $data=M('Users')
            ->field('id,username,avatar')
            ->where($map)
            ->select();
        ajax_return($data,'获取用户数据成功',0);
    }


}