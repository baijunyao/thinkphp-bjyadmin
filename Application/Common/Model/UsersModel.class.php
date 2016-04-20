<?php
namespace Common\Model;
use Common\Model\BaseModel;
/**
 * ModelName
 */
class UsersModel extends BaseModel{



    /**
     * 删除数据
     * @param   array   $map    where语句数组形式
     * @return  boolean         操作是否成功
     */
    public function deleteData($map){
        die('禁止删除用户');
    }



}
