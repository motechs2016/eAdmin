<?php
/*
 * ���ֺ���
 */
class lib_functions{

	/*
	 * �ϲ����飬����array(1,2,3,4),�Ƴ�ǰ���������array(3,4)
	 * @params array $data Ҫ����������
	 * @params int $n �Ƴ�ǰn��
	 */
	public static function reduce_array(array $data, $n){
		
	}
	
	/*
	 * ����framset src���ô˺���������src·��
	 */
	public static function echo_url_frame($view_path){
		$url = $_SERVER['SCRIPT_NAME'].'/'.$view_path;
		echo $url;
	}
	
	/*
	 * ��ʽ����������
	 */
	public static function format_pre(array $params){
		echo '<pre>';
		print_r($params);
		echo '</pre>';
	}
	
	/*
	 * ����session
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
	 * ��ȡsession
	 */
	public static function getSession($name){
		if(!isset($_SESSION)) {
			session_start();
		}
		return isset($_SESSION[$name])?$_SESSION[$name]:null;
	}
	/*
	 * ����session
	 */
	public static function unsetSession($name){
		if(!isset($_SESSION)) {
			session_start();
		}
		unset($_SESSION[$name]);
		session_destroy();
	}
	
	/*
	 * �ض���
	 * @param url �ض���to url  urlΪ'controller/action'  test/index
	 * @param $time ����ò��ض���
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
	 * ���һ��<a></a>
	 * <?php lib_functions::action('test/add', 'ADD', array('id'=>'add_id','class'=>'add_class'));
	 * <=>  <a class="add_class" id="add_id" href="/front_controller/index.php/test/add">ADD</a>
	 * @param $url <a href=$url>
	 * @param $name <a>$name</a>
	 * @param attributes ��������
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
	 * ���Ӳ����ļ�
	 */
	public static function require_layout($file){
		if(defined('DS')) {
			require (DS.DIRECTORY_SEPARATOR.'layout'.DIRECTORY_SEPARATOR.$file);
		}
	}
	
	/*
	 * ���ͼƬsrc
	 * @param  string $url �˿�ܵ�ͼƬ����layout/images
	 */
	public static function image_src($url='') {
		if(defined('DS')) {
			return '/'.basename(DS).'/layout/images/'.$url;
		}
	}
	
	/*
	 * ���image
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
	 * @param $string $file �ļ���
	 * $file �Ǹ�Ŀ¼���ļ�·�������磺"layout/js/a.js"
	 * @return $string �ļ��ľ��Ե�·��
	 */
	public static function file_path($file){
		if(defined('DS')) {
			return '/'.basename(DS).'/'.$file;
		}
	}

	/**
	 *��ȡlayout·��
	 *
	 */
	 public static function get_layout($file=null) {
		 if(defined('DS')) {
			return '/'.basename(DS).'/layout/'.$file;
		 }
	 }
	/**
	 *��ȡ�����ļ�������config.php
	 *@param $key
	 */
	 public static function get_config($key) {
		 if(defined('DS')) {
			$configPath = DS.DIRECTORY_SEPARATOR.'boot'.DIRECTORY_SEPARATOR.'config.php';
			$config = include $configPath; //���������ļ�
			return $config[$key];
		 }
	 }

	 /**
	  *����view
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
	 *����include�ļ����ݳ�Ϊһ������
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