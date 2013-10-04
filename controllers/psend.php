<?php
class controllers_psend extends controllers_base {

	public function __construct(){
		parent::__construct();
	//	$this->layout = null;
		
		$eAdmin = lib_functions::getSession('eAdmin');
		if(empty($eAdmin)) {
			lib_functions::redirect('privilege/index');
		}
	}
		
	/**
	 *生成拣货单
	 *
	 */
	 public function create() {
		if(empty($_REQUEST['ids'])) {
			exit;
		}
		$order_ids = $_REQUEST['ids'];

		$sql = "select og.warehouse_id,oi.shipping_id, 
				og.order_id,og.order_sn,oi.deal_code,og.goods_id,
				og.color_id,og.size_id,og.goods_number,og.pay_fee
				from order_info oi,order_goods og
				where oi.id=og.order_id
				and oi.id in ($order_ids)";
				
		$order_data = $this->model->getAll($sql);
		if(empty($order_data)) {
			$ret = array('status'=>-1,'msg'=>'没有数据');
			exit(json_encode($ret));
		}

		//判断是否是同一仓库，快递公司
		$ship_id = $order_data[0]['shipping_id'];
		$warehouse_id = $order_data[0]['warehouse_id'];
		foreach($order_data as $v) {
			if($v['shipping_id'] != $ship_id && $v['warehouse_id'] != $warehouse_id) {
				$ret = array('status'=>-1,'msg'=>'条件不符合，不能一起拣货');
				exit(json_encode($ret));
			}
		}

		
		$mdl = new models_psend();
		$djbh = $mdl->create_djbh();

		$psend = array();
		try {
			$this->model->startTransaction();

			$psend = array(
					'djbh'=>$djbh,
					'warehouse_id'=>$order_data[0]['warehouse_id'],
					'ship_id'=>$order_data[0]['shipping_id'],
					'maker_id'=>isset($_SESSION['eAdmin_admin_id'])?$_SESSION['eAdmin_admin_id']:0,
					'maker'=>isset($_SESSION['eAdmin_admin_user'])?$_SESSION['eAdmin_admin_user']:'',
					'time'=>time()
			);
			$psend_id = $this->model->table('psend')->insert($psend);

			foreach($order_data as $value) {
				$psendlk_data = array(
					'p_id'	=> $psend_id,
					'djbh'=>$djbh,
					'order_id'=>$value['order_id'],
					'order_sn'=>$value['order_sn'],
					'deal_code'=>$value['deal_code'],
					'goods_id'=>$value['goods_id'],
					'size_id'=>$value['size_id'],
					'color_id'=>$value['color_id'],
					'goods_number'=>$value['goods_number'],
					'goods_price'=>$value['pay_fee']/$value['goods_number'],
					'pay_fee'=>$value['pay_fee']
				);
				$psendlk_id = $this->model->table('psendlk')->insert($psendlk_data);
			}

			$sql = "update order_info set status=3 where id in ($order_ids)";
			$this->model->query($sql);

			$this->model->commit();
		}catch(Exception $e) {
			$this->model->rollback();
			$ret = array('status'=>-1,'msg'=>'操作失败');
			exit(json_encode($ret));
		}
		$ret = array('status'=>0,'msg'=>'操作成功');
		exit(json_encode($ret));
   }

   /**
    *拣货单列表
	*
	*/
	public function getList() {
		$filter = array('page_size'=>20,'page'=>1);
		if(isset($_REQUEST['query'])) {
				$this->layout = null;
				$filter['page_size'] = empty($_REQUEST['page_size'])?20:intval($_REQUEST['page_size']);
				$filter['page'] = empty($_REQUEST['page'])?1:intval($_REQUEST['page']);
				$this->query = 1;
				$filter['query'] = 1;
				$filter['ship_id'] = !empty($_REQUEST['ship_id'])?$_REQUEST['ship_id']:null;
				$filter['warehouse_id'] = !empty($_REQUEST['warehouse_id'])?$_REQUEST['warehouse_id']:null;
				$filter['start_add_time'] = isset($_REQUEST['start_add_time'])?$_REQUEST['start_add_time']:null;
				$filter['end_add_time'] = isset($_REQUEST['end_add_time'])?$_REQUEST['end_add_time']:null;
		}
		$filter = $this->add_magic($filter);

		$where = " where 1=1 ";
		if(!empty($filter['ship_id'])) {
			$where .= " and ship_id = '{$filter['ship_id']}'";
		}
		if(!empty($filter['warehouse_id'])) {
			$where .= " and warehouse_id = '{$filter['warehouse_id']}'";
		}
		if(!empty($filter['start_add_time'])) {
			$time = strtotime($filter['start_add_time']);
			$where .= " and time >= '{$time}'";
		}
		if(!empty($filter['end_add_time'])) {
			$time = strtotime($filter['end_add_time']);
			$where .= " and time <= '{$time}'";
		}

		$sql = "select count(*) from psend $where";	
		$num = $this->model->getOne($sql);

		$start = $filter['page_size'] * ($filter['page']-1);
		if($start > $num) {
			$start = 0;
		}
		$sql = "select * from psend $where order by id desc limit $start ,{$filter['page_size']}";
		$this->list = $this->model->getAll($sql);

		$this->warehouse = $this->model->table('warehouse')->fetchAll();
		$this->ships = $this->model->table('ships')->fetchAll();
		$ship_arr = array();
		foreach($this->ships as $v) {
			$ship_arr[$v['id']] = $v['name'];
		}

		$warehouse_arr = array();
		foreach($this->warehouse as $v) {
			$warehouse_arr[$v['id']] = $v['name'];
		}

		foreach($this->list as &$v) {
			$v['warehouse_name'] = $warehouse_arr[$v['warehouse_id']];
			$v['ship_name'] = $ship_arr[$v['ship_id']];
		}

		$this->pageinfo = $this->set_page($this->list,$num,$filter);
		//print_r($filter);exit;
		if(isset($_REQUEST['query'])) {
			$this->make_json_exit('psend/getList.php',$filter);
		}
	}

	/**
	 *拣货单明细
	 *
	 */
	 public function info($id = null) {
		if(empty($id)) {
			exit('此单不存在');
		}
		$this->id = $id;
		$sql = "select p.* ,w.name as warehouse_name,s.name as ship_name
				from psend p
				inner join warehouse w on w.id=p.warehouse_id
				inner join ships s on s.id = p.ship_id
				where p.id = '{$id}'";
		$this->result = $this->model->getRow($sql);
		
		$sql = "select g.goods_name as goods_name,g.goods_sn,c.code as color_code,c.name as color_name,
				s.name as size_name,s.code as size_code,
				pl.*
				from psendlk pl
		
				inner join goods g on g.id=pl.goods_id
				inner join color c on c.id=pl.color_id
				inner join size s on s.id=pl.size_id
				where p_id = '{$id}'";
		$goods = $this->model->getAll($sql);

		$this->goods = array();
		foreach($goods as $v) {
			$order_sn = $v['order_sn'];
			$this->goods[$order_sn][] = $v; 
		}
	//	print_r($this->goods);exit;
	//	print_r($this->result);exit;
	 }

	 public function ys($id = null) {
		if(empty($id)) {
			exit('此单不存在');
		}
		$num = $this->model->table('psend')->update(array('id'=>$id),array('status'=>1));
		if($num) {
			$sql = "update order_info set status=4 where id in (select order_id from psendlk where p_id='{$id}')";
			$num = $this->model->query($sql);
			
			exit(json_encode(array('status'=>0,'msg'=>'验收成功！')));
		}else {
			exit(json_encode(array('status'=>-1,'msg'=>'验收失败！')));
		}
	 }

}