<?php
/**
 *商品拣货单
 *
 */
class models_psend extends models_base{
	/**
	 *产生单据编号
	 *
	 */
	public function create_djbh() {
		$djbh_mdl = new models_djbh();
		$djbh = $djbh_mdl->create();
		$djbh = 'PS'.$djbh;
	
		$sql = "select count(*) from psend where djbh='{$djbh}'";
		if($this->getOne($sql)) {
			return $this->create_djbh();
		}else {
			return $djbh;
		}
	}

}