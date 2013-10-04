<?php
class controllers_shop extends controllers_base{
	public $action_list;
	public $base_info;


	public function __construct(){
		parent::__construct();
		$this->layout = null;
		
		$eAdmin = lib_functions::getSession('eAdmin');
		if(empty($eAdmin)) {
			lib_functions::redirect('privilege/index');
		}
    
	
	}
	
	/**
	 *首页
	 */
	public function index() {
		
		//
	}

	/**
	 *头部
	 */
	public function top() {
		$this->layout = null;
		$this->model->query('set names utf8');
		$sql = "select * from acl_action where type='cote'";
		
		$this->action_list = $this->model->getAll($sql);
		//var_dump($this->model);
	//	print_r($$this->action_list);exit;
	}

	/**
	 *导航条
	 */
	 public function menu() {
		$main_menu = $this->model->table('acl_action')->fetchAll(array('where'=>array('type'=>'group')));
		foreach($main_menu as $n=>$value) {
			$sql = "select id,action_code,action_name from acl_action where parent_id='{$value['id']}'";
			$main_menu[$n]['child_menu'] = $this->model->getAll($sql);
		}
		$this->action_list = $main_menu;
	//	print_r($this->action_list);exit;

	 }

	 public function drag() {
		
		
	 }

	 /**
	  *内容
	  */
	  public function main() {
		  $mysql_version = $this->model->getRow("select version() as version");
		  $gd = gd_info();

		  $this->base_info = array(
			  'os' => PHP_OS,
			  'ip' => $_SERVER['SERVER_ADDR'],
			  'web_server' => $_SERVER['SERVER_SOFTWARE'],
			  'php_version' => PHP_VERSION,
			  'mysql_version' => $mysql_version['version'],
			  'safe_mode' => ini_get('safe_mode'),
			  'safe_mode_gid' => ini_get('safe_mode_gid'),
			  'socket' => function_exists('fsockopen')?'是':'否' ,
			  'timezone' => date_default_timezone_get(),
			  'GD_version' => $gd['GD Version'],
			  'zlib' => function_exists('gzclose')?'是':'否',
			  'upload_max_filesize' => ini_get('upload_max_filesize'),
			  'charset' => lib_functions::get_config('charset'),
			  'memory' => memory_get_usage()/(1024*1024)
		  );
		  
		
	  }

	 public function insert_acl_action() {
		if(isset($_REQUEST['submit'])) {
			$arr = array(
				'parent_id'=>$this->_request('parent_id'),
				'type'=>$this->_request('type'),
				'action_name'=>$this->_request('action_name'),
				'action_code'=>$this->_request('action_code'),
				'sort_by' =>$this->_request('sort_by')
			);

			$this->model->table('acl_action')->insert($arr);
		}

		$this->action_list = $this->model->table('acl_action')->fetchAll();

	 }
}