�̳̣�
	1. ����"·����_�ļ���"����    ���������test ����Ϊ��controllers_test
	2. $response��view��ʹ�ã���ʾview�����õ������ɵĶ���$response->var ��ʱ��var��view��Ӧ��controller���һ������
		��$response�Ϳ���ֱ�ӷ��ʿ������е�����һ������ֱ�ӷ��ʵ����ԣ�����Ϊpublic��protected
	3. �ڿ��������и�view���ԣ���ʾaction��Ӧ��view���������ã����Ϊ�գ���Ĭ��Ϊviews/��������/action��.php ��$this->view="test/bbs";
	4. bootĿ¼�£�
			1. config.php�����ļ�����ʱ����Ĭ�ϵĿ�������action��
				$array = array('default_controller'=>'','default_action'=>'');return $array;
			2. init.php�ļ�Ϊ�����ļ�������url�����Ҳ������ļ�
			3. command.php�ļ�����ϵͳ������Ĵ��
	5. libĿ¼Ϊ�����ļ�����,������Ķ��嶼Ӧ���ڴ�Ŀ¼��
	6. controllersĿ¼���������ֿ�����
	7. viewsĿ¼����������views�ļ���
	8. index.phpΪ��������ļ������ļ�
	9. models�㣬models_base�������ݿ����
	10. ȫ�ֱ��� DEBUG �������õ�

8��9����������
	һ. ��models_base�� $this->model->table($tablename)->fetchAll();
		1. fetchAll()
		2. fetch()
		3. insert()
		4. delete()
		5. update()
		6. table() ѡ����һ�ű�������
		��models_base��$this->model->getAll($sql),$this->model->getRow($sql)
		1. getAll($sql,$mode)��ȡ��������
		2. getRow($sql,$mode)��ȡһ������
		3. ����������������ʱ������ date_default_timezone_set('Asia/Shanghai');
	��. controllers/base.php ����Ϊ���п�������ĸ��࣬�ص�"���̳�";�Ա�֤һЩ���Կ��Ա��̳�
		1.���ԣ� 
			1.view 
			2.model
			3.layout ���֣���������Ϊ�̳�base���Կ����ڿ���������layoutֵ �磺$this->layout = 'index';
				��index��layout�ļ��е�index.php�ļ���Ӧ
				�������Ҫlayout��������Ϊnull
				
	��.	layout�ļ��� ������ ��� controllers_base���е�layout����
		1.index.php Ĭ�ϵĲ����ļ�
		2.css�ļ�����css�ļ�
		2.js�ļ�����js�ļ�
	
ÿ��һ��㣬��������¹��ܣ������ƣ��Լ��Ŀ�ܾ�����ǰ��

ע�����ͳͳ���þ���·��