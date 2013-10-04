<?php
/**
 *店铺
 *
 */
class controllers_shopMarket extends controllers_base{
	
	public function __construct(){
		parent::__construct();
		
		$eAdmin = lib_functions::getSession('eAdmin');
		if(empty($eAdmin)) {
			lib_functions::redirect('privilege/index');
		}
    
	}

	public function add() {
		if(isset($_REQUEST['submit'])) {
			$class_id = $this->_request('class_id');
			$class_code = $this->model->getOne("select code from shop_class where id='{$class_id}'");
			
			$data = array(
				'code'=>$this->_request('code'),
				'name'=>$this->_request('name'),
				'class_id'=>$class_id,
				'class_code'=>$class_code
			);
		
			$id = $this->model->table('shops')->insert($data);
			$url = lib_functions::url('shopMarket/edit/'.$id);
			header("location:$url");
		}

		$this->shop_class = $this->model->table('shop_class')->fetchAll();
	}
	
	public function getList() {
		$sql = "select s.* ,sc.name as class_name from shops s ,shop_class sc where s.class_id = sc.id";
		$this->list = $this->model->getAll($sql);
	}

	
	public function edit($id = null) {

		if(isset($_REQUEST['submit'])) {
			$class_id = $this->_request('class_id');
			$class_code = $this->model->getOne("select code from shop_class where id='{$class_id}'");
			$keys = $this->_request('keys');
			$values = $this->_request('values');
			$params = array();
			foreach($keys as $n=>$v) {
				if(empty($v)){
					continue;
				}
				$params[] = array($v=>$values[$n]);
			}
			$api_params = json_encode($params);

			$data = array(
				'name'=>$this->_request('name'),
				'class_id'=>$class_id,
				'class_code'=>$class_code,
				'api_params'=>addslashes($api_params)
			);
			$where = array('code'=>$this->_request('code'));
			$ret = $this->model->table('shops')->update($where,$data);
			if($ret) {
				exit(json_encode(array('status'=>0,'msg'=>'操作成功')));
			}else {
				exit(json_encode(array('status'=>-1,'msg'=>'操作失败')));
			}
		}

		if(empty($id)) {
			exit('该店铺不存在');
		}
		$this->shop = $this->model->table('shops')->fetch(array(
			'where'=>array('id'=>$id)	
		));
		$this->shop_class = $this->model->table('shop_class')->fetchAll();
		$this->shop['api_params'] = json_decode($this->shop['api_params'],true);
		
		$this->api_params = array();
		if(!empty($this->shop['api_params'])) {
			foreach($this->shop['api_params'] as $value) {
				foreach($value as $k => $v) {
					$this->api_params[] = array($k,$v);
				}
			}
		}
	//	print_r($this->api_params);exit;
	}

	public function view($id = null){
		if(empty($id)) {
			exit('该店铺不存在');
		}
		
		$this->shop = $this->model->table('shops')->fetch(array(
			'where'=>array('id'=>$id)	
		));
		$this->shop_class = $this->model->table('shop_class')->fetchAll();
		$this->shop['api_params'] = json_decode($this->shop['api_params'],true);
		
		$this->api_params = array();
		if(!empty($this->shop['api_params'])) {
			foreach($this->shop['api_params'] as $value) {
				foreach($value as $k => $v) {
					$this->api_params[] = array($k,$v);
				}
			}
		}
	}

	public function delete($id=null) {
		if(empty($id)) {
			exit('该店铺不存在');
		}

		$this->model->table('shops')->delete(array('id'=>$id));
		$url = lib_functions::url('shopMarket/getList');
		header("location:$url");
	}
}