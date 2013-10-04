<?php
class controllers_base {
	protected $model;
	public  $view;
	public  $layout = 'index';
	public function __construct(){
		$this->model = boot_command::factory('models_base');
	}
	
	/**
	 * 定义请求函数 post ，get方法通用
	 * @param string $name  $_POST[$name]
	 * @return string 
	 */
	protected function _request($name){
		return isset($_REQUEST[$name])?$_REQUEST[$name]:null;
	}
	
	/**
	 * 拦截器
	 */
	protected function interceptor($session_name,$url){
		$session = lib_functions::getSession($session_name);
		if(!isset($session)) {
//			header('Refresh:5;url=login.php');
			lib_functions::redirect($url,5);
			print('对不起,您还没有登录,请先登录，5秒之后跳转....');
			exit;
		}
	}

	/**
	 *分页
	 *2012-5-4
	 */
	protected function set_page($data,$total_num,&$filter=array('page_size'=>20,'page'=>1)) {

		$data['total_num'] = $total_num;
		$data['page_num'] = ceil($data['total_num']/$filter['page_size']);
		$data['page'] = $filter['page'];
		$data['page_size'] = $filter['page_size'];
		if($data['page_num']==1) {
			$data['select_page']= array(1);
		}elseif($data['page_num'] < 7 && $data['page_num'] > 1) {
			for($i=1;$i<=$data['page_num'];$i++) {
				$data['select_page'][]= $i;
			}
		}elseif($data['page_num'] >= 7) {
			$data['select_page']= array(1,2,3,$data['page_num']-2,$data['page_num']-1,$data['page_num']);
		}
		$filter['page_num'] = $data['page_num'];
		$data['filter'] = $filter;

		return $data;
	}

	/**
	 *json格式输出 
	 *2012-5-5
	 */
	protected function make_json_exit($tpl,$filter) {
		$response = &$this;
		$tpl = 'views/'.$tpl;
		$filename = DS.'/'.$tpl;
		
		if (is_file($filename)) {
			
			ob_start();
			include $filename;
			$contents = ob_get_contents();
			ob_end_clean();

			$ret = array('content'=>$contents,'filter'=>$filter);
			exit(json_encode($ret));
		}
		
	}
	
	protected function add_magic($arr) {
		foreach($arr as &$value) {
			$value = trim($value);
			$value = addslashes($value);
		}
		return $arr;
	}

}