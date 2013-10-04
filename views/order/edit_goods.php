<div class="list-div">
<form action="<?php echo lib_functions::url('order/edit_goods');?>" method="post" name="add_goods_form" onsubmit='return submit_form();'>
	<input type='hidden' name='order_id' id='order_id' value="<?php echo $response->goods['order_id'];?>" />
	<input type='hidden' name='order_sn' id='order_sn'value="<?php echo $response->goods['order_sn'];?>" />
	<input type='hidden' name='goods_sn' id='goods_sn' value="<?php echo $response->goods['goods_sn'];?>" />
	<input type='hidden' name='goods_id' id='goods_id' value="<?php echo $response->goods['goods_id'];?>" />
	<input type='hidden' name='order_goods_id' id='order_goods_id' value="<?php echo $response->goods['id'];?>" />

	<table cellspacing="1" cellpadding="3" >
		<tr>
			<th>商品名称：</th><td><?php echo $response->goods['goods_name'];?> </td>
		</tr>
		<tr>
			<th>商品编码：</th><td><?php echo $response->goods['goods_sn'];?></td>
		</tr>
		<tr>
			<th>市场价</th>
			<td><?php echo $response->goods['market_price'];?>
				<input type='hidden' value='<?php echo $response->goods['market_price'];?>' name='market_price' id='market_price'>
			</td>
		</tr>
		<tr>
			<th>销售价</th>
			<td>
				<input type="type" size="10" value="<?php echo $response->goods['shop_price'];?>" name="shop_price" id="shop_price" onkeyup='change_shop_price(this)'>
			</td>
		</tr>
		<tr>
			<th>折扣</th>
			<td>
				<input type="text" id="zhekou" size="5" value="<?php echo number_format($response->goods['shop_price']/$response->goods['market_price'],2);?>" readOnly />
			</td>
		</tr>
		<tr>
			<th>颜色</th>
			<td>
				<select id="color_id" name="color_id" disabled="true">
				<?php foreach($response->colors as $color):?>
				<option value="<?php echo $color['id'];?>"<?php if($response->goods['color_id'] == $color['id']){ echo 'checked';}?>>
					<?php echo $color['name'];?>
				</option>
				<?php endforeach;?>
			</td>
		</tr>
		<tr>
			<th>尺码</th>
			<td>
				<select id="size_id" name="size_id" disabled="true">
				<?php foreach($response->sizes as $size):?>
				<option value="<?php echo $size['id'];?>"<?php if($response->goods['size_id'] == $size['id']){ echo 'checked';}?>>
					<?php echo $size['name'];?>
				</option>
				<?php endforeach;?>
			</td>
		</tr>
		<tr>
			<th>数量</th>
			<td><input type="text" value="<?php echo $response->goods['goods_number'];?>" name="goods_number" id="goods_number"/></td>
		</tr>
		<tr>
			<td colspan="2" align="center">
			<input type="button" value="提交" onclick="submit_form();"/>
			<input type="button" value="关闭" onclick="window.close()">
			</td>
		</tr>
	</table>
</form>
</div>

<script type='text/javascript'>
window.returnValue =  1;

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
	zk = zk.toString();
	var index = zk.indexOf('.');

	if(index>0) {
		zk = zk.substring(0,index+4);
	}
	document.getElementById('zhekou').value = zk;
}

function is_number(value) {
	var regex = /^\d+(\.\d)*$/;
	return regex.test(value);
}

function submit_form() {
	
	var params = {
		'submit':1,
		'order_goods_id':document.getElementById('order_goods_id').value,
		'shop_price':	document.getElementById('shop_price').value,
		'market_price':	document.getElementById('market_price').value,
		'size_id':	document.getElementById('size_id').value,
		'color_id':	document.getElementById('color_id').value,
		'goods_number':	document.getElementById('goods_number').value
	};
		
	var url = '<?php echo lib_functions::url('order/edit_goods');?>';

	$.post(url,params,function(data) {
			if(data.code==0) {
				alert('保存成功！');
			}else {
				alert('保存失败！');
			}
			window.close();
	},'json');
	
	return false;
}
</script>