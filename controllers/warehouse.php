<?php
/**
 *店铺
 *
 */
class controllers_warehouse extends controllers_base{
	
	public function __construct(){
		parent::__construct();
		
		$eAdmin = lib_functions::getSession('eAdmin');
		if(empty($eAdmin)) {
			lib_functions::redirect('privilege/index');
		}
    
	}

	public function add() {
		if(isset($_REQUEST['submit'])) {			
			$data = array(
				'description'=>$this->_request('desc'),
				'name'=>$this->_request('name')
			);
		
			$id = $this->model->table('warehouse')->insert($data);
			$url = lib_functions::url('warehouse/getList');
			header("location:$url");
		}
	}
	
	public function getList() {
		$this->list = $this->model->table('warehouse')->fetchAll();
	}

	public function delete($id=null) {
		if(empty($id)) {
			exit('该仓库不存在');
		}

		$this->model->table('warehouse')->delete(array('id'=>$id));
		$url = lib_functions::url('warehouse/getList');
		header("location:$url");
	}

	/**
	 *仓库店铺关联
	 *
	 */
	public function ware_shop() {
		$list = $this->model->table('shop_ware')->fetchAll();
		foreach($list as $v) {
			$this->list[$v['shop_id']][] = $v;
		}

		$this->view = 'warehouse/relation_list';
	}

	/**
	 *添加仓库店铺关联
	 *
	 */
	public function add_relation() {
		if(isset($_REQUEST['submit'])) {
			$shop_id = $this->_request('shop');
			$warehouse_id = $this->_request('warehouse');
			if(!empty($shop_id) && !empty($warehouse_id)) {
				$shop = $this->model->table('shops')->fetch(array('where'=>array('id'=>$shop_id)));
				$warehouse = $this->model->table('warehouse')->fetch(array('where'=>array('id'=>$warehouse_id)));

				$insert_data = array(
					'shop_id'=>$shop_id,
					'shop_code'=>$shop['code'],
					'shop_name'=>$shop['name'],
					'ware_id'=>$warehouse_id,
					'ware_name'=>$warehouse['name'],
					'level'=>$this->_request('level')
					);
				$this->model->table('shop_ware')->insert($insert_data);
				$url = lib_functions::url('warehouse/ware_shop');
				header('location:'.$url);
			}
			exit;
		}
		$this->shops = $this->model->table('shops')->fetchAll();
		$this->warehouses = $this->model->table('warehouse')->fetchAll();
		$this->view = 'warehouse/relation';
	}

	public function delete_relation($id = null) {
		if(empty($id)) {
			exit('该关系不存在');
		}
		$ret = $this->model->table('shop_ware')->delete(array('id'=>$id));
		if($ret) {
			$url = lib_functions::url('warehouse/ware_shop');
			header('location:'.$url);
		}
	}

	
}