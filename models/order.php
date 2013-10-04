<?php
/**
 *订单基类
 *
 */

class models_order extends models_base {
	
	/**
	 *订单添加商品
	 *
	 */
	public function add_goods($params) {
		$this->table = 'order_goods';

		if(empty($params['order_id']) && empty($params['order_sn'])) {
			throw new Exception("传入的参数不正确");
		}
		if(empty($params['order_sn'])) {
			$order_params = array(
					'cols'=>array('order_sn'),
					'where'=>array('id'=>$params['order_id'])
				);
			$row = $this->table('order_info')->fetch($order_params);
			$params['order_sn'] = $row['order_sn'];
		}
		if(!isset($params['goods_sn'])) {
			$goods_params = array(
					'cols'=>array('goods_sn'),
					'where'=>array('id'=>$params['goods_id'])
				);
			$row = $this->table('goods')->fetch($order_params);
			$params['goods_sn'] = $row['goods_sn'];
		}
		if(!isset($params['color_code'])) {
			$color_params = array(
					'cols'=>array('code'),
					'where'=>array('id'=>$params['color_id'])
				);
			$row = $this->table('color')->fetch($color_params);
			$params['color_code'] = $row['code'];
		}
		if(!isset($params['size_code'])) {
			$color_params = array(
					'cols'=>array('code'),
					'where'=>array('id'=>$params['color_id'])
				);
			$row = $this->table('size')->fetch($color_params);
			$params['color_code'] = $row['code'];
		}

		$sql = "select id,goods_number,goods_amount,card_fee,pay_fee from order_goods where order_id='{$params['order_id']}' 
				and goods_id= '{$params['goods_id']}' and color_id='{$params['color_id']}'
				and size_id='{$params['size_id']}'";
				
		$row = $this->getRow($sql);

		//不存在此商品
		if(empty($row['goods_number'])) {
			$order_goods_id = $this->insert($params);
			return $order_goods_id;
		//存在此商品
		}else {
			$sql = "update order_goods set goods_number=goods_number+{$params['goods_number']},
					goods_amount=goods_amount+{$params['goods_amount']},card_fee=card_fee+{$params['card_fee']},pay_fee=goods_amount-card_fee 
					where order_id='{$params['order_id']}' and goods_id='{$params['goods_id']}'
					and color_id='{$params['color_id']}' and size_id='{$params['size_id']}' ";
					
			$this->query($sql);
			return $row['id'];
		}

	}

	//更新主单金额数量
	public function update_je_sl($order_id) {
		$sql = "update order_info oi,(select SUM(goods_number) as total_num,SUM(goods_amount) as total_amount,SUM(pay_fee) as pay_money,SUM(card_fee) as discount from order_goods where order_id='{$order_id}') as tmp
		set oi.total_amount=oi.shipping_fee+tmp.pay_money-oi.discount,
			oi.goods_amount=tmp.total_amount,oi.goods_discount=tmp.discount
		where id='{$order_id}'";
		
		$this->query($sql);
	}

}