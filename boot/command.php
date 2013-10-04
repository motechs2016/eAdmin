<?php
class boot_command{
	/*
	 * 工厂模式实例化类
	 */
	public static function factory($classname){
		return new $classname();
	}
}