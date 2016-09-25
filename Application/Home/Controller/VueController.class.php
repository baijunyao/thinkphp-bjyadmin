<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;
/**
 * Vue示例
 */
class VueController extends HomeBaseController{

    /**
     * 拦截空方法 自动加载html
     * @param  string $methed_name 空方法
     */
    public function _empty($methed_name){
        $this->display($methed_name);
        exit(0);
    }

    /**
     * 配合thinkphp分页示例
     */
    public function page(){
        // 获取总条数
        $count=M('Province_city_area')->count();
        // 每页多少条数据
        $limit=200;
        $page=new \Org\Nx\Page($count,$limit);
        $data=M('Province_city_area')
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
        echo json_encode($data);
    }

}