<?php
/**
 *商品进货单 控制类
 *
 */
class controllers_goodsPurchase extends controllers_base {
	
	public $suppliers; //供应商
	public $list;

	public function __construct() {
		parent::__construct();

		$eAdmin = lib_functions::getSession('eAdmin');
		if(empty($eAdmin)) {
			lib_functions::redirect('privilege/index');
		}
	}

	public function getList() {
		$sql = "select gp.*,w.name as warehouse_name,s.name as supper
				from goods_purchase gp
				inner join warehouse w on w.id=gp.warehouse_id
				inner join suppliers s on s.id =gp.supper_id order by id desc";
		$this->list = $this->model->getAll($sql);

	}

	/**
	 *添加商品进货单
	 *
	 */
	public function add() {
		if(isset($_REQUEST['submit'])) {
			$djbh = $this->_request('djbh');
			$ywrq = $this->_request('ywrq');
			$supplier = $this->_request('supplier');
			$warehouse = $this->_request('warehouse');
			$desc = $this->_request('desc');
			$data = array(
				'djbh'=>$djbh,
				'ywrq'=>strtotime($ywrq),
				'supper_id'=>$supplier,
				'warehouse_id'=>$warehouse,
				'maker_id'=>isset($_SESSION['eAdmin_admin_id'])?$_SESSION['eAdmin_admin_id']:0,
				'maker'=>isset($_SESSION['eAdmin_admin_user'])?$_SESSION['eAdmin_admin_user']:'',
				'create_time'=>time(),
				'description'=>trim($desc)
			);
			$id = $this->model->table('goods_purchase')->insert($data);
			if($id) {
				$url = lib_functions::url('goodsPurchase/getList');
				header("location:{$url}");
				exit;
			}
		}

		$this->suppliers = $this->model->table('suppliers')->fetchAll();
		$this->warehouses = $this->model->table('warehouse')->fetchAll();
		$mdl = new models_goodsPurchase();
		$this->djbh = $mdl->create_djbh();
		
		//print_r($this->suppliers);exit;
	}

	/**
	 *编辑商品进货单
	 *
	 */
	 public function edit($id=null) {
		if(empty($id)) {
			exit('该单据不存在');
		}

		if(isset($_REQUEST['submit'])) {
			$djbh = $this->_request('djbh');
			$ywrq = $this->_request('ywrq');
			$supplier = $this->_request('supplier');
			$warehouse = $this->_request('warehouse');
			$desc = $this->_request('desc');
			$data = array(
				'djbh'=>$djbh,
				'ywrq'=>strtotime($ywrq),
				'supper_id'=>$supplier,
				'warehouse_id'=>$warehouse,
				'create_time'=>time(),
				'description'=>trim($desc)
			);

			$ret = $this->model->table('goods_purchase')->update(array('id'=>$id),$data);
			if($ret) {
				$url = lib_functions::url('goodsPurchase/view/'.$id);
				header("location:{$url}");
				exit;
			}
		}

		$this->id = $id;
		$sql = "select gp.*,w.name as warehouse_name,s.name as supper
				from goods_purchase gp
				inner join warehouse w on w.id=gp.warehouse_id
				inner join suppliers s on s.id =gp.supper_id
				where gp.id='{$id}'";
		$this->result = $this->model->getRow($sql);

		$this->suppliers = $this->model->table('suppliers')->fetchAll();
		$this->warehouses = $this->model->table('warehouse')->fetchAll();

		$sql = "select gpm.*,g.goods_sn,g.goods_name,
				c.name as color_name,c.code as color_code,
				s.name as size_name,s.code as size_code
				from goods_purchase_mx gpm,goods g,color c,size s
				where gpm.p_id='$id' and g.id=gpm.goods_id 
				and c.id=gpm.color_id and s.id=gpm.size_id";
		$this->goods = $this->model->getAll($sql);


	 }

	 /**
	  *查看
	  *
	  */
	  public function view($id) {
		if(empty($id)) {
			exit('该单据不存在');
		}

		$this->id = $id;
		$sql = "select gp.*,w.name as warehouse_name,s.name as supper
				from goods_purchase gp
				inner join warehouse w on w.id=gp.warehouse_id
				inner join suppliers s on s.id =gp.supper_id
				where gp.id='{$id}'";
		$this->result = $this->model->getRow($sql);

		$this->suppliers = $this->model->table('suppliers')->fetchAll();
		$this->warehouses = $this->model->table('warehouse')->fetchAll();

		$sql = "select gpm.*,g.goods_sn,g.goods_name,
				c.name as color_name,c.code as color_code,
				s.name as size_name,s.code as size_code
				from goods_purchase_mx gpm,goods g,color c,size s
				where gpm.p_id='$id' and g.id=gpm.goods_id 
				and c.id=gpm.color_id and s.id=gpm.size_id";
		$this->goods = $this->model->getAll($sql);
	  }

	  /**
	   *验收
	   *
	   */
	   public function ys($id) {
		   $this->layout = null;
			if(empty($id)) {
				exit(json_encode(array('status'=>-1,'msg'=>'该单据不存在')));
			}
			
			$goods = $this->model->table('goods_purchase_mx')->fetchAll(array(
				'where'=>array('p_id'=>$id)	
			));
			
			if(!empty($goods)) {
				$sql = "select warehouse_id from goods_purchase where id='{$id}'";
				$warehouse_id = $this->model->getOne($sql);
				//增加商品库存
				$mdl = new models_goods();
				foreach($goods as $value) {
					$data = array(
						'warehouse_id'=>$warehouse_id,
						'goods_id'=>$value['goods_id'],
						'size_id'=>$value['size_id'],
						'color_id'=>$value['color_id'],
						'goods_number'=>$value['goods_number']
					);
					$mdl->add_actual_stock($data);
				}

				$ret = $this->model->table('goods_purchase')->update(array('id'=>$id),array('status'=>1,'ys_time'=>time()));
				if($ret) {
					exit(json_encode(array('status'=>0,'msg'=>'操作成功')));
				}else{
					exit(json_encode(array('status'=>-1,'msg'=>'操作失败')));
				}
			}else {
				exit(json_encode(array('status'=>-1,'msg'=>'没有商品明细，不能验收')));
			}
		
	   }
		
	/**
	 *增加商品
	 *
	 */
	 public function add_goods($id = null) {
		if(empty($id)) {
			exit('该单据不存在');
		}

		$sql = "select djbh from goods_purchase where id='{$id}'";
		$djbh = $this->model->getOne($sql);

		$goods_sn = $_REQUEST['goods_sn'];
		$goods_id = $_REQUEST['goods_id'];

		//颜色尺码转化
		$mdl = new models_goodsPurchase();
		foreach($_REQUEST as $key => $value) {
			if(substr($key,0,3) == 'row') {
				if((int)$value) {
					$size_color_arr = explode('_',$key);
					$size_id = $size_color_arr[1];
					$color_id = $size_color_arr[2];
					$goods_number = (int)$value;

					$goods_arr = array(
						'djbh'=>$djbh,
						'p_id'=>$id,
						'goods_id' => $goods_id,
						'color_id' => $color_id,
						'size_id'  => $size_id,
						'goods_number'=> $goods_number
						);
				
					$mdl->add_goods($goods_arr);
				}
			}
		}

		exit("<script type='text/javascript'>window.close()</script>");

	 }


	
}