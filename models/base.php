<?php
class models_base {
	
	private static $db ;
	private static $hostname ;
	private static $username  ;
	private static $passwd ;
	private static $dsn;
	private static $pdo;

	public $table;

	public function __construct(){
//		$this->dsn = " mysql:host=$this->hostname;dbname=$this->db";
//		$this->pdo = new PDO($this->dsn, $this->username, $this->passwd);
		
		$configPath = DS.DIRECTORY_SEPARATOR.'boot'.DIRECTORY_SEPARATOR.'config.php';
		$config = include $configPath; //加载配置文件
		
		self::$hostname = $config['host'];
		self::$db = $config['database'];
		self::$username = $config['user'];
		self::$passwd = $config['password'];
		
	}
	
	/**
	 * 获取pdo single模式
	 */
	public static function getPDO(){
		if(empty(self::$pdo)){
			self::$dsn = "mysql:host=".self::$hostname.";dbname=".self::$db;
			
			self::$pdo = new PDO(self::$dsn, self::$username, self::$passwd);
		}
		self::$pdo->query('set names utf8');
		self::$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		return self::$pdo;
	}
	
	/**
	 * 设定表名
	 * @param string $tablename
	 * @return class model_base
	 */
	public function table($tablename){
	//	$table = new models_base();
		$this->table = $tablename;
		return $this;
	}
	
	/**
	 *@param string $sql 
	 *@return unkown_type
	 */
	 public function query($sql) {
		return self::getPDO()->query($sql);
	 }
	/**
	 * 获取所有数据
	 * @params array $params , array('cols'=>array('id','name'),'mode'=>'','where'=>array('id'=>'','name'=>''),'order'=>array('id asc','pid desc'));
	 * cols为要查询的字段，mode为fetch方式
	 * mode = PDO::FETCH_ASSOC  PDO::FETCH_BOTH  ....具体参考pdo fetch_style属性 
	 * 其实PDO::FETCH_ASSOC是常量数字，具体参考pdo
	 * @return array
	 */
	public function fetchAll(array $params = NULL){
		$sql = "select * from $this->table";
		$where = " where 1 ";
		
		//对输入不正确进行处理
		if(!empty($params)){
			$keys_array = array_keys($params);
			$array_value = array('cols','mode','where','order');
		//	print_r($keys_array);
			foreach($keys_array as $key_y){
				if(!in_array($key_y, $array_value)){
					exit("fetchAll params are not right！");
				}
			}
		}
		
		//select id,name....
		if(!empty($params['cols'])){
			$cols = implode(',', $params['cols']);
			$sql = "select $cols from $this->table ";
		}
		//select   where id=1 and name='wq'
	//	print_r($params);
		if(!empty($params['where'])){
			foreach($params['where'] as $key => $value){
				$where .= " and $key = '{$value}' "; 
				
			}
		}
		
		$sql .= $where;
		
		if(!empty($params['order'])){
			$order = " order by ";
			foreach($params['order'] as $val){
				$order .= " $val ";
			}
			$sql .= $order;
		}
		if(!empty($params['mode'])){
			$mode = $params['mode'];
		}else{
			$mode = PDO::FETCH_BOTH;
		}
		
		$stm = self::getPDO()->prepare($sql);
		$stm->execute();
		return $stm->fetchAll($mode);
	}
	
	/**
	 * 获取一条数据
	 */
	public function fetch(array $params = NULL){
		$sql = "select * from $this->table";
		$where = " where 1 ";
		
	//对输入不正确进行处理
		if(!empty($params)){
			$keys_array = array_keys($params);
			$array_value = array('cols','mode','where','order');
			foreach($keys_array as $key_y){
				if(!in_array($key_y, $array_value)){
					exit("fetch params are not right！");
				}
			}
		}
		
		if(!empty($params['cols'])){
			$cols = implode(',', $params['cols']);
			$sql = "select $cols from $this->table ";
		}
		
		if(!empty($params['where'])){
			foreach($params['where'] as $key => $value){
				$where .= " and $key = '{$value}' "; 
			}
		}
		$sql .= $where;
		
		if(!empty($params['order'])){
			$order = " order by ";
			foreach($params['order'] as $val){
				$order .= " $val ";
			}
			$sql .= $order;
		}
		if(!empty($params['mode'])){
			$mode = $params['mode'];
		}else{
			$mode = PDO::FETCH_BOTH;
		}
		$stm = self::getPDO()->prepare($sql);
		$stm->execute();
		return $stm->fetch($mode);
	}
	
	/**
	 * 插入一条数据  insert into users(name,pass) values ('a','a')
	 * @params array $params数组形式 ,如array('id'=>1,'name'=>'wq');
	 * @return int 正常运行应该返回1
	 */
	public function insert(array $params){
		$keys = $vals = '';
		foreach($params as $key => $val){
			$keys .= $key.',';
			$vals .= self::getPDO()->quote($val).',';
		}
		$keys = trim($keys,',');
		$vals = trim($vals,',');
		
		$sql = "INSERT INTO {$this->table} ({$keys}) values ({$vals})";

	//	exit ($sql);
		$count = self::getPDO()->exec($sql); //返回影响的行数
		return $this->getOne("SELECT LAST_INSERT_ID()");
	}
	
	/**
	 * 批量插入数据 
	 * @params array $params 数组集set
	 * $params array(array('name'=>'wq'),array('name'=>'wq')
	 */
	public function insertAll(array $params){
		try{
			//self::getPDO()->beginTransaction();
			foreach($params as $param){
				$ret = $this->insert($param);
				if($ret == false) {
					throw Exception('插入运行失败');
				}
			}
			//self::getPDO()->commit();
		}catch(Exception $e){
		//	self::getPDO()->rollBack();
			throw $e;
		}
		return true;
	}
	
	/**
	 * 删除
	 * @params array $params删除条件where array('id'=>1,'name'=>'wq')
	 * @return boolean
	 */
	public function delete(array $params){
		$where = ' where 1 ';
		foreach($params as $key => $val){
			$where .= " and {$key}='{$val}' ";
		}
		$sql = "DELETE FROM {$this->table} {$where}";
		$stm = self::getPDO()->prepare($sql);
		$b = $stm->execute();
		return $b;
		
	}
	
	/**
	 * 更新数据
	 * @params array $where array('id'=>1,'name'=>'wq')
	 * @params array $params array('name'=>'wqq')
	 * @return boolean
	 */
	public function update(array $where ,array $params){
		$sql = "update {$this->table} ";
		$set = " set ";
		foreach($params as $key => $val){
			$set .= "  $key = '{$val}' ,";
		}
		$set = trim($set,',');
		$sql .= $set;
		
		$w = 'where 1';
		foreach($where as $key => $val){
			$w .= " and $key = '{$val}' ";
		}
		$sql .= $w;
//		echo $sql;exit;
		$stm = self::getPDO()->prepare($sql);
		$b = $stm->execute();
		return $stm->rowCount();
	}
	
	//多表联合查询
	
	/**
	 * 获取一条数据
	 * @param string $sql 标准的sql语句
	 * @param int $mode
	 * @return array result
	 */
	public function getRow($sql,$mode=PDO::FETCH_ASSOC){
		$result = array();
		try{
			self::getPDO()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stm = self::getPDO()->prepare($sql);
			$stm->execute();
			$result = $stm->fetch($mode);
			
		//	print_r($result);exit;
			return $result;
		}catch (PDOException $err){
			
			$message = date('Y-m-d H:i:s',time());
			$message .= "\tCode: ".strval($err->getCode());
			$message .= "\t Message:{$err->getMessage()} \n";
			$ds = DIRECTORY_SEPARATOR;
			$logpath = DS."{$ds}log{$ds}error_log.txt";
			if(file_exists($logpath)) {
				error_log($message,3,$logpath);
				if(DEBUG){
					throw new Exception($err->getMessage());
				}
			}
		}
	}

	public function getOne($sql,$colunm=0) {
		$stm = self::getPDO()->prepare($sql);
		$stm->execute();
		return $stm->fetchColumn($colunm);
	}
	
	/**
	 * 获取全部数据
	 * @param string $sql 标准的sql语句
	 * @param int $mode
	 * @return array resultset
	 */
	public function getAll($sql,$mode=PDO::FETCH_ASSOC){
		try{
			$stm = self::getPDO()->prepare($sql);
			$stm->execute();
			$result = $stm->fetchAll($mode);
			return $result;
		}catch (PDOException $err){
			
			$message = date('Y-m-d H:i:s',time());
			$message .= "\t Code: ".strval($err->getCode());
			$message .= "\t Message:{$err->getMessage()} \n";
			$ds = DIRECTORY_SEPARATOR;
			$logpath = DS."{$ds}log{$ds}error_log.txt";
			if(file_exists($logpath)) {
				error_log($message,3,$logpath);
				if(DEBUG){
					throw new Exception($err->getMessage());
				}
			}
		}
	}

	/**
	 *事物开始
	 */
	public function startTransaction() {
		return self::getPDO()->beginTransaction();
	}

	/**
	 *事物提交
	 */
	public function commit() {
		return self::getPDO()->commit();
	}

	/**
	 *事物回滚
	 */
	 public function rollback() {
		return self::getPDO()->rollBack();
	 }

	 /**
	  *返回最后插入ID
	  *
	  */
	  public function get_last_id() {
		 $sql = "select last_insert_id()";
		 return $this->getOne($sql);
	  }
}