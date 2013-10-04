<?php
class controllers_goods extends controllers_base {
	public $goods = array();
	public $brands;
	public $categories;
	public $shops;
	public $colors;
	public $sizes;

	public function __construct() {
		parent::__construct();

		$eAdmin = lib_functions::getSession('eAdmin');
		if(empty($eAdmin)) {
			lib_functions::redirect('privilege/index');
		}
	}

	public function getList() {
		$filter = array('page_size'=>20,'page'=>1);
		if(!isset($_REQUEST['query'])) {
			$this->brands = $this->model->table('brand')->fetchAll();
			$this->cats = $this->model->table('category')->fetchAll();
		}

		if(isset($_REQUEST['query'])) {
			$this->layout = null;
			$filter['page_size'] = empty($_REQUEST['page_size'])?20:intval($_REQUEST['page_size']);
			$filter['page'] = empty($_REQUEST['page'])?1:intval($_REQUEST['page']);
			$this->query = 1;
			$filter['query'] = 1;
			$filter['goods_key'] = !empty($_REQUEST['goods_key'])?$_REQUEST['goods_key']:null;
			$filter['brand_id'] = !empty($_REQUEST['brand_id'])?$_REQUEST['brand_id']:null;
			$filter['cat_id'] = !empty($_REQUEST['cat_id'])?$_REQUEST['cat_id']:null;
			$filter['is_sale'] = isset($_REQUEST['is_sale'])?$_REQUEST['is_sale']:null;
		}
		$filter = $this->add_magic($filter);
		
		$where = " ";
		if(!empty($filter['goods_key'])) {
			$where .= " and (g.goods_sn = '{$filter['goods_key']}' or g.goods_name like '%{$filter['goods_key']}%' )";
		}
		if(!empty($filter['brand_id'])) {
			$where .= " and g.brand_id = '{$filter['brand_id']}'";
		}
		if(!empty($filter['cat_id'])) {
			$where .= " and g.category_id = '{$filter['cat_id']}'";
		}
		if(isset($filter['is_sale']) && $filter['is_sale'] != '') {
			$where .= " and g.is_sale = '{$filter['is_sale']}'";
		}
		
		$sql = "select count(*) from goods g where 1=1 $where";
		
		$num = $this->model->getOne($sql);

		$start = $filter['page_size'] * ($filter['page']-1);
		if($start > $num) {
			$start = 0;
		}
		

		$sql = "select g.* ,c.name as cat_name,b.name as brand_name
				from goods g,category c,brand b
				where g.category_id=c.id and g.brand_id = b.id
				$where 
				limit $start ,{$filter['page_size']}";
		
		$this->goods = $this->model->getAll($sql);

		$this->pageinfo = $this->set_page($this->goods,$num,$filter);
	//	print_r($this->goods);exit;
		$this->view = 'goods/list';

		if(isset($_REQUEST['query'])) {
			$this->make_json_exit('goods/list.php',$filter);
		}
	}

	public function add() {
		$this->brands  = $this->model->table('brand')->fetchAll();
		$this->categories = $this->model->table('category')->fetchAll();
		$this->shops = $this->model->table('shops')->fetchAll();
	}

	public function edit($id = null) {
		if(empty($id)) {
			exit('该商品不存在');
		}
		$this->brands  = $this->model->table('brand')->fetchAll();
		$this->categories = $this->model->table('category')->fetchAll();
		$this->shops = $this->model->table('shops')->fetchAll();
		$this->goods = $this->model->table('goods')->fetch(array('where'=>array('id'=>$id)));
		$sql = "select gc.*,c.name as color_name from goods_color gc ,color c where gc.color_id=c.id and gc.goods_id='{$id}'";
		$this->colors = $this->model->getAll($sql);

		$sql = "select gs.*,s.name as size_name from goods_size gs ,size s where gs.size_id=s.id and gs.goods_id='{$id}'";
		$this->sizes = $this->model->getAll($sql);
	//	print_r($this->sizes);
	}

	public function save() {
	
		$goods = array(
			'goods_sn' => $this->_request('goods_sn'),
			'goods_name' => $this->_request('goods_name'),
			'shop_id' => $this->_request('shop_id'),
			'category_id' => $this->_request('cat_id'),
			'brand_id' => $this->_request('brand_id'),
			'is_sale' => $this->_request('is_sale'),
			'market_price' => $this->_request('market_price'),
			'cost_price' => $this->_request('cost_price'),
			'shop_price' => $this->_request('shop_price'),
			'describle' => $this->_request('desc'),
			'weight' => $this->_request('goods_weight')
			);
		$goods['market_price'] = empty($goods['market_price'])?'0.00':$goods['market_price'];
		$goods['cost_price'] = empty($goods['cost_price'])?'0.00':$goods['cost_price'];
		$goods['shop_price'] = empty($goods['shop_price'])?'0.00':$goods['shop_price'];
	//	print_r($_REQUEST);exit;
		try{
			$this->model->startTransaction();
			$this->model->table('goods')->insert($goods);
			$goods_id = $this->model->getOne('select last_insert_id()');

			//插入goods_color表
			$colors_arr = $this->_request('colors');
			if(!empty($colors_arr)) {
				foreach($colors_arr as $color) {
					$sql="select id from color where code ='{$color}'";
					$color_id = $this->model->getOne($sql);
					
					$goods_color = array(
						'goods_id'=>$goods_id,
						'goods_sn'=>$goods['goods_sn'],
						'color_code'=>$color,
						'color_id'=>$color_id
					);
					$this->model->table('goods_color')->insert($goods_color);
				}
			}
			
			//插入goods_size表
			$sizes_arr = $this->_request('sizes');
			if(!empty($sizes_arr)) {
				foreach($sizes_arr as $size) {
					$sql="select id from size where code ='{$size}'";
					$size_id = $this->model->getOne($sql);
					
					$goods_size = array(
						'goods_id'=>$goods_id,
						'goods_sn'=>$goods['goods_sn'],
						'size_code'=>$size,
						'size_id'=>$size_id
					);
					$this->model->table('goods_size')->insert($goods_size);
				}
			}
			
		}catch(Exception $e) {
			$this->model->rollback();
			exit('保存失败'.$e->getMessage());
		}
		$this->model->commit();
		exit('保存成功');
	}

	public function edit_save() {
		$goods_sn = $this->_request('goods_sn');
		$goods_id = $this->_request('goods_id');
		$goods = array(
			'goods_name' => $this->_request('goods_name'),
			'shop_id' => $this->_request('shop_id'),
			'category_id' => $this->_request('cat_id'),
			'brand_id' => $this->_request('brand_id'),
			'is_sale' => $this->_request('is_sale'),
			'market_price' => $this->_request('market_price'),
			'cost_price' => $this->_request('cost_price'),
			'shop_price' => $this->_request('shop_price'),
			'weight' => $this->_request('goods_weight'),
			'describle' => $this->_request('desc')
			);
		$goods['market_price'] = empty($goods['market_price'])?'0.00':$goods['market_price'];
		$goods['cost_price'] = empty($goods['cost_price'])?'0.00':$goods['cost_price'];
		$goods['shop_price'] = empty($goods['shop_price'])?'0.00':$goods['shop_price'];
	//	print_r($_REQUEST);exit;
		try{
			$this->model->startTransaction();
			$this->model->table('goods')->update(array('goods_sn'=>$goods_sn),$goods);

			//插入goods_color表

			$colors_arr = $this->_request('colors');
			$this->model->table('goods_color')->delete(array('goods_id'=>$goods_id));
			if(!empty($colors_arr)) {
				foreach($colors_arr as $color) {
					$sql="select id from color where code ='{$color}'";
					$color_id = $this->model->getOne($sql);
					
					$goods_color = array(
						'goods_id'=>$goods_id,
						'goods_sn'=>$goods_sn,
						'color_code'=>$color,
						'color_id'=>$color_id
					);
					$this->model->table('goods_color')->insert($goods_color);
				}
			}
			
			//插入goods_size表
			$sizes_arr = $this->_request('sizes');
			$this->model->table('goods_size')->delete(array('goods_id'=>$goods_id));
			if(!empty($sizes_arr)) {
				foreach($sizes_arr as $size) {
					$sql="select id from size where code ='{$size}'";
					$size_id = $this->model->getOne($sql);
					
					$goods_size = array(
						'goods_id'=>$goods_id,
						'goods_sn'=>$goods_sn,
						'size_code'=>$size,
						'size_id'=>$size_id
					);
					$this->model->table('goods_size')->insert($goods_size);
				}
			}
			
		}catch(Exception $e) {
			$this->model->rollback();
			exit('保存失败'.$e->getMessage());
		}
		$this->model->commit();
		exit('保存成功');
	}

	public function delete($id=null) {
		if(empty($id)) {
			exit('该商品不存在');
		}
		$sql = "select count(*) from order_goods where goods_id='{$id}'";
		$num = $this->model->getOne($sql);
		if($num>0) {
			exit(json_encode(array('status'=>-1,'msg'=>'此商品已经被用了，不能删除！')));
		}
		$this->model->table('goods')->delete(array('id'=>$id));
		$this->model->table('goods_color')->delete(array('goods_id'=>$id));
		$this->model->table('goods_size')->delete(array('goods_id'=>$id));
		exit(json_encode(array('status'=>0,'msg'=>'删除成功！')));
	}


	public function search_color() {
		$color_key  = $this->_request('color_keyword');
		$color_key = trim($color_key);
		$sql = "select * from color";
		if($color_key !== '') {
			$sql .= " where code ='{$color_key}' or name like '%{$color_key}%'";
		}
		
		$colors = $this->model->getAll($sql);
		exit(json_encode($colors));
	}

	public function search_size() {
		$size_key  = $this->_request('size_keyword');
		$size_key = trim($size_key);
		$sql = "select * from size";
		if($size_key !== '') {
			$sql .= " where code ='{$size_key}' or name like '%{$size_key}%'";
		}
		
		$sizes = $this->model->getAll($sql);
		exit(json_encode($sizes));
	}

	/**
	 *所有商品列表
	 *
	 */
	public function select_goods($order_id = null) {

		if($this->_request('act') == 'query') {
			$this->layout = null;

			$goods_sn = $this->_request('goods_sn');
			$goods_name = $this->_request('goods_name');
			$cat_id = $this->_request('cat_id');
			$brand_id = $this->_request('brand_id');
			
			$sql = "select g.*,s.name as shop_name ,c.name as cat_name,b.name as brand_name
					from goods g,shops s,category c,brand b
					where g.shop_id = s.id and g.category_id=c.id and b.id=g.brand_id";

			if(!empty($goods_sn)) {
				$sql .= " and g.goods_sn = '{$goods_sn}'";
			}
			if(!empty($goods_name)) {
				$sql .= " and g.goods_name like '%{$goods_name}%'";
			}
			if(!empty($cat_id)) {
				$sql .= " and g.category_id = '{$cat_id}'";
			}
			if(!empty($brand_id)) {
				$sql .= " and g.brand_id = '{$brand_id}'";
			}
			
			$this->goods = $this->model->getAll($sql);

			$this->fullpage = 0;
			
		}else {
			
			$sql = "select g.*,s.name as shop_name ,c.name as cat_name,b.name as brand_name
					from goods g,shops s,category c,brand b
					where g.shop_id = s.id and g.category_id=c.id and b.id=g.brand_id";
			if(!empty($_REQUEST['order_sn'])) {
				$_REQUEST['order_sn'] = urldecode($_REQUEST['order_sn']);

				$sql .= " and (g.goods_sn like '%{$_REQUEST['order_sn']}%' or g.goods_name like '%{$_REQUEST['order_sn']}%') ";
			}
		//	error_log($sql,3,'d:/1.sql');
			$this->goods = $this->model->getAll($sql);
		//	print_r($this->goods);exit;
			$sql = "select * from category";
			$this->categories = $this->model->getAll($sql);

			$sql = "select * from brand";
			$this->brands = $this->model->getAll($sql);

			$this->fullpage = 1;
		}

		$this->order_id = $order_id;
	}

	/*
	 *某个商品
	 *
	 *
	 */
	public function select_goods_one($id) {
		
		$sql = "select c.name as color_name,c.id from goods_color gc,color c
				where gc.color_id = c.id and gc.goods_id='{$id}'";
		$this->colors = $this->model->getAll($sql);
		//print_r($this->colors);exit;
		$sql = "select s.id,s.name as size_name from goods_size gs,size s 
				where gs.size_id=s.id and gs.goods_id='{$id}'";
		$this->sizes = $this->model->getAll($sql);
	//	print_r($this->sizes);exit;
		//商品
		$sql = "select * from goods where id='{$id}'";
		$this->goods = $this->model->getRow($sql);

	}


	/**
	 *上传图片
	 *
	 */
	 public function upload_img() {
		
		if (isset($_POST["PHPSESSID"])) {
			session_id($_POST["PHPSESSID"]);
		}
	//	print_r($_FILES);exit;
		if(isset($_FILES['Filedata'])) {
			$file_name = $_FILES['Filedata']['name'];
			$tmp_name = $_FILES['Filedata']['tmp_name'];
			
			$save_path = defined('DS')?DS.'/layout/images/goods/':null;

			if(empty($save_path) || !is_dir($save_path)) {
				$save_path = 'tmp/';
			}

			$file_name_new = $save_path.$file_name;
			$file_name_new = $this->get_file($file_name_new);
		//	exit($tmp_name);
			if (!@move_uploaded_file($tmp_name, $file_name_new)) {
				exit('图片保存失败');
			}

			$img_url = empty($save_path)?'tmp/':'goods/';
			$img_url .= basename($file_name_new);
			$img_url = addslashes($img_url);

			$id = $this->_request('id');
			$sql = "update goods set img_url = '{$img_url}' where id='{$id}'";
			$this->model->query($sql);

		}
		exit;
		
	 }
	
	/**
	 *获取文件路径
	 *如果有相同的文件名，则在已文件名加1
	 *例如：image.jpg,image2.jpg
	 *
	 */
	 private function get_file($file_name) {
		if(!file_exists ($file_name)) {
				return $file_name;
		}else {
			
			$path_parts = pathinfo($file_name);
			$new_file = trim($file_name,'.'.$path_parts['extension']);
			$new_file .= '1.'.$path_parts['extension'];

			return $this->get_file($new_file);
		}
	 }
	

}