<?php
class controllers_test extends controllers_base{

	public $a='bb';
	public $users,$user;
	
	public function __construct(){
		parent::__construct();
	}
	
	public function index($id){
	//	$this->view = 'test/login';
		$this->layout = 'index';
		//拦截器
		$this->interceptor('user', 'test/login');
		
        echo lib_functions::getSession('user') .", 欢迎您    <a href='logout.php'>注销</a><br/>";
		if($_REQUEST['submit']){
			$user = $this->_request('user');
			$pass = $this->_request('pass');
			$count = $this->model->table('users')->insert(array('name'=>$user,'pass'=>md5($pass)));
			if($count>0)
				echo 'save successfully!';
		}
	//	$this->users = $this->model->table('users')->fetchAll(array('mode'=>2,'order'=>array('id asc')));
		$this->users = $this->model->getAll('select * from users');
	//	$this->users = $this->model->table('users')->fetch(array('mode'=>2,'where'=>array('id'=>1),'order'=>array('id asc')));
		
	//	lib_functions::format_pre($this->users);
		
	}
	public function delete($id){
		$id = isset($id)?$id:0;
		$b = $this->model->table('users')->delete(array('id'=>$id));
		if($b){
			echo 'delete successfully!';
			exit;
		}
		echo 'delete fail!';
		exit;
	}
	public function update($id){
		$id = isset($id)?$id:0;
		if($_REQUEST['submit']){
			$b = $this->model->table('users')->update(array('id'=>$id),array('name'=>$_REQUEST['name'],'pass'=>md5($_REQUEST['pass'])));
			if($b){
				echo 'update successfully!'; 
			}else{
				echo 'update fail!';
			}
		}
		
		$this->user = $this->model->table('users')->fetch(array('where'=>array('id'=>$id)));
	}
	
	public function login(){
		if(isset($_REQUEST['submit'])){
			$user = $this->model->table('users')->fetch(array('where'=>array('name'=>$_REQUEST['username'],'pass'=>md5($_REQUEST['password']))));
//			lib_functions::format_pre($user);
			if(!empty($user)){
				lib_functions::setSession('user', $user['name']);
			//	echo "{$_SESSION['user']} , 欢迎您    <a href='logout.php'>注销</a><br/>";
				lib_functions::redirect('test/index.php');
			}else{
				echo '用户名或密码不正确';
			}
		}
	}
	
	public function logout(){
		lib_functions::unsetSession('user');
		lib_functions::redirect('test/login.php');
		exit;
	}
	
	public function test() {
		print_r($_REQUEST);
		exit;
	}
}