<?php
class boot_command{
	/*
	 * ����ģʽʵ������
	 */
	public static function factory($classname){
		return new $classname();
	}
}