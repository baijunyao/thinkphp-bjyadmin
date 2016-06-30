<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台菜单管理
 */
class NavController extends AdminBaseController{
	/**
	 * 菜单列表
	 */
	public function index(){
		$data=D('AdminNav')->getTreeData('tree','order_number,id');
		$assign=array(
			'data'=>$data
			);
		$this->assign($assign);
		$this->display();
	}

	/**
	 * 添加菜单
	 */
	public function add(){
		$data=I('post.');
		unset($data['id']);
		D('AdminNav')->addData($data);
		$this->success('添加成功',U('Admin/Nav/index'));
	}

	/**
	 * 修改菜单
	 */
	public function edit(){
		$data=I('post.');
		$map=array(
			'id'=>$data['id']
			);
		D('AdminNav')->editData($map,$data);
		$this->success('修改成功',U('Admin/Nav/index'));
	}

	/**
	 * 删除菜单
	 */
	public function delete(){
		$id=I('get.id');
		$map=array(
			'id'=>$id
			);
		$result=D('AdminNav')->deleteData($map);
		if($result){
			$this->success('删除成功',U('Admin/Nav/index'));
		}else{
			$this->error('请先删除子菜单');
		}
	}

	/**
	 * 菜单排序
	 */
	public function order(){
		$data=I('post.');
		D('AdminNav')->orderData($data);
		$this->success('排序成功',U('Admin/Nav/index'));
	}


}
