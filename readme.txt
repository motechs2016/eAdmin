教程：
	1. 类以"路径名_文件名"命名    例如控制器test 类名为“controllers_test
	2. $response在view中使用，表示view被调用的类生成的对象，$response->var 此时的var是view对应的controller类的一个属性
		用$response就可以直接访问控制器中的任意一个可以直接访问的属性，可以为public，protected
	3. 在控制器中有个view属性，表示action对应的view，可以设置，如果为空，则默认为views/控制器名/action名.php ，$this->view="test/bbs";
	4. boot目录下：
			1. config.php配置文件，暂时配置默认的控制器，action；
				$array = array('default_controller'=>'','default_action'=>'');return $array;
			2. init.php文件为启动文件，解释url，查找并调用文件
			3. command.php文件各种系统命令函数的存放
	5. lib目录为”库文件集“,各种类的定义都应放在此目录下
	6. controllers目录：包含各种控制器
	7. views目录：包含各种views文件，
	8. index.php为各种入口文件，主文件
	9. models层，models_base基本数据库操作
	10. 全局变量 DEBUG 做调试用的

8月9号新增功能
	一. 在models_base类 $this->model->table($tablename)->fetchAll();
		1. fetchAll()
		2. fetch()
		3. insert()
		4. delete()
		5. update()
		6. table() 选择哪一张表来操作
		在models_base类$this->model->getAll($sql),$this->model->getRow($sql)
		1. getAll($sql,$mode)获取所有数据
		2. getRow($sql,$mode)获取一条数据
		3. 上述两个方法中有时区设置 date_default_timezone_set('Asia/Shanghai');
	二. controllers/base.php 类中为所有控制器类的父类，重点"被继承";以保证一些属性可以被继承
		1.属性： 
			1.view 
			2.model
			3.layout 布局，控制器因为继承base所以可以在控制器中设layout值 如：$this->layout = 'index';
				此index与layout文件中的index.php文件对应
				如果不需要layout，则设置为null
				
	三.	layout文件夹 布局用 结合 controllers_base类中的layout变量
		1.index.php 默认的布局文件
		2.css文件，放css文件
		2.js文件，放js文件
	
每天一点点，慢慢添加新功能，逐步完善，自己的框架就在眼前！

注意事项：统统采用绝对路径