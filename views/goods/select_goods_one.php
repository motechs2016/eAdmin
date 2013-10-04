<div class="list-div">
<base target="_self">
<form  action="<?php echo lib_functions::url('order/add_goods');?>" method="post" name="add_goods_form" >
	<input type="hidden" name="goods_id" value="<?php echo $response->goods['id'];?>">
	<input type="hidden" name="goods_sn" value="<?php echo $response->goods['goods_sn'];?>">
	<input type="hidden" name="order_id" value="<?php echo @$_REQUEST['order_id'];?>">
	<input type="hidden" name="market_price" id = "market_price" value="<?php echo $response->goods['market_price'];?>">

	<table cellspacing="1" cellpadding="3" >
		<tr>
			<th>商品名称：</th><td><?php echo $response->goods['goods_name'];?> </td>
			<th>商品编码：</th><td><?php echo $response->goods['goods_sn'];?></td>
		</tr>
		<tr id="goods_price_edit">
			<th>市场价</th><td><?php echo $response->goods['market_price'];?></td>
			<th>销售价</th>
			<td>
				<input type="type" size="10" value="<?php echo $response->goods['shop_price'];?>" name="shop_price" id="shop_price" onkeyup='change_shop_price(this)'>
				折扣：<input type="text" id="zhekou" size="5" value="<?php echo number_format($response->goods['shop_price']/$response->goods['market_price'],2);?>" readOnly />
				
			</td>
		</tr>
	</table>
	<hr/>
	<table  cellspacing="1" cellpadding="3" >
		<tr>
			<th>尺码/颜色</th>
			<?php foreach($response->colors as $color):?>
			<th style="color:red"><?php echo $color['color_name'];?></th>
			<?php endforeach;?>
		</tr>
		<?php foreach($response->sizes as $size):?>
		<tr>
			<th style="color:red"><?php echo $size['size_name'];?></th>
			<?php foreach($response->colors as $color):?>
			<th><input type="type" size="5" name="<?php echo 'row_'.$size['id'].'_'.$color['id'];?>" onkeyup="check_type(this)"/></th>
			<?php endforeach;?>
		</tr>	
		<?php endforeach;?>
		<tr>
			<td colspan="<?php echo count($response->sizes)+1;?>" align="center">
			<input type="submit" value="提交"/>
			<input type="button" value="关闭" onclick="window.close()">
			</td>
		</tr>
	</table>
</form>
</div>


<script type="text/javascript">

var Utils = new Utils();

if(!window.dialogArguments) {
	window.close();  
}else{
	var arg_obj = window.dialogArguments;
	if(arg_obj.action) {
		document.forms['add_goods_form'].action = arg_obj.action;
		document.getElementById('goods_price_edit').style.display = 'none';
	}
}

window.returnValue = true;

function check_type(obj) {
	var value = Utils.trim(obj.value);

	//判断是否是正整数
	var regex = /^\d+$/;
	if(regex.test(value)) {
		//符合条件
	}else {
		obj.value = '';
	}
}

function change_shop_price(obj) {
	var market_price = document.getElementById('market_price').value;
	
	if(!is_number(obj.value)) {
		obj.value = market_price;
	}

	var value = parseFloat(obj.value);
	
	value = value > market_price?market_price:value;
	obj.value = value;
	var zk = parseFloat(value/market_price);
	document.getElementById('zhekou').value = zk;
}

function is_number(value) {
	var regex = /^\d+(\.\d)*$/;
	return regex.test(value);
}


</script>