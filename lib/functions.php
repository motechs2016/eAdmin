<?php
/*
 * 各种函数
 */
class lib_functions{

	/*
	 * 合并数组，比如array(1,2,3,4),移除前两项，而生成array(3,4)
	 * @params array $data 要操作的数组
	 * @params int $n 移除前n项
	 */
	public static function reduce_array(array $data, $n){
		
	}
	
	/*
	 * 试用framset src中用此函数，输入src路径
	 */
	public static function echo_url_frame($view_path){
		$url = $_SERVER['SCRIPT_NAME'].'/'.$view_path;
		echo $url;
	}
	
	/*
	 * 格式化输入数组
	 */
	public static function format_pre(array $params){
		echo '<pre>';
		print_r($params);
		echo '</pre>';
	}
	
	/*
	 * 设置session
	 */
	public static function setSession($name,$value){
	
		if(!isset($_SESSION)) {
			session_start();
		}
		$lifeTime = 24*3600;
		$_SESSION[$name] = $value;
		//setcookie(session_name(),session_id(),time()+$lifeTime);
	}
	
	/*
	 * 获取session
	 */
	public static function getSession($name){
		if(!isset($_SESSION)) {
			session_start();
		}
		return isset($_SESSION[$name])?$_SESSION[$name]:null;
	}
	/*
	 * 销毁session
	 */
	public static function unsetSession($name){
		if(!isset($_SESSION)) {
			session_start();
		}
		unset($_SESSION[$name]);
		session_destroy();
	}
	
	/*
	 * 重定向
	 * @param url 重定向to url  url为'controller/action'  test/index
	 * @param $time 过多久才重定向
	 * @return void
	 */
	public static function redirect($url,$time = NULL){
		$url = $_SERVER['SCRIPT_NAME'].'/'.$url;
		if(!$time){
			@header('Location:'.$url);
		}else{
			@header("Refresh:$time;url=$url");
		}
	}
	
	/**
	 * 输出一个<a></a>
	 * <?php lib_functions::action('test/add', 'ADD', array('id'=>'add_id','class'=>'add_class'));
	 * <=>  <a class="add_class" id="add_id" href="/front_controller/index.php/test/add">ADD</a>
	 * @param $url <a href=$url>
	 * @param $name <a>$name</a>
	 * @param attributes 各种属性
	 */
	public static function action($url,$name,array $attributes =NULL){
		$u = $_SERVER['SCRIPT_NAME'].'/'.$url;
		$attr = '';
		if(!is_null($attributes)){
			foreach($attributes as $key => $val){
				$attr .= " $key='{$val}'";
			}
		}
		return "<a href='{$u}' $attr>$name</a>";
	}

	public static function url($url) {
		return $_SERVER['SCRIPT_NAME'].'/'.$url;
	}
	
	/*
	 * 增加布局文件
	 */
	public static function require_layout($file){
		if(defined('DS')) {
			require (DS.DIRECTORY_SEPARATOR.'layout'.DIRECTORY_SEPARATOR.$file);
		}
	}
	
	/*
	 * 输出图片src
	 * @param  string $url 此框架的图片放在layout/images
	 */
	public static function image_src($url='') {
		if(defined('DS')) {
			return '/'.basename(DS).'/layout/images/'.$url;
		}
	}
	
	/*
	 * 输出image
	 * @return string 
	 */
	public static function image($url,array $attributes = NULL) {
		$url = self::image_src($url);
		$attr = '';
		if(!is_null($attributes)) {
			foreach($attributes as $key => $val){
				$attr .= "$key='{$val}'";
			}
		}
		return  "<img src = '{$url}' $attr />";
	} 
	
	/*
	 * @param $string $file 文件名
	 * $file 是根目录的文件路径，例如："layout/js/a.js"
	 * @return $string 文件的绝对的路径
	 */
	public static function file_path($file){
		if(defined('DS')) {
			return '/'.basename(DS).'/'.$file;
		}
	}

	/**
	 *获取layout路径
	 *
	 */
	 public static function get_layout($file=null) {
		 if(defined('DS')) {
			return '/'.basename(DS).'/layout/'.$file;
		 }
	 }
	/**
	 *获取配置文件的数据config.php
	 *@param $key
	 */
	 public static function get_config($key) {
		 if(defined('DS')) {
			$configPath = DS.DIRECTORY_SEPARATOR.'boot'.DIRECTORY_SEPARATOR.'config.php';
			$config = include $configPath; //加载配置文件
			return $config[$key];
		 }
	 }

	 /**
	  *加载view
	  *
	  *
	  */
	  public static function include_view($file) {
		  if(defined('DS')) { 
			   $file_path =  DS.'/views/'.$file;
			   include $file_path;
		  }
	  }

	 /**
	 *返回include文件内容成为一个变量
	 *
	 */
	public static function get_include_contents($filename) {
		if(!defined('DS')) {
			return;
		}
		$filename = DS.'/'.$filename;

		if (is_file($filename)) {
			ob_start();
			include $filename;
			$contents = ob_get_contents();
			ob_end_clean();
			return $contents;
		}
		return false;
	}
}