<?php
class controllers_users extends controllers_base{
	public $action_list;
	public $base_info;


	public function __construct(){
		parent::__construct();
		
		$eAdmin = lib_functions::getSession('eAdmin');
		if(empty($eAdmin)) {
			lib_functions::redirect('privilege/index');
		}
    
	}
	
	/**
	 *会员列表
	 */
	public function getList() {
		$sql = "select u.*,ua.mobile,concat(r1.name,r2.name,r3.name,ua.address) as address,ua.consignee
				from users u
				inner join  user_address ua on u.id = ua.user_id
				inner join region r1 on r1.id = ua.province
				inner join region r2 on r2.id = ua.city
				inner join region r3 on r3.id = ua.district
				where ua.is_default = 1";
		$this->list = $this->model->getAll($sql);
		
	}

	public function edit($id = null) {
		$this->user_name = $this->model->getOne("select user_name from users where id='{$id}'");

		$sql = "select ua.mobile,r1.name as province_name,r2.name as city_name,r3.name as district_name,ua.address,ua.consignee,ua.tel,ua.mobile,ua.is_default,ua.id
				from   user_address ua 
				inner join region r1 on r1.id = ua.province
				inner join region r2 on r2.id = ua.city
				inner join region r3 on r3.id = ua.district
				where ua.user_id='{$id}'";
		$this->list = $this->model->getAll($sql);

	}

	public function delete() {
		$id = $this->_request('id');
		if(empty($id)) {
			exit(json_encode(array('status'=>-1,'msg'=>'操作失败')));
		}
		
		$ret = $this->model->table('user_address')->delete(array('id'=>$id));
		if($ret) {
			exit(json_encode(array('status'=>0,'msg'=>'操作成功')));
		}
		exit(json_encode(array('status'=>-1,'msg'=>'操作失败')));
	}
	
}