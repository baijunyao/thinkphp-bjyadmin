<?php
namespace Common\Model;
use Common\Model\BaseModel;
/**
 * 购物车Model
 */
class ShoppingCartModel extends BaseModel{

    /**
     * 增加购物车
     * @param integer $uid      用户id
     * @param integer $goods_id 产品id
     * @param integer $number   数量
     */
    public function addData($uid,$goods_id,$number=1){
        // 获取产品数据
        $goods_data=M('Product_goods')->find($goods_id);
        // 不存在
        if (empty($goods_data)) {
            $result=array(
                'error_code'=>1,
                'error_message'=>'产品不存在'
                );
            return $result;           
        }
        // 禁售
        if ($goods_data['status']==0) {
            $result=array(
                'error_code'=>1,
                'error_message'=>'此产品已经被禁售'
                );
            return $result;
        }
        // 下架
        if ($goods_data['status']==2) {
            $result=array(
                'error_code'=>1,
                'error_message'=>'此产品已经下架'
                );
            return $result;
        }

        // 获取购物车中的数据
        $map=array(
            'uid'=>$uid,
            'goods_id'=>$goods_id
            );
        $data=$this->where($map)->find();
        // 判断是否已存在
        if (empty($data)) {
            // 新增数据
            $shopping_data=array(
                'uid'=>$uid,
                'goods_id'=>$goods_id,
                'number'=>$number
                );
            $id=$this->add($shopping_data);
        }else{
            // 已存在、增加数量
            $number +=$data['number'];
            $this->setNumberById($uid,$goods_id,$number);
        }
        // 添加成功
        $result=array(
            'error_code'=>0,
            'error_message'=>'操作成功'
            );        
        return $result;
    }
        

    /**
     * 修改购物车数量
     * @param integer $uid      用户id
     * @param integer $goods_id 产品id
     * @param integer $number   数量
     */
    public function setNumberById($uid,$goods_id,$number){
        $map=array(
            'uid'=>$uid,
            'goods_id'=>$goods_id
            );
        $result=$this->where($map)->setField('number',$number);
        $result=array(
            'error_code'=>0,
            'error_message'=>'操作成功'
            );
        return $result;
    }

    /**
     * 删除购物车中的产品
     * @param  array $map where语句的数组形式
     * @return array      操作状态
     */
    public function deleteData($map){
        $this->where($map)->delete();
        $result=array(
            'error_code'=>0,
            'error_message'=>'操作成功'
            );
        return $result;
    }

    public function getCountData($uid,$goods_ids){
        
    }


}