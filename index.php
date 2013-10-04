<?php
//自动注册，包含类所在的文件
require 'lib/myautoload.php';
spl_autoload_register(array('Myautoload','autoLoad'));
define('DS', dirname(__FILE__)); //根路径

$start = boot_command::factory('boot_init');
