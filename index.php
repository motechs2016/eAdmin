<?php
//�Զ�ע�ᣬ���������ڵ��ļ�
require 'lib/myautoload.php';
spl_autoload_register(array('Myautoload','autoLoad'));
define('DS', dirname(__FILE__)); //��·��

$start = boot_command::factory('boot_init');
