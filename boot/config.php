<?php
/*
 * 配置文件
 * 配置默认的ctrl，action....
 */
$array = array(
	'default_controller' => 'shop',
	'default_action' => 'index',
	'debug' => false  //调试
);

/**
 *
 *数据库配置
 *
 */
$array['host'] = 'localhost';
$array['database'] = 'eadmin';
$array['user'] = 'root';
$array['password'] = '';

/**
 *编码类型
 */
$array['charset'] = 'utf8';

$array['timezone'] = 'Asia/Shanghai';

return $array;