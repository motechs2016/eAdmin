<?php
/**
 *商品基类
 *
 */
class models_goods extends models_base{

	/**
	 *增加实际库存
	 *
	 */
	 public function add_actual_stock($params) {
		if(empty($params['warehouse_id']) || empty($params['goods_id']) || empty($params['color_id']) || empty($params['size_id'])) {
			throw new Exception('传入的参数不正确');
		}
		if(empty($params['goods_number'])) {
			throw new Exception('传入的参数不正确');
		}
		$sql = "select count(*) from goods_stock where warehouse_id='{$params['warehouse_id']}'
				and goods_id='{$params['goods_id']}' and color_id='{$params['color_id']}'
				and size_id='{$params['size_id']}'";
		if($this->getOne($sql)) {
			$sql = "update goods_stock set actual_quantity =actual_quantity+{$params['goods_number']} where 
					warehouse_id='{$params['warehouse_id']}' and goods_id='{$params['goods_id']}' and color_id='{$params['color_id']}'
					and size_id='{$params['size_id']}'";
			return $this->query($sql);
		}else {
			return $this->table('goods_stock')->insert(array(
				'goods_id'=>	$params['goods_id'],
				'size_id'=>   $params['size_id'],
				'color_id'=>   $params['color_id'],
				'warehouse_id'=> $params['warehouse_id'],
				'actual_quantity'=> $params['goods_number']
			));
		}
	 }

	 /**
	  *增加锁定库存
	  *
	  */
	  public function add_lock_stock($params) {
			if(empty($params['warehouse_id']) || empty($params['goods_id']) || empty($params['color_id']) || empty($params['size_id'])){
				throw new Exception('传入的参数不正确');
			}
			if(empty($params['goods_number'])) {
				throw new Exception('传入的参数不正确');
			}
			$sql = "select count(*) from goods_stock where warehouse_id='{$params['warehouse_id']}'
				and goods_id='{$params['goods_id']}' and color_id='{$params['color_id']}'
				and size_id='{$params['size_id']}'";
			if($this->getOne($sql)) {
				$sql = "update goods_stock set lock_quantity =lock_quantity+{$params['goods_number']} where 
						warehouse_id='{$params['warehouse_id']}' and goods_id='{$params['goods_id']}' and color_id='{$params['color_id']}'
						and size_id='{$params['size_id']}'";
				return $this->query($sql);
			}else {
				return $this->table('goods_stock')->insert(array(
					'goods_id'=>	$params['goods_id'],
					'size_id'=>   $params['size_id'],
					'color_id'=>   $params['color_id'],
					'warehouse_id'=> $params['warehouse_id'],
					'lock_quantity'=> $params['goods_number']
				));
			}
	  }

	  /**
	   *订单锁定库存
	   *
	   */
	   public function order_add_lock_stock($order_id) {
		
			$sql = "select oi.shop_id,og.*
					from order_info oi,order_goods og
					where oi.id=og.order_id and oi.id='{$order_id}'";
			$goods_arr = $this->getAll($sql);
			$ware_arr = $this->table('shop_ware')->fetchAll(array(
				'where'=>array('shop_id'=>$goods_arr[0]['shop_id'])	,
				'order'=>array('level asc')
			));

			try{
				$this->startTransaction();
				$order_goods_ids = array();

				foreach($goods_arr as $goods) {
					$kc = 0;
					
					foreach($ware_arr as $ware) {
						$stock_data = $this->table('goods_stock')->fetch(
							array(
								'where'=>array('goods_id'=>$goods['goods_id'],
												'size_id'=>$goods['size_id'],
												'color_id'=>$goods['color_id'],
												'warehouse_id'=>$ware['ware_id']
										)
							)	
						);
					//	print_r($stock_data);print_r($goods);exit;
						//库存表没有记录
						if(empty($stock_data)) {
							 $this->table('goods_stock')->insert(array(
								'goods_id'=>	$goods['goods_id'],
								'size_id'=>   $goods['size_id'],
								'color_id'=>   $goods['color_id'],
								'warehouse_id'=> $ware['ware_id']
							));
							 continue;
						 //有记录，但库存不足
						}elseif($stock_data['actual_quantity']-$stock_data['lock_quantity']<$goods['goods_number']) {  
							continue;	
						}else{
							//增加锁定库存
							$sql = "update goods_stock set lock_quantity =lock_quantity+{$goods['goods_number']}
									where warehouse_id='{$ware['ware_id']}' and goods_id='{$goods['goods_id']}' 
									and color_id='{$goods['color_id']}'
									and size_id='{$goods['size_id']}'";
						//	exit($sql);		
							$this->query($sql);
							
							//更改order_goods中warehouse_id字段
							$sql = "update order_goods set warehouse_id='{$ware['ware_id']}' where id='{$goods['id']}'";
							
							$this->query($sql);

							$kc = 1;
							break;
						}
						
					}
					
					if($kc == 0) {  //商品没有任何符合库存，都库存不足
						$order_goods_ids[] = $goods['id'];  //缺货order_goods id
					}
					
				}
				
				if(!empty($order_goods_ids)) {
					throw new Exception('商品库存不足','-1');
				}

				$this->commit();
			}catch(Exception $e) {
				$this->rollback();
				//缺货
				$this->table('order_info')->update(array('id'=>$order_id),array('is_separate'=>1));
				foreach($order_goods_ids as $id) {
					$this->table('order_goods')->update(array('id'=>$id),array('is_separate'=>1));
				}
			
			}

	   }

}