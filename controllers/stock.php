<?php
/**
 *库存
 *
 */
class controllers_stock extends controllers_base{
	public function __construct(){
		parent::__construct();
		
		$eAdmin = lib_functions::getSession('eAdmin');
		if(empty($eAdmin)) {
			lib_functions::redirect('privilege/index');
		}
    
	}

	/**
	 *查看
	 *
	 */
	public function view() {

		$filter = array('page_size'=>20,'page'=>1);
		if(!isset($_REQUEST['query'])) {
			$this->warehouses = $this->model->table('warehouse')->fetchAll();
		}

		if(isset($_REQUEST['query'])) {
			$this->layout = null;
			$filter['page_size'] = empty($_REQUEST['page_size'])?20:intval($_REQUEST['page_size']);
			$filter['page'] = empty($_REQUEST['page'])?1:intval($_REQUEST['page']);
			$this->query = 1;
			$filter['query'] = 1;
			$filter['goods_key'] = !empty($_REQUEST['goods_key'])?$_REQUEST['goods_key']:null;
			$filter['barcode'] = !empty($_REQUEST['barcode'])?$_REQUEST['barcode']:null;
			$filter['warehouse_id'] = !empty($_REQUEST['warehouse_id'])?$_REQUEST['warehouse_id']:null;
		}
		$filter = $this->add_magic($filter);
		
		$where = " where 1=1 ";
		if(!empty($filter['goods_key'])) {
			$where .= " and (g.goods_name like '%{$filter['goods_key']}%' or g.goods_sn='{$filter['goods_key']}')";
		}
		if(!empty($filter['barcode'])) {
			$barcode = $this->model->table('barcode')->fetch(array('where'=>array('barcode'=>$filter['barcode'])));
			$where .= " and gs.goods_id='{$barcode['goods_id']}' and gs.color_id='{$barcode['color_id']}'
						and gs.size_id='{$barcode['size_id']}'";
		}
		if(!empty($filter['warehouse_id'])) {
			$where .= " and gs.warehouse_id = '{$filter['warehouse_id']}'";
		}

		$sql = "select count(*)
				from goods_stock gs
				inner join goods g on gs.goods_id=g.id
				inner join color c on c.id = gs.color_id
				inner join size s on  s.id = gs.size_id $where ";
		$num = $this->model->getOne($sql);

		$start = $filter['page_size'] * ($filter['page']-1);
		if($start > $num) {
			$start = 0;
		}

		$sql = "select gs.* ,g.goods_name,g.goods_sn,s.name as size_name,s.code as size_code,
				c.name as color_name,c.code as color_code
				from goods_stock gs
				inner join goods g on gs.goods_id=g.id
				inner join color c on c.id = gs.color_id
				inner join size s on  s.id = gs.size_id 
				$where 
				limit $start ,{$filter['page_size']}";
		$data = $this->model->getAll($sql);
		$list = array();

		$warehouse = $this->model->table('warehouse')->fetchAll();
		$warehouse_arr = array();
		foreach($warehouse as $v) {
			$warehouse_arr[$v['id']] = $v['name'];
		}

		foreach($data as $value) {
			$value['warehouse_name'] = $warehouse_arr[$value['warehouse_id']];
			$list[$value['goods_id']][] = $value;
		}
		$this->list = $list;
		//print_r($filter);exit;
		$this->pageinfo = $this->set_page($this->list,$num,$filter);
		
		if(isset($_REQUEST['query'])) {
			$this->make_json_exit('stock/view.php',$filter);
		}
		
	}

	/**
	 *修改警告库存
	 *
	 *
	 */
	 public function update_warn() {
		 $this->layout = null;
		$id = $this->_request('id');
		if(!empty($id)) {
			$value = $this->_request('value');
			$this->model->table('goods_stock')->update(array('id'=>$id),array('warn_quantity'=>$value));
			exit;
		}

	 }

	
}