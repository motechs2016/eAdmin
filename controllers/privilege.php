<?php
class controllers_privilege extends controllers_base {
	public $user_name;
	public $admin_info;
	public $cote_action_list;
	public $group_action_list;

	public function __construct() {
		$this->layout = null;
		parent::__construct();
	
	}
	
	/**
	 *登录界面
	 */
	public function index() {
		//如果session存在，则转到shop主页
		$eAdmin = lib_functions::getSession('eAdmin');
		if(!empty($eAdmin)) {
			lib_functions::redirect('shop/index');
		}
	}

	/**
	 *登录
	 */
	 public function login() {
		
		$username = $this->_request('username');
		$pass = $this->_request('password');
		$params = array('cols'=>array('*'),'where'=>array('user_name' => $username ,'password' => md5($pass)));
		$result = $this->model->table('admin_user')->fetch($params);
		
		//用户名，密码不对
		if(!$result) {
			exit(json_encode(array('status'=>'-1','msg'=>'用户名或密码错误')));
		}else {  //登录正确

			//session
			lib_functions::setSession('eAdmin','1');
			lib_functions::setSession('eAdmin_admin_user',$username);
			lib_functions::setSession('eAdmin_admin_id',$result['id']);
			
			//更改状态
			$params = array('last_ip'=>$_SERVER['REMOTE_ADDR'],'last_login'=>time(),'active'=>1);
			$this->model->table('admin_user')->update(array('user_name'=>$username),
				$params);
		
			exit(json_encode(array('status'=>0,'msg'=>'登录成功')));
		}
	 }

	/**
	 *注销
	 */
	 public function logout() {
		lib_functions::unsetSession('eAdmin');
		lib_functions::redirect('privilege/index');
	 }

	 /**
	  *设置导航条
	  *
	  */
	  public function modif() {
			$this->user_name = lib_functions::getSession('eAdmin_admin_user');
			$this->admin_user = $this->model->table('admin_user')->fetch(
				array('cols'=>array('email'),
						'where'=>array('user_name'=>$this->user_name)
				)
				);

			$this->cote_action_list = $this->model->table('acl_action')->fetchAll(
				array('cols'=>array('action_name','action_code'),
					  'where'=>array('type'=>'cote'))
				);

			
			/**
			 * group acl_action 
			 */
			$sql = "select id,action_code,action_name from acl_action where type='group'";
			$groups = $this->model->getAll($sql);

			foreach($groups as $n=>$data) {
				$sql = "select id,action_code,action_name from acl_action where type='url' and parent_id = '{$data['id']}'"; 
			
				$groups[$n]['children'] = $this->model->getAll($sql);
			}

			$this->groups_action_list = $groups;
	  }

	  /**
	   * 保存
	   */
	   public function save() {
			$user_name = $this->_request('user_name');
			$pass = $this->_request('pass');
			$pass = md5($pass);
			$old_pass = $this->_request('old_pass');
			$old_pass = md5($old_pass);
			$email = $this->_request('email');
			$cote = $this->_request('cote');
			foreach($cote as $n=>$data) {
				$cote[$n]['type'] = 'cote';
			}
			
			$sql = "select count(*) as num from admin_user where user_name ='{$user_name}'
					and password = '{$old_pass}'";
			$ret = $this->model->getRow($sql);
			if(empty($ret['num'])) {
				$result = array('status'=>'-1','msg'=>'密码不正确');
			}else {
				$sql = "update admin_user set password = '{$pass}' where user_name ='{$user_name}'";
				$ret = $this->model->query($sql);
				if(!$ret) {
					$result = array('status'=>'-1','msg'=>'密码更新失败');
				}

				$this->model->startTransaction();
				
				$sql = "delete from acl_action where type='cote'";
				$ret = $this->model->query($sql);
				if($ret) {
					$insert_ret = $this->model->table('acl_action')->insertAll($cote);
					if(!$insert_ret) {
						$this->model->rollback();
						throw new Exception('插入值出错','-1');
					}else {
						$result = array('status'=>0,'msg'=>'操作成功！');
					}
				}

				$this->model->commit();
			}

			exit(json_encode($result));
			
	   }
}