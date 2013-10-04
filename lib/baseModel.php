<?php
class baseModel{
	private $dsn = "mysql:host=hostname;dbname=";
	public function con($databasename){
		$dsn .= $databasename;
		
		
	}
}