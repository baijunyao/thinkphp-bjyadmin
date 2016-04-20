<?php
namespace Common\Model;
use Common\Model\BaseModel;
/**
 * 第三方用户
 */
class OauthUserModel extends BaseModel{

    // 自动验证
    protected $_validate=array(
        array('type','require','类型必填'),
        array('nickname','require','昵称必填'),
        array('head_img','require','头像必填'),
        array('access_token','require','access_token必填'),
        );

    // 自动完成
    protected $_auto=array(
        array('create_time','time',1,'function'),
        array('last_login_time','time',3,'function'),
        );

    // 添加数据
    public function addData($add_data){
        if($data=$this->create($add_data)){
            $id=$this->add($data);
            return $id;
        }else{
            return false;
        }
    }

    /**
     * 获取token值
     * @param  integer  $uid  用户id
     * @param  integer $type  类型
     * @return string         token值
     */
    public function getToken($uid,$type){
        $map=array(
            'uid'=>$uid,
            'type'=>$type
            );
        $token=$this->where($map)->getField('access_token');
        return $token;
    }

}