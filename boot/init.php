<?php

class boot_init {
	public $controller;
	public $action;
	public $params;
	private $default = array();
	
	public function __construct(){
		$configPath = DS.DIRECTORY_SEPARATOR.'boot'.DIRECTORY_SEPARATOR.'config.php';
		$this->default = include $configPath; //加载配置文件
		define('DEBUG', $this->default['debug']);
		if(DEBUG) {
			error_reporting(E_ALL);
		}else {
			error_reporting(E_ALL ^ E_NOTICE);
		}
		
		date_default_timezone_set($this->default['timezone']);
	
		if(empty($this->default['default_controller'])) {
			//系统默认的值
			$this->controller = 'test';
			$this->action = 'index';
		}else{
			//您配置的默认值
			$this->controller = $this->default['default_controller'];
			$this->action = $this->default['default_action'];
		}
		
		$this->get_path();
		$this->start();
		
	}
	
	/*
	 * 从url提取controller，action，params
	 */
	public function get_path(){
		
		$url = $_SERVER['PHP_SELF'];
		$start = strpos($url,'index.php');
		
		if($start){  // http://localhost/hr/index.php/....
			$url = trim($url,'/'); //去掉 index.php/后面的"/"
			$url = substr($url, $start+strlen('index.php'));
			
			if(!$url){
				return;
			}
			  
			$url = str_ireplace('.php','',$url); //   control/action/params
			$url_array = explode('/', $url);
			
			switch (sizeof($url_array)){
				case 0 : //默认值
					break;
				case 1 :
					$this->controller = $url_array[0];
					break;
				case 2 :
					$this->controller = $url_array[0];
					$this->action = $url_array[1];
					break;
				case 3 :
					$this->controller = $url_array[0];
					$this->action = $url_array[1];
					$this->params = $url_array[2];
					break;
				default:
					$this->controller = $url_array[0];
					$this->action = $url_array[1];
					array_splice($url_array, 0,2); //移除前两项
					$this->params = $url_array;
			}
		}
	}
	
	/*
	 * 启动程序，查找相应的文件controller，action
	 */
	public function start(){
		//$this->controller 为 test ，而对应的类名为controller_test
		$classname = "controllers_".$this->controller;
		$action = $this->action;

		$ctl_reflect = new ReflectionClass($classname);
		$ctl = $ctl_reflect->newInstance();
//		$ctl = new $classname();
		if(method_exists($ctl, $action)){
			$response = $ctl;  //定义在view中显示

			$ctl->$action($this->params); //调用ctrl->action方法
		    
			//layout
			if(!empty($ctl->layout)){
				include(DS.DIRECTORY_SEPARATOR.'layout'.DIRECTORY_SEPARATOR.$ctl->layout.'.php');
			}
			
			//view
			if(!empty($ctl->view)){ //调用view   控制器中view属性存在
				$filename = DS.DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR.$ctl->view.'.php';
				if(!file_exists($filename)){
					exit("<div class='error'>files: <b>$filename</b> not exists!</div>");
				}
				include_once ($filename);
			}else{  // 控制器中view属性不存在
				$filename = DS.DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR.$this->controller.DIRECTORY_SEPARATOR.$action.".php";
				if(!file_exists($filename)){
					exit("<div class='error'>files: <b>$filename</b> not exist!</div>");
				}
				include_once ($filename);
			}
		}else{
			exit("<div class='error'>action: <b>$action</b>  not exist in controller class: <b>$classname</b></div>");
		}
		
	}
}