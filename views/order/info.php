<h1>订单详情</h1>
<div class="header_div">
	<span><input type="button" onclick="window.history.back()" value="返回" class="header_button"></span>
</div>
<div style="margin-bottom: 5px;" class="list-div">
<table cellspacing="1" cellpadding="3" width="100%">
  <tr>
    <th colspan="4">基本信息
	<a id='edit_base_info' class="special" href="javascript:edit_base_info();">编辑</a>
	<a id="cancel_base_info" class="special" href="javascript:cancel_base_info();" style="display:none">取消</a>
	<a id="save_base_info"class="special" href="javascript:save_base_info();" style="display:none">保存</a>
	
	</th>
  </tr>
  <tr>
    <td width="18%"><div align="right"><strong>订单号：</strong></div></td>
    <td width="34%"><?php echo $response->order_info['order_sn'];?></td>
    <td width="15%"><div align="right"><strong>订单状态：</strong></div></td>
    <td><?php echo $response->order_info['status']==0?'未确定':'确定';?></td>
  </tr>
  <tr>
    <td><div align="right"><strong>交易号：</strong></div></td>
    <td><?php echo $response->order_info['deal_code'];?></td>
    <td><div align="right"><strong>店铺：</strong></div></td>
    <td><?php echo $response->order_info['shop_name'];?></td>
  </tr>
  <tr>
    <td><div align="right"><strong>购货人：</strong></div></td>
    <td><?php echo $response->order_info['user_name'];?></td>
    <td><div align="right"><strong>下单时间：</strong></div></td>
    <td><?php echo date('Y-m-d H:i',$response->order_info['add_time']);?></td>
  </tr>
  <tr>
    <td><div align="right"><strong>支付方式：</strong></div></td>
    <td>
		<select id="pay_id" name="pay_id" disabled='true' title="<?php echo $response->order_info['pay_id'];?>">
		<?php foreach($response->payments as $payment):?>
			<option value="<?php echo $payment['id'];?>" 
				<?php if($response->order_info['pay_id'] ==$payment['id']) echo 'selected';?>>
				<?php echo $payment['name'];?>
			</option>
		<?php endforeach;?>
		</select>
	</td>
    <td><div align="right"><strong>付款时间：</strong></div></td>
    <td><?php if($response->order_info['pay_time']) { echo date('Y-d-m H:i',$response->order_info['pay_time']);}?></td>
  </tr>
  <tr>
    <td><div align="right"><strong>配送方式：</strong></div></td>
    <td>
		<select id="ship_id" name="ship_id" disabled='true'  title="<?php echo $response->order_info['shipping_id'];?>">
		<?php foreach($response->ships as $ship):?>
			<option value="<?php echo $ship['id'];?>" 
				<?php if($response->order_info['shipping_id'] ==$ship['id']) echo 'selected';?>>
				<?php echo $ship['name'];?>
			</option>
		<?php endforeach;?>
		</select>
	</td>
    <td><div align="right"><strong>发货时间：</strong></div></td>
    <td>
	<?php if(!empty( $response->order_info['shipping_time'])) { echo date('Y-d-m H:i',$response->order_info['shipping_time']);}  ?>
	</td>
  </tr>
  <tr>
    <td><div align="right"><strong>运单号：</strong></div></td>
    <td><?php echo $response->order_info['invoice'];?></td>
    <td></td>
    <td></td>
  </tr>
</table>
<table cellspacing="1" cellpadding="3" width="100%">
  <tr>
    <th colspan="10">商品信息</th>
  </tr>
  <tr>
	<th>商品</th>
	<th>颜色</th>
	<th>尺码</th>
	<th>市场价</th>
	<th>销售价</th>
	<th>数量</th>
	<th>商品总额</th>
	<th>折扣</th>
	<th>应付金额</th>
	<?php if($response->order_info['pay_status'] ==0 ):?>
	<th>操作</th>
	<?php endif;?>
  </tr>
  <?php foreach($response->order_goods as $goods):?>
  <tr>
	<td align="center"  <?php if($goods['is_separate']):?> style="background-color:red" <?php endif;?>  ><?php echo $goods['goods_name']?>(<?php echo $goods['goods_sn']?>)</td>
	<td align="center"><?php echo $goods['color_name']?>(<?php echo $goods['color_code']?>)</td>
	<td align="center"><?php echo $goods['size_name']?>(<?php echo $goods['size_code']?>)</td>
	<td align="center"><?php echo $goods['market_price'];?></td>
	<td align="center"><?php echo $goods['shop_price'];?></td>
	<td align="center"><?php echo $goods['goods_number'];?></td>
	<td align="center"><?php echo $goods['goods_amount'];?></td>
	<td align="center"><?php echo $goods['card_fee'];?></td>
	<td align="center"><?php echo $goods['pay_fee'];?></td>
	<?php if($response->order_info['pay_status'] ==0 ):?>
	<td align="center">
		<a href="javascript:edit_goods('<?php echo $goods['id'];?>');">编辑</a>
		<a href="javascript:remove_goods('<?php echo $goods['id'];?>');">删除</a>
	</td>
	<?php endif;?>
	</tr>
  <?php endforeach;?>
</table>
<?php if($response->order_info['pay_status'] ==0 ):?>
<div id="edit_goods">
	商品货号<input type="text" name="edit_goods_sn" id="edit_goods_sn" />
	<input type="button" value="搜索" onclick="show_select_goods()"/>
</div>
<?php endif;?>
<table cellspacing="1" cellpadding="3" width="100%">
  <tr>
    <th colspan="4">收货人信息
	<a id='edit_consignee_info' class="special" href="javascript:edit_consignee_info();">编辑</a>
	<a id="cancel_consignee_info" class="special" href="javascript:cancel_consignee_info();" style="display:none">取消</a>
	<a id="save_consignee_info"class="special" href="javascript:save_consignee_info();" style="display:none">保存</a>

	</th>
  </tr>
  <tr>
    <td><div align="right"><strong>收货人：</strong></div></td>
    <td><input type='text' value='<?php echo $response->order_info['consignee'];?>' readOnly='readOnly' name='consignee' id='consignee' title="<?php echo $response->order_info['consignee'];?>"/></td>
    <td><div align="right"><strong>电子邮件：</strong></div></td>
    <td><input type='text' value='<?php echo $response->order_info['email'];?>' readOnly='readOnly' name='email' id='email' title='<?php echo $response->order_info['email'];?>' /></td>
  </tr>
  <tr>
    <td><div align="right"><strong>地址：</strong></div></td>
    <td>
		<select id="province" name="province" disabled="true" title="<?php echo $response->order_info['province_id'];?>">
				<?php foreach($response->provinces as $province): ?>
				<option value="<?php echo $province['id'];?>" <?php if($province['id'] == $response->order_info['province_id']) { echo "selected";}?>><?php echo $province['name'];?></option>
				<?php endforeach;?>
			</select>省
		<select id="city" name="city" disabled="true" title="<?php echo $response->order_info['city_id'];?>">
				<?php foreach($response->cities as $city):?>
			    <option  value="<?php echo $city['id'];?>" <?php if($city['id'] == $response->order_info['city_id']) { echo "selected";}?>  ><?php echo $city['name'];?></option>
				<?php endforeach;?>
		  </select>市
		<select  id="district" name="district" disabled="true"  title="<?php echo $response->order_info['district_id'];?>">
			<?php foreach($response->districtes as $district):?>
			<option  <?php if($district['id'] == $response->order_info['district_id']) { echo "selected";}?>  value="<?php echo $district['id'];?>"><?php echo $district['name'];?></option>
			<?php endforeach;?>
			</select>
	</td>
    <td><div align="right"><strong>详细地址：</strong></div></td>
    <td><input type='text' name='address' id='address' value='<?php echo $response->order_info['address'];?>' title="<?php echo $response->order_info['address'];?>" readOnly /> </td>
  </tr>
  <tr>
    <td><div align="right"><strong>电话：</strong></div></td>
    <td><input type='text' name='tel' id='tel' value='<?php echo $response->order_info['tel'];?>' title='<?php echo $response->order_info['tel'];?>' readOnly /></td>
    <td><div align="right"><strong>手机：</strong></div></td>
    <td><input type='text' name='mobile' id='mobile' value='<?php echo $response->order_info['mobile'];?>' title='<?php echo $response->order_info['mobile'];?>' readOnly /></td>
  </tr>
  <tr>
    <td><div align="right"><strong>邮编：</strong></div></td>
    <td><input type='text' name='zipcode' id='zipcode' value='<?php echo $response->order_info['zipcode'];?>' title='<?php echo $response->order_info['zipcode'];?>' readOnly /></td>
    <td><div align="right"><strong>备注--买家留言：</strong></div></td>
    <td><input type='text' name='bz' id='bz' value='<?php echo $response->order_info['bz'];?>' title='<?php echo $response->order_info['bz'];?>' readOnly /></td>
  </tr>
</tbody></table>
	<table cellspacing="1" cellpadding="3" width="100%">
		<tr>
			<td width="25%"><div align="right"><strong>商品总金额：</strong></div></td>
			<td width="30%"><?php echo $response->order_info['goods_amount'];?></td>
			<td><div align="right"><strong>其他折扣：</strong></div></td>
			<td><input type='text' value='<?php echo $response->order_info['discount'];?>' name='discount' readOnly='readOnly' id='discount' title="<?php echo $response->order_info['discount'];?>"/></td>
		</tr>
		<tr>
			<td><div align="right"><strong>订单总金额：</strong></div></td>
			<td><?php echo $response->order_info['total_amount'];?></td>
			<td><div align="right"><strong>运费：</strong></div></td>
			<td>
				<input type='text' value='<?php echo $response->order_info['shipping_fee'];?>' name='shipping_fee' id='shipping_fee' readOnly='readOnly' title='<?php echo $response->order_info['shipping_fee'];?>'/>
			</td>
		</tr>
		<tr>
			<td><div align="right"><strong>商品总折扣：</strong></div></td>
			<td><?php echo $response->order_info['goods_discount'];?></td>
			<td><div align="right"><strong>已付款：</strong></div></td>
			<td><?php echo $response->order_info['pay_money'];?></td>
		</tr>
		<tr>
			<td><div align="right"><strong>应付款：</strong></div></td>
			<td>
			<?php echo $response->order_info['total_amount'];?>
			<font color='red'>商品总金额-商品折扣-其他折扣+运费</font>
			</td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<th colspan="4">
			<?php if($response->order_info['is_guaqi'] == 0):?>
				<?php if($response->order_info['pay_id'] != 6 && $response->order_info['status'] !=2 ):?>
					<?php if($response->order_info['pay_status'] == 0):?>
						<a class="special" href="javascript:pay_function(1,'<?php echo $response->order_info['id'];?>');">付款</a>
					<?php else:?>
						<a class="special" href="javascript:pay_function(0,'<?php echo $response->order_info['id'];?>');">取消付款</a>
					<?php endif;?>
				<?php endif;?>
				<?php if($response->order_info['status'] == 0):?>
					<a class="special" href="javascript:confirm_fun(1,'<?php echo $response->order_info['id'];?>');">确认</a>
				<?php elseif($response->order_info['status'] == 1):?>
					<a class="special" href="javascript:confirm_fun(0,'<?php echo $response->order_info['id'];?>');">取消确认</a>
					<?php if($response->order_info['pay_status'] == 1 || $response->order_info['pay_id'] == 6):?>
					<a class="special" href="javascript:notice_peihuo(1,'<?php echo $response->order_info['id'];?>');">通知配货</a>
					<?php endif;?>
				<?php elseif($response->order_info['status'] == 2 && ($response->order_info['pay_status'] == 1 || $response->order_info['pay_id'] == 6)):?>
					<a class="special" href="javascript:notice_peihuo(0,'<?php echo $response->order_info['id'];?>');">取消通知配货</a>
				<?php endif;?>
			<?php endif;?>
			<?php if($response->order_info['is_guaqi'] == 0):?>
				<a class="special" href="javascript:guaqi(1,'<?php echo $response->order_info['id'];?>');">挂起</a>
			<?php else:?>
				<a class="special" href="javascript:guaqi(0,'<?php echo $response->order_info['id'];?>');">取消挂起</a>
			<?php endif;?>
			
			
			</th>
		</tr>
	</table>

</div>

<script type="text/javascript">
var Utils = new Utils();

function show_select_goods() {
	var obj = new Object();
	obj.order_id = '<?php echo $response->order_info['id'];?>';
	var order_sn = document.getElementById('edit_goods_sn').value;
	var returnValue = window.showModalDialog('<?php echo lib_functions::url('goods/select_goods/');?>'+obj.order_id+'?order_sn='+encodeURI(order_sn),obj,'resizable=yes;dialogWidth=700px');

	if(returnValue){
		var goods_id = returnValue;
		var url = '<?php echo lib_functions::url('goods/select_goods_one/');?>'+goods_id+'?order_id='+obj.order_id;

		var rV = window.showModalDialog(url,obj,'resizable=yes;dialogWidth=700px');
		
		if(rV) {
			window.location.reload();
		}

	}else {
		window.location.reload();
	}

	
}

function edit_goods(id) {
	var obj = new Object();
	var url= '<?php echo lib_functions::url('order/edit_goods/');?>';
	var returnValue =  window.showModalDialog(url+'?id='+id,obj,'resizable=yes;dialogWidth=700px');
	
	if(returnValue) {
		window.location.reload();
	}
}

function remove_goods(id) {
	
	if(!confirm('确认删除？')) {
		return;
	}
	
	var url= '<?php echo lib_functions::url('order/remove_goods/');?>';
	$.post(url,{'id':id},function(){
		alert('删除成功');
		window.location.reload();
	});
	
}

function edit_base_info() {
	document.getElementById('save_base_info').style.display = '';
	document.getElementById('cancel_base_info').style.display = '';
	document.getElementById('ship_id').disabled = false;
	document.getElementById('pay_id').disabled = false;
}
function cancel_base_info() {
	document.getElementById('save_base_info').style.display = 'none';
	document.getElementById('cancel_base_info').style.display = 'none';
	document.getElementById('ship_id').value = document.getElementById('ship_id').title;
	document.getElementById('ship_id').disabled = true;
	document.getElementById('pay_id').value = document.getElementById('pay_id').title;
	document.getElementById('pay_id').disabled = true;
}

function save_base_info() {
	var ship_id = document.getElementById('ship_id').value;
	document.getElementById('ship_id').title = document.getElementById('ship_id').value;
	var pay_id = document.getElementById('pay_id').value;
	document.getElementById('pay_id').title = document.getElementById('pay_id').value;
	var url= '<?php echo lib_functions::url('order/edit_base_info');?>';
	var id = '<?php echo $response->order_info['id'];?>';
	$.post(url,{'ship_id':ship_id,'pay_id':pay_id,'id':id},function() {
		document.getElementById('save_base_info').style.display = 'none';
		document.getElementById('cancel_base_info').style.display = 'none';
		document.getElementById('ship_id').disabled = true;
		document.getElementById('pay_id').disabled = true;
	});
}
function edit_consignee_info() {
	document.getElementById('save_consignee_info').style.display = '';
	document.getElementById('cancel_consignee_info').style.display = '';
	document.getElementById('consignee').readOnly = false;
	document.getElementById('email').readOnly = false;
	document.getElementById('address').readOnly = false;
	document.getElementById('tel').readOnly = false;
	document.getElementById('mobile').readOnly = false;
	document.getElementById('zipcode').readOnly = false;
	document.getElementById('bz').readOnly = false;
	document.getElementById('province').disabled = false;
	document.getElementById('city').disabled = false;
	document.getElementById('district').disabled = false;
	document.getElementById('shipping_fee').readOnly = false;
	document.getElementById('discount').readOnly = false;
}

function cancel_consignee_info() {
	document.getElementById('save_consignee_info').style.display = 'none';
	document.getElementById('cancel_consignee_info').style.display = 'none';
	document.getElementById('consignee').value = document.getElementById('consignee').title;
	document.getElementById('email').value = document.getElementById('email').title;
	document.getElementById('address').value = document.getElementById('address').title;
	document.getElementById('tel').value = document.getElementById('tel').title;
	document.getElementById('mobile').value = document.getElementById('mobile').title;
	document.getElementById('zipcode').value = document.getElementById('zipcode').title;
	document.getElementById('bz').value = document.getElementById('bz').title;
	document.getElementById('province').value = document.getElementById('province').title;
	document.getElementById('city').value = document.getElementById('city').title;
	document.getElementById('district').value = document.getElementById('district').title;
	document.getElementById('shipping_fee').value = document.getElementById('shipping_fee').title;
	document.getElementById('discount').value = document.getElementById('discount').title;

	document.getElementById('consignee').readOnly = true;
	document.getElementById('email').readOnly = true;
	document.getElementById('address').readOnly = true;
	document.getElementById('tel').readOnly = true;
	document.getElementById('mobile').readOnly = true;
	document.getElementById('zipcode').readOnly = true;
	document.getElementById('bz').readOnly = true;
	document.getElementById('province').disabled = true;
	document.getElementById('city').disabled = true;
	document.getElementById('district').disabled = true;
	document.getElementById('shipping_fee').readOnly = true;
	document.getElementById('discount').readOnly = true;
}

function save_consignee_info() {
	
	var params = {'consignee':Utils.trim(document.getElementById('consignee').value),
		'email': Utils.trim(document.getElementById('email').value),
		'address':Utils.trim(document.getElementById('address').value),
		'tel':Utils.trim(document.getElementById('tel').value),
		'mobile':Utils.trim(document.getElementById('mobile').value),
		'zipcode':Utils.trim(document.getElementById('zipcode').value),
		'bz':Utils.trim(document.getElementById('bz').value),
		'province':document.getElementById('province').value,
		'city':document.getElementById('city').value,
		'district':document.getElementById('district').value,
		'discount':document.getElementById('discount').value,
		'shipping_fee':document.getElementById('shipping_fee').value
	};

	var url= '<?php echo lib_functions::url('order/edit_consignee_info');?>';
	var id = '<?php echo $response->order_info['id'];?>';
	params.id = id;

	$.post(url,params,function() {
		alert('保存成功');
		window.location.reload();
		/*
		document.getElementById('save_consignee_info').style.display = 'none';
		document.getElementById('cancel_consignee_info').style.display = 'none';
		document.getElementById('consignee').title = document.getElementById('consignee').value;
		document.getElementById('email').title = document.getElementById('email').value;
		document.getElementById('address').title = document.getElementById('address').value;
		document.getElementById('tel').title = document.getElementById('tel').value;
		document.getElementById('mobile').title = document.getElementById('mobile').value;
		document.getElementById('zipcode').title = document.getElementById('zipcode').value;
		document.getElementById('bz').title = document.getElementById('bz').value;
		document.getElementById('province').title = document.getElementById('province').value;
		document.getElementById('city').title = document.getElementById('city').value;
		document.getElementById('district').title = document.getElementById('district').value;
		document.getElementById('discount').title = document.getElementById('discount').value;
		document.getElementById('shipping_fee').title = document.getElementById('shipping_fee').value;

		document.getElementById('consignee').readOnly = true;
		document.getElementById('email').readOnly = true;
		document.getElementById('address').readOnly = true;
		document.getElementById('tel').readOnly = true;
		document.getElementById('mobile').readOnly = true;
		document.getElementById('zipcode').readOnly = true;
		document.getElementById('bz').readOnly = true;
		document.getElementById('province').disabled = true;
		document.getElementById('city').disabled = true;
		document.getElementById('district').disabled = true;
		document.getElementById('discount').readOnly = true;
		document.getElementById('shipping_fee').readOnly = true;
		*/
	});
}


var province = document.getElementById('province');
Utils.bindFunction(province,'change',function() {
	var url = "<?php echo lib_functions::url('order/add');?>";
	var params = {'province':province.value};
	$.post(url,params,function(data){
		var city  = document.getElementById('city');
		var option =null;
		var cont = null;
		city.innerHTML = '';
		option = document.createElement('option');
		cont  = document.createTextNode('请选择');
		option.appendChild(cont);
		option.value = '';
		city.appendChild(option);

		var district  = document.getElementById('district');
		district.innerHTML = '';
		option = document.createElement('option');
		cont  = document.createTextNode('请选择');
		option.appendChild(cont);
		option.value = '';
		district.appendChild(option);

		for(var i=0;i<data.length;i++) {
			option = document.createElement('option');
			cont  = document.createTextNode(data[i]['name']);
			option.appendChild(cont);
			option.value=data[i].id;
			city.appendChild(option);
		}
	},'json');
});

var city = document.getElementById('city');
Utils.bindFunction(city,'change',function() {
	var url = "<?php echo lib_functions::url('order/add');?>";
	var params = {'city':city.value};
	$.post(url,params,function(data){
		var district  = document.getElementById('district');
		var option =null;
		var cont = null;

		district.innerHTML = '';
		option = document.createElement('option');
		cont  = document.createTextNode('请选择');
		option.appendChild(cont);
		option.value = '';
		district.appendChild(option);

		for(var i=0;i<data.length;i++) {
			option = document.createElement('option');
			cont  = document.createTextNode(data[i]['name']);
			option.appendChild(cont);
			option.value=data[i].id;
			district.appendChild(option);
		}
	},'json');
});

function pay_function(status,order_id) {
	var url = "<?php echo lib_functions::url('order/pay_money');?>";
	$.post(url,{'order_id':order_id,'status':status},function(data){
		if(data.status == 0) {
			alert(data.msg);
			window.location.reload();
		}else {
			alert(data.msg);
		}
	},'json');
}

function confirm_fun(status,order_id) {
	var url = "<?php echo lib_functions::url('order/confirm');?>";
	$.post(url,{'order_id':order_id,'status':status},function(data){
		if(data.status == 0) {
			alert(data.msg);
			window.location.reload();
		}else {
			alert(data.msg);
		}
	},'json');
}

function notice_peihuo(status,order_id) {
	var url = "<?php echo lib_functions::url('order/notice_peihuo');?>";
	$.post(url,{'order_id':order_id,'status':status},function(data){
		if(data.status == 0) {
			alert(data.msg);
			window.location.reload();
		}else {
			alert(data.msg);
		}
	},'json');

}

function guaqi(status,order_id) {
	var url = "<?php echo lib_functions::url('order/guaqi');?>";
	$.post(url,{'order_id':order_id,'status':status},function(data){
		if(data.status == 0) {
			alert(data.msg);
			window.location.reload();
		}else {
			alert(data.msg);
		}
	},'json');

}
</script>