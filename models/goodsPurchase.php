<?php
/**
 *商品进货单
 *
 */
class models_goodsPurchase extends models_base{

	/**
	 *产生单据编号
	 *
	 */
	public function create_djbh() {
		$djbh_mdl = new models_djbh();
		$djbh = $djbh_mdl->create();
		$djbh = 'JH'.$djbh;
	
		$sql = "select count(*) from goods_purchase where djbh='{$djbh}'";
		if($this->getOne($sql)) {
			return $this->create_djbh();
		}else {
			return $djbh;
		}
	}

	/**
	 *增加商品
	 *
	 */
	 public function add_goods($params){
		 
			if(empty($params['p_id'])|| empty($params['goods_id']) || empty($params['color_id']) ||empty($params['size_id'])||empty($params['goods_number'])) {
				throw new Exception ("参数不正确");
			}
			if(empty($params['djbh'])) {
				$sql = "select djbh from goods_purchase where id='{$params['p_id']}'";
				$params['djbh'] = $this->getOne($sql);
			}
			$sql = "select goods_number,id from goods_purchase_mx 
					where p_id='{$params['p_id']}' and goods_id='{$params['goods_id']}'
					and color_id='{$params['color_id']}' and size_id='{$params['size_id']}'";
			$row = $this->getRow($sql);
			$num = $row['goods_number'];

			if(!empty($num)) {
				$num += $params['goods_number'];

				$sql = "update goods_purchase_mx set goods_number='{$num}'
						where id='{$row['id']}'";
				$this->query($sql);
			}else {
				return $this->table('goods_purchase_mx')->insert($params);
			}

	 }

}