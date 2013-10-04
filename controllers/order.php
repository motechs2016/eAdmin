<?php
class controllers_order extends controllers_base {
	public $shop_from; //商店分类
	public $ships; //快递公司
	public $payments; //支付方式
	public $provinces;//省份
	public $cities;//省份
	public $order_info;//订单
	public $order_list;

	public function __construct(){
		parent::__construct();
	//	$this->layout = null;
		
		$eAdmin = lib_functions::getSession('eAdmin');
		if(empty($eAdmin)) {
			lib_functions::redirect('privilege/index');
		}
	}
	
	/**
	 *追加订单
	 *
	 */
	 public function add() {
		//商店分类
		$this->shop_from = $this->model->table('shop_class')->fetchAll();
		//快递公司
		$this->ships = $this->model->table('ships')->fetchAll(
			array('where'=>array('enabled'=>1))
		);
		//支付方式
		$this->payments = $this->model->table('payments')->fetchAll(
			array('where'=>array('enabled'=>1))
		);

		//省份
		$this->provinces = $this->model->table('region')->fetchAll(
				array('where'=>array('region_type'=>1))
			);

		
		if(isset($_REQUEST['shop_class_code'])) {
			$shops = $this->model->table('shops')->fetchAll(
				array('where'=>array('class_code'=>$_REQUEST['shop_class_code']))
			);
			
			exit(json_encode($shops));
		}

		if(isset($_REQUEST['province'])) {
			$cities = $this->model->table('region')->fetchAll(
				array('where'=>array('region_type'=>2,'parent_id'=>$_REQUEST['province']))
			);
			
			exit(json_encode($cities));
		}

		if(isset($_REQUEST['city'])) {
			$district = $this->model->table('region')->fetchAll(
				array('where'=>array('region_type'=>3,'parent_id'=>$_REQUEST['city']))
			);
			
			exit(json_encode($district));
		}


	 }

	 /**
	  *订单保存
	  *
	  */
	  public function save() {
		//  $order_source = $this->_request('order_source');
		
			$order = array(
				'shop_id'=> $this->_request('select_shop'),
				'add_time' => strtotime($this->_request('add_time')),
				'shipping_id' => $this->_request('shipping_id'),
				'pay_id' => $this->_request('pay_id'),
				'deal_code' => $this->_request('deal_code'),
				'bz' => $this->_request('bz'),
				'email' => $this->_request('email'),
				'consignee' => $this->_request('consignee'),
				'province_id' => $this->_request('province'),
				'city_id' => $this->_request('city'),
				'district_id' => $this->_request('district'),
				'address' => $this->_request('address'),
				'tel' => $this->_request('tel'),
				'mobile' => $this->_request('mobile'),
				'zipcode' => $this->_request('zipcode')
				);
		
			$this->model->startTransaction();
			//保存用户
			$user_name = $this->_request('buyer_name');
			$user = new models_user();
			
			//添加用户
			$user_info = array(
				'sex'=> 1,
				'password'=>md5(8888),
				'shop_id'=>$order['shop_id'],
				'email'=>$order['email'],
				'last_login'=>time(),
				'last_ip'=>$_SERVER['REMOTE_ADDR']
				);
			
			$user_id = $user->add($user_name,$user_info);

			//添加用户address
			$address_info = array(
				'province'=>$order['province_id'],
				'city'=>$order['city_id'],
				'district'=>$order['district_id'],
				'address'=>$order['address'],
				'tel'=>$order['tel'],
				'mobile'=>$order['mobile'],
				'consignee'=>$user_name
				);

			$user->insert_address($user_id,$address_info);

			$order['user_id'] = $user_id;
			$order['order_sn'] = $order['deal_code'];
			$order_id = $this->model->table('order_info')->insert($order);

			$this->model->commit();
			$url = lib_functions::url('order/info/'.$order_id);
			header("location:$url");
	  }

	  /**
	   *
	   *订单详情
	   */
	   public function info($order_id) {
			$sql = "select o.*,sp.name as shop_name,u.user_name
					from order_info as o,shops sp,users as u
					where  o.shop_id = sp.id 
					and u.id=o.user_id  and o.id='{$order_id}'";
			
			$this->order_info = $this->model->getRow($sql);
			//支付方式
			$sql = "select id,name  from payments";
			$this->payments = $this->model->getAll($sql);
			//快递方式
			$sql = "select id,name from ships";
			$this->ships = $this->model->getAll($sql);
			//省份
			$this->provinces = $this->model->table('region')->fetchAll(
					array('where'=>array('region_type'=>1))
				);
			$this->cities = $this->model->table('region')->fetchAll(
					array('where'=>array('region_type'=>2,'parent_id'=>$this->order_info['province_id']))
				);
			$this->districtes = $this->model->table('region')->fetchAll(
					array('where'=>array('region_type'=>3,'parent_id'=>$this->order_info['city_id']))
				);
		//	print_r($this->order_info);exit;
			$sql = "select og.*,g.goods_name as goods_name,c.name as color_name,s.name as size_name,
					g.market_price,g.shop_price
					from order_goods og,goods g, color c,size s
					where og.goods_id = g.id and og.color_id = c.id and og.size_id = s.id 
					and order_id = '{$order_id}'";

			$this->order_goods = $this->model->getAll($sql);

		//	print_r($this->order_goods);exit;
	   }

	  /***
	   *
	   *订单列表
	   *
	   */
	   public function getList() {
		    $filter = array('page_size'=>20,'page'=>1);
			if(!isset($_REQUEST['query'])) {
				$this->ships = $this->model->table('ships')->fetchAll();
				$this->shops = $this->model->table('shops')->fetchAll();
				$this->payments = $this->model->table('payments')->fetchAll();
			}

			if(isset($_REQUEST['query'])) {
				$this->layout = null;
				$filter['page_size'] = empty($_REQUEST['page_size'])?20:intval($_REQUEST['page_size']);
				$filter['page'] = empty($_REQUEST['page'])?1:intval($_REQUEST['page']);
				$this->query = 1;
				$filter['query'] = 1;
				$filter['order_sn'] = !empty($_REQUEST['order_sn'])?$_REQUEST['order_sn']:null;
				$filter['deal_code'] = !empty($_REQUEST['deal_code'])?$_REQUEST['deal_code']:null;
				$filter['shop_id'] = !empty($_REQUEST['shop_id'])?$_REQUEST['shop_id']:null;
				$filter['consignee'] = !empty($_REQUEST['consignee'])?$_REQUEST['consignee']:null;
				$filter['status'] = isset($_REQUEST['status'])?$_REQUEST['status']:null;
				$filter['pay_status'] = isset($_REQUEST['pay_status'])?$_REQUEST['pay_status']:null;
				$filter['ship_id'] = isset($_REQUEST['ship_id'])?$_REQUEST['ship_id']:null;
				$filter['pay_id'] = isset($_REQUEST['pay_id'])?$_REQUEST['pay_id']:null;
				$filter['start_add_time'] = isset($_REQUEST['start_add_time'])?$_REQUEST['start_add_time']:null;
				$filter['end_add_time'] = isset($_REQUEST['end_add_time'])?$_REQUEST['end_add_time']:null;
			}
			$filter = $this->add_magic($filter);

			$where = '';
			if(!empty($filter['order_sn'])) {
				$where .= " and oi.order_sn = '{$filter['order_sn']}'";
			}
			if(!empty($filter['deal_code'])) {
				$where .= " and oi.deal_code = '{$filter['deal_code']}'";
			}
			if(!empty($filter['shop_id'])) {
				$where .= " and oi.shop_id = '{$filter['shop_id']}'";
			}
			if(!empty($filter['consignee'])) {
				$where .= " and oi.consignee = '{$filter['consignee']}'";
			}
			if(!empty($filter['ship_id'])) {
				$where .= " and oi.shipping_id = '{$filter['ship_id']}'";
			}
			if(!empty($filter['pay_id'])) {
				$where .= " and oi.pay_id = '{$filter['pay_id']}'";
			}
			if(isset($filter['status']) && $filter['status'] != '') {
				$where .= " and oi.status = '{$filter['status']}'";
			}
			if(isset($filter['pay_status']) && $filter['pay_status'] != '') {
				$where .= " and oi.pay_status = '{$filter['pay_status']}'";
			}
			if(!empty($filter['start_add_time'])) {
				$where .= " and oi.add_time >= '".strtotime($filter['start_add_time'])."'";
			}
			if(!empty($filter['end_add_time'])) {
				$where .= " and oi.add_time <= '".strtotime($filter['end_add_time'])."'";
			}
			
			$sql = "select count(*) from order_info oi where 1=1 $where";
		
			$num = $this->model->getOne($sql);

			$start = $filter['page_size'] * ($filter['page']-1);
			if($start > $num) {
				$start = 0;
			}

			$sql = "select oi.*,s.name as shop_name from order_info as oi,shops as s 
					where oi.shop_id = s.id
					$where 
					limit $start ,{$filter['page_size']}";
			$this->order_list = $this->model->getAll($sql);
			
			$this->pageinfo = $this->set_page($this->order_list,$num,$filter);

			if(isset($_REQUEST['query'])) {
				$this->make_json_exit('order/getList.php',$filter);
			}
	   }

	   /**
	    *订单添加商品
		*
		*/
		public function add_goods() {
			$goods_sn = $_REQUEST['goods_sn'];
			$goods_id = $_REQUEST['goods_id'];
			$order_id = $_REQUEST['order_id'];
			$market_price = $_REQUEST['market_price'];
			$shop_price = $_REQUEST['shop_price'];
			
			$sql = "select order_sn from order_info where id='{$order_id}'";
			$order_sn = $this->model->getOne($sql);

			//颜色尺码转化
			$mdl = new models_order();

			foreach($_REQUEST as $key => $value) {
				if(substr($key,0,3) == 'row') {
					if((int)$value) {
						$size_color_arr = explode('_',$key);
						$size_id = $size_color_arr[1];
						$color_id = $size_color_arr[2];
						$goods_number = (int)$value;
						$goods_amount = $market_price * $goods_number;
						$pay_fee = $shop_price * $goods_number;
						$card_fee = $goods_amount - $pay_fee;
						
						$sql = "select code from color where id='{$color_id}'";
						$color_code = $this->model->getOne($sql);
						$sql = "select code from size where id='{$size_id}'";
						$size_code = $this->model->getOne($sql);

						$goods_arr = array(
							'order_sn' => $order_sn,
							'order_id' => $order_id,
							'goods_id' => $goods_id,
							'goods_sn' => $goods_sn,
							'color_id' => $color_id,
							'size_id'  => $size_id,
							'color_code' => $color_code,
							'size_code'=> $size_code,
							'goods_number'=> $goods_number,
							'goods_amount'=> $goods_amount,
							'card_fee' => $card_fee,
							'pay_fee' => $pay_fee
							);
							
						$mdl->add_goods($goods_arr);
					}
				}
			}
			//更新主单
			$mdl->update_je_sl($order_id);
			/*
			$url = lib_functions::url("goods/select_goods_one/{$goods_id}?order_id=".$order_id);
			header('location:'.$url);
			*/
			exit("<script type='text/javascript'>window.close()</script>");

		//	print_r($_REQUEST);exit;
			
		}


		/** 
		 *编辑商品
		 *
		 */
		 public function edit_goods() {
			
			 //编辑保存
			if(isset($_POST['submit'])) {
				$order_goods_id = $this->_request('order_goods_id');
				$market_price = $this->_request('market_price');
				$shop_price = $this->_request('shop_price');
				$num = $this->_request('goods_number');
				$goods_amount = $num*$market_price;
				$pay_fee = $shop_price * $num;
				$card_fee = $goods_amount-$pay_fee;
			
				$sql = "update order_goods set goods_number='{$num}',
						goods_amount='{$goods_amount}',card_fee='{$card_fee}',
						pay_fee='{$pay_fee}' where id='{$order_goods_id}'";
				
				$ret = $this->model->query($sql);
				//更新主单
				$order_id = $this->model->getOne("select order_id from order_goods where id='{$order_goods_id}'");
				$mdl = new models_order();
				$mdl->update_je_sl($order_id);

				if($ret) {
					exit(json_encode(array('code'=>0,'msg'=>'保存成功')));
				}else {
					exit(json_encode(array('code'=>-1,'msg'=>'保存失败')));
				}
			}else {
				$id = $this->_request('id');
				$sql = "select og.*,g.market_price,g.shop_price ,g.goods_name as goods_name
						from order_goods og, goods g
						where og.goods_id=g.id and og.id = '{$id}'";
				$this->goods = $this->model->getRow($sql);

				$sql = "select s.id,s.name from goods_size gs,size s where gs.size_id=s.id and gs.goods_id='{$this->goods['goods_id']}'";
				$this->sizes = $this->model->getAll($sql);

				$sql = "select c.id,c.name from goods_color gc,color c  where gc.color_id=c.id and gc.goods_id='{$this->goods['goods_id']}'";
				$this->colors = $this->model->getAll($sql);
			}

		 }

		 /**
		  *删除商品
		  *
		  */
		 public function remove_goods() {
			$id = $this->_request('id');
			$order_id = $this->model->getOne("select order_id from order_goods where id='{$id}'");

			$sql = "delete from order_goods where id='{$id}'";
			$this->model->query($sql);
			
			//更新主单
			
			$mdl = new models_order();
			$mdl->update_je_sl($order_id);
		 }

		 public function edit_base_info() {
			$this->layout = null;
			$id = $this->_request('id');
			$ship_id = $this->_request('ship_id');
			$pay_id = $this->_request('pay_id');
			$sql = "update order_info set pay_id='{$pay_id}',shipping_id='{$ship_id}' where id='{$id}'";

			$this->model->query($sql);
			exit();
		 }

		 public function edit_consignee_info() {
			$this->layout = null;
			$id = $this->_request('id');
			$consignee = $this->_request('consignee');
			$address = $this->_request('address');
			$bz = $this->_request('bz');
			$email = $this->_request('email');
			$zipcode = $this->_request('zipcode');
			$tel = $this->_request('tel');
			$mobile = $this->_request('mobile');
			$province = $this->_request('province');
			$city = $this->_request('city');
			$district = $this->_request('district');
			$discount = $this->_request('discount');
			$shipping_fee = $this->_request('shipping_fee');

			$sql = "update order_info set 
				consignee='{$consignee}',
				address='{$address}',
				bz='{$bz}',
				email='{$email}',
				zipcode='{$zipcode}',
				tel='{$tel}',
				mobile='{$mobile}',
				province_id='{$province}',
				city_id='{$city}',
				district_id='{$district}',
				discount='{$discount}',
				shipping_fee='{$shipping_fee}',
				total_amount = goods_amount-goods_discount-discount+shipping_fee
				where id='{$id}'";

			$this->model->query($sql);
			exit();
		 }

		 /**
		  *付款
		  *
		  */
		  public function pay_money() {
				$status = $this->_request('status');
				$order_id = $this->_request('order_id');
				$sql = "select count(*) from order_goods where order_id='{$order_id}'";
				$num = $this->model->getOne($sql);
				if($num == 0) {
					exit(json_encode(array('status'=>-1,'msg'=>'没有商品，不能付款！')));
				}
				//$ret = $this->model->table('order_info')->update(array('id'=>$order_id),array('pay_status'=>$status));
				if($status == 0) { //取消付款
					$this->model->table('order_goods')->update(array('order_id'=>$order_id),array('is_separate'=>0));

					$sql = "update order_info set pay_status = $status,pay_money=0,is_separate=0,pay_time=0 where id='{$order_id}'";

				}else{  //付款
					$time = time();
					$sql = "update order_info set pay_status = $status,pay_money=total_amount,pay_time='{$time}' where id='{$order_id}'";

					//锁定库存
					$mdl= new models_goods();
					
					$mdl->order_add_lock_stock($order_id);
					
				}
				$ret = $this->model->query($sql);
				$msg = $status == 0?'付款取消':'付款';
				if($ret) {
					exit(json_encode(array('status'=>0,'msg'=>$msg.'成功！')));
				}else {
					exit(json_encode(array('status'=>-1,'msg'=>$msg.'失败！')));
				}
		  }

		 /**
		  *确认
		  *
		  */
		  public function confirm() {
				$status = $this->_request('status');
				$order_id = $this->_request('order_id');
				if($status == 0) {
					$ret = $this->model->table('order_info')->update(array('id'=>$order_id,'status'=>1,'is_guaqi'=>0),array('status'=>$status,'confirm_time'=>0));
				}else {
					$sql = "select count(*) from order_goods where order_id='{$order_id}'";
					$num = $this->model->getOne($sql);
					if($num == 0) {
						exit(json_encode(array('status'=>-1,'msg'=>'没有商品，不能确认！')));
					}
					
					$ret = $this->model->table('order_info')->update(array('id'=>$order_id,'status'=>0,'is_guaqi'=>0,'is_separate'=>0),array('status'=>$status,'confirm_time'=>time()));
				
				}
				$msg = $status == 0?'确认取消':'确认';
				if($ret) {
					exit(json_encode(array('status'=>0,'msg'=>$msg.'成功！')));
				}else {
					exit(json_encode(array('status'=>-1,'msg'=>$msg.'失败！')));
				}
		  }
		 /**
		  *通知配货
		  *
		  */
		  public function notice_peihuo() {
				$status = $this->_request('status');
				$order_id = $this->_request('order_id');
				$msg = $status == 0?'通知配货取消':'通知配货';

				if($status == 0) {
					$ret = $this->model->table('order_info')->update(array('id'=>$order_id,'pay_status'=>1,'status'=>2,'is_guaqi'=>0),array('status'=>1,'peihuo_time'=>0));
				}else{
					$ret = $this->model->table('order_info')->update(
						array('id'=>$order_id,'pay_status'=>1,'status'=>1,'is_guaqi'=>0),
						array('status'=>2,'peihuo_time'=>time())
					);
				}
				
				if($ret) {
					exit(json_encode(array('status'=>0,'msg'=>$msg.'成功！')));
				}else {
					exit(json_encode(array('status'=>-1,'msg'=>$msg.'失败！')));
				}
		  }

		  /**
		   *挂起操作
		   *
		   */
		   public function guaqi() {
				$status = $this->_request('status');
				$order_id = $this->_request('order_id');
				$msg = $status == 0?'挂起取消':'挂起';

				if($status == 0) {
					$ret = $this->model->table('order_info')->update(array('id'=>$order_id),array('is_guaqi'=>0));
				}else{
					$ret = $this->model->table('order_info')->update(array('id'=>$order_id),array('is_guaqi'=>1));
				}
				
				if($ret) {
					exit(json_encode(array('status'=>0,'msg'=>$msg.'成功！')));
				}else {
					exit(json_encode(array('status'=>-1,'msg'=>$msg.'失败！')));
				}
		  }

	  /***
	   *
	   *待配货订单列表
	   *
	   */
	   public function torange() {
		    $filter = array('page_size'=>20,'page'=>1);
			if(!isset($_REQUEST['query'])) {
				$this->ships = $this->model->table('ships')->fetchAll();
				$this->shops = $this->model->table('shops')->fetchAll();
				$this->payments = $this->model->table('payments')->fetchAll();
			}

			if(isset($_REQUEST['query'])) {
				$this->layout = null;
				$filter['page_size'] = empty($_REQUEST['page_size'])?20:intval($_REQUEST['page_size']);
				$filter['page'] = empty($_REQUEST['page'])?1:intval($_REQUEST['page']);
				$this->query = 1;
				$filter['query'] = 1;
				$filter['order_sn'] = !empty($_REQUEST['order_sn'])?$_REQUEST['order_sn']:null;
				$filter['deal_code'] = !empty($_REQUEST['deal_code'])?$_REQUEST['deal_code']:null;
				$filter['shop_id'] = !empty($_REQUEST['shop_id'])?$_REQUEST['shop_id']:null;
				$filter['consignee'] = !empty($_REQUEST['consignee'])?$_REQUEST['consignee']:null;
				$filter['status'] = $_REQUEST['status'];
				$filter['pay_status'] = $_REQUEST['pay_status'];
				$filter['ship_id'] = $_REQUEST['ship_id'];
				$filter['pay_id'] = $_REQUEST['pay_id'];
				$filter['start_add_time'] = $_REQUEST['start_add_time'];
				$filter['end_add_time'] = $_REQUEST['end_add_time'];
			}
			$filter = $this->add_magic($filter);

			$where = '';
			if(!empty($filter['order_sn'])) {
				$where .= " and oi.order_sn = '{$filter['order_sn']}'";
			}
			if(!empty($filter['deal_code'])) {
				$where .= " and oi.deal_code = '{$filter['deal_code']}'";
			}
			if(!empty($filter['shop_id'])) {
				$where .= " and oi.shop_id = '{$filter['shop_id']}'";
			}
			if(!empty($filter['consignee'])) {
				$where .= " and oi.consignee = '{$filter['consignee']}'";
			}
			if(!empty($filter['ship_id'])) {
				$where .= " and oi.shipping_id = '{$filter['ship_id']}'";
			}
			if(!empty($filter['pay_id'])) {
				$where .= " and oi.pay_id = '{$filter['pay_id']}'";
			}
			if(isset($filter['status']) && $filter['status'] != '') {
				$where .= " and oi.status = '{$filter['status']}'";
			}
			if(isset($filter['pay_status']) && $filter['pay_status'] != '') {
				$where .= " and oi.pay_status = '{$filter['pay_status']}'";
			}
			if(!empty($filter['start_add_time'])) {
				$where .= " and oi.add_time >= '".strtotime($filter['start_add_time'])."'";
			}
			if(!empty($filter['end_add_time'])) {
				$where .= " and oi.add_time <= '".strtotime($filter['end_add_time'])."'";
			}
			
			$sql = "select count(*) from order_info oi where oi.status = 2 $where";
		
			$num = $this->model->getOne($sql);
			
			$start = $filter['page_size'] * ($filter['page']-1);
			if($start > $num) {
				$start = 0;
			}

			$sql = "select oi.*,s.name as shop_name from order_info as oi,shops as s 
					where oi.shop_id = s.id and oi.status = 2 
					$where 
					limit $start,{$filter['page_size']}";
			$this->order_list = $this->model->getAll($sql);

			$this->pageinfo = $this->set_page($this->order_list,$num,$filter);

			if(isset($_REQUEST['query'])) {
				$this->make_json_exit('order/torange.php',$filter);
			}
	   }

	   /**
	    *发货列表
		*
		*/

		public function send_list() {
			$filter = array('page_size'=>20,'page'=>1);
			if(!isset($_REQUEST['query'])) {
				$this->ships = $this->model->table('ships')->fetchAll();
				$this->shops = $this->model->table('shops')->fetchAll();
				$this->payments = $this->model->table('payments')->fetchAll();
			}
			$filter['status'] = 4;
			if(isset($_REQUEST['query'])) {
				$this->layout = null;
				$filter['page_size'] = empty($_REQUEST['page_size'])?20:intval($_REQUEST['page_size']);
				$filter['page'] = empty($_REQUEST['page'])?1:intval($_REQUEST['page']);
				$this->query = 1;
				$filter['query'] = 1;
				$filter['order_sn'] = !empty($_REQUEST['order_sn'])?$_REQUEST['order_sn']:null;
				$filter['deal_code'] = !empty($_REQUEST['deal_code'])?$_REQUEST['deal_code']:null;
				$filter['shop_id'] = !empty($_REQUEST['shop_id'])?$_REQUEST['shop_id']:null;
				$filter['consignee'] = !empty($_REQUEST['consignee'])?$_REQUEST['consignee']:null;
			
				$filter['status'] = isset($_REQUEST['status'])?$_REQUEST['status']:null;
				$filter['pay_status'] = isset($_REQUEST['pay_status'])?$_REQUEST['pay_status']:null;
				$filter['ship_id'] = isset($_REQUEST['ship_id'])?$_REQUEST['ship_id']:null;
				$filter['pay_id'] = isset($_REQUEST['pay_id'])?$_REQUEST['pay_id']:null;
				$filter['start_add_time'] = isset($_REQUEST['start_add_time'])?$_REQUEST['start_add_time']:null;
				$filter['end_add_time'] = isset($_REQUEST['end_add_time'])?$_REQUEST['end_add_time']:null;
			}
			$filter = $this->add_magic($filter);

			$where = '';
			if(!empty($filter['order_sn'])) {
				$where .= " and oi.order_sn = '{$filter['order_sn']}'";
			}
			if(!empty($filter['deal_code'])) {
				$where .= " and oi.deal_code = '{$filter['deal_code']}'";
			}
			if(!empty($filter['shop_id'])) {
				$where .= " and oi.shop_id = '{$filter['shop_id']}'";
			}
			if(!empty($filter['consignee'])) {
				$where .= " and oi.consignee = '{$filter['consignee']}'";
			}
			if(!empty($filter['ship_id'])) {
				$where .= " and oi.shipping_id = '{$filter['ship_id']}'";
			}
			if(!empty($filter['pay_id'])) {
				$where .= " and oi.pay_id = '{$filter['pay_id']}'";
			}
			if(isset($filter['status']) && $filter['status'] != '') {
				$where .= " and oi.status = '{$filter['status']}'";
			}
			if(isset($filter['pay_status']) && $filter['pay_status'] != '') {
				$where .= " and oi.pay_status = '{$filter['pay_status']}'";
			}
			if(!empty($filter['start_add_time'])) {
				$where .= " and oi.add_time >= '".strtotime($filter['start_add_time'])."'";
			}
			if(!empty($filter['end_add_time'])) {
				$where .= " and oi.add_time <= '".strtotime($filter['end_add_time'])."'";
			}
			
			$sql = "select count(*) from order_info oi where 1=1 $where";
		
			$num = $this->model->getOne($sql);

			$sql = "select oi.*,s.name as shop_name ,r1.name as province_name,r2.name as city_name,r3.name as district_name
					from order_info as oi
					inner join shops as s on oi.shop_id = s.id  
					inner join region r1 on oi.province_id = r1.id
					inner join region r2 on oi.city_id = r2.id
					inner join region r3 on oi.district_id = r3.id
					where oi.status > 3
					$where";
			
			$this->order_list = $this->model->getAll($sql);
			
			$this->pageinfo = $this->set_page($this->order_list,$num,$filter);

			if(isset($_REQUEST['query'])) {
				
				$this->make_json_exit('order/send_list.php',$filter);
			}

		}

		/**
		 *发货
		 *
		 */
		 public function send($id = null) {
			if(empty($id)) {
				exit('此单不存在');
			}
			$time = time();
			$sql = "update order_info set status =5,shipping_time='{$time}' where id ='{$id}'";
			$this->model->query($sql);

			//更改库存
			//1.减少锁定库存 2.减少实际库存
			$sql = "update goods_stock gs,order_goods og
					set gs.actual_quantity = gs.actual_quantity-og.goods_number,gs.lock_quantity=gs.lock_quantity-og.goods_number
					where og.goods_id=gs.goods_id and og.size_id=gs.size_id and og.color_id=gs.color_id
					and og.warehouse_id = gs.warehouse_id
					and og.order_id = '{$id}'";
			$this->model->query($sql);

			$url = lib_functions::url('order/send_list');
			header('location:'.$url);

		 }

		 /**
		  *批量发货
		  *
		  */
		 public function pl_send() {
			$ids = $_REQUEST['ids'];
			
			if(empty($ids)) {
				exit(json_encode(array('status'=>-1,'msg'=>'操作失败')));
			}

			$time = time();
			$sql = "update order_info set status =5,shipping_time='{$time}' where id in ($ids)";
			$this->model->query($sql);

			//更改库存
			//1.减少锁定库存 2.减少实际库存
			$sql = "update goods_stock gs,order_goods og
					set gs.actual_quantity = gs.actual_quantity-og.goods_number,gs.lock_quantity=gs.lock_quantity-og.goods_number
					where og.goods_id=gs.goods_id and og.size_id=gs.size_id and og.color_id=gs.color_id
					and og.warehouse_id = gs.warehouse_id
					and og.order_id in ($ids)";
			$this->model->query($sql);
			exit(json_encode(array('status'=>0,'msg'=>'操作成功')));

		 }

	  
}