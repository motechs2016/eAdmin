<?php
/*
 * classname for example "filename_filename.php"
 * 
 */
class Myautoload {
	public static function autoLoad($className){
		$fileName = str_replace('_', DIRECTORY_SEPARATOR, $className);
		$fileName = $fileName.'.php';
		if(file_exists($fileName)){
			require_once ($fileName);
		}else {
		//	exit("<div class='error'><b>$filename</b> not exist!</div>");
			throw new Exception("class $className not exists!");
		}
	}
}