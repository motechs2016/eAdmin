<div class="pageheader">
	<h1>
		<span class="action-span1">商品进货单--编辑 </span>
		<div style="clear: both;"></div>
	</h1>
</div>

<div class="list-div" style="text-align:center">
	<form name="theForm" method="post" action="<?php echo lib_functions::url('goodsPurchase/edit/'.$response->id);?>" width="60%" onsubmit="return form_submit('theForm')">
	  
		<table class="table-div" cellspacing="0" cellpadding="0" border="1">
			<tr>
				<td align="right">单据编号：</td>
				<td><input type="text" name="djbh" class="required" readOnly='readOnly' value="<?php echo $response->result['djbh'];?>" /></td>
				<td align="right">业务日期：</td>
				<td>
					<input type="text" name="ywrq" class="required" value="<?php echo date('Y-m-d H:i',$response->result['ywrq']);?>"/>
				</td>
			</tr>
	
			<tr>
				<td align="right">供应商：</td>
				<td>
					<select name="supplier"  class="required">
						<option value="">请选择</option>
						<?php foreach($response->suppliers as $supplier): ?>
						<option value="<?php echo $supplier['id'];?>" <?php if($supplier['id'] == $response->result['supper_id']) { echo 'selected';}?>>
							<?php echo $supplier['name'];?>
						</option>
						<?php endforeach; ?>
					</select>
				</td>
				<td align="right" >仓库：</td>
				<td>
					<select name="warehouse"  class="required">
						<option value="">请选择</option>
						<?php foreach($response->warehouses as $warehouse): ?>
						<option value="<?php echo $warehouse['id'];?>" <?php if($warehouse['id'] == $response->result['warehouse_id']) { echo 'selected';} ?>><?php echo $warehouse['name'];?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right">制单人：</td>
				<td><input type='text' name='maker' value="<?php echo $response->result['maker'];?>" readOnly="readOnly" />
				<td align="right">制单日期：</td>
				<td><input type='text' name='maker' value="<?php echo date('Y-m-d H:i:s',$response->result['create_time']);?>" readOnly="readOnly" />
			</tr>
			<tr>
				<td align="right">状态：</td>
				<td style='color:red'>
				<?php 
					if($dj['status'] == 1) {
						echo '已验收';
					}elseif($dj['status'] == 0) {
						echo '未验收';
					}
				?>
				</td>
				<td align="right">描述：</td>
				<td>
					<input type='text' name='desc' title="<?php echo $response->result['description'];?>" value="<?php echo $response->result['description'];?>" />
				</td>
			</tr>
			<tr>
				<td colspan='4' align='center'>
					<input type="button" name="ys" value="验收" onclick="ys_dj('<?php echo $response->id;?>')"/>
					<input type="submit" name="submit" value="保存" />
					<input type="button" name="back" value='返回' onclick="back_list()">
				</td>
			</tr>
			

		</table>
	</form>

</div>
<div class="list-div">
<!-- 商品明细 -->

	<table cellspacing="1" cellpadding="3" width="100%">
	  <tr>
		<th colspan="10">商品信息</th>
	  </tr>
	  <tr>
		<th>商品</th>
		<th>颜色</th>
		<th>尺码</th>
		<th>数量</th>
		<th>操作</th>
	  </tr>
	  <?php foreach($response->goods as $goods):?>
	  <tr>
		<td align="center"><?php echo $goods['goods_name']?>(<?php echo $goods['goods_sn']?>)</td>
		<td align="center"><?php echo $goods['color_name']?>(<?php echo $goods['color_code']?>)</td>
		<td align="center"><?php echo $goods['size_name']?>(<?php echo $goods['size_code']?>)</td>
		<td align="center"><?php echo $goods['goods_number'];?></td>
		<td align="center">
			<a href="javascript:remove_goods('<?php echo $goods['id'];?>');">删除</a>
		</td>
		</tr>
	  <?php endforeach;?>
	</table>
	<div id="edit_goods">
		商品货号<input type="text" name="edit_goods_sn" id="edit_goods_sn" />
		<input type="button" value="搜索" onclick="show_select_goods()"/>
	</div>
</div>

<script type="text/javascript">

function show_select_goods() {
	var obj = new Object();
	obj.action = '<?php echo lib_functions::url('goodsPurchase/add_goods/'.$response->id);?>';
	var returnValue = window.showModalDialog('<?php echo lib_functions::url('goods/select_goods/');?>','','resizable=yes;dialogWidth=700px');

	if(returnValue){
		var goods_id = returnValue;
		var url = '<?php echo lib_functions::url('goods/select_goods_one/');?>'+goods_id;

		var rV = window.showModalDialog(url,obj,'resizable=yes;dialogWidth=700px');
		
		if(rV) {
			window.location.reload();
		}

	}else {
		window.location.reload();
	}

}

function back_list() {
	var url = '<?php echo lib_functions::url('goodsPurchase/getList');?>';
	window.location.href = url;
}

function ys_dj(id) {
	if(!confirm('你确认要验收？')) {
		return false;
	}
	var url = '<?php echo lib_functions::url('goodsPurchase/ys/');?>'+id;
	$.post(url,{},function (data) {
		if(data.status == 0) {
			alert(data.msg);
			var url_head =  '<?php echo lib_functions::url('goodsPurchase/view/');?>'+id;
			window.location.href= url_head;
		}else {
			alert(data.msg);
		}
	},'json');
	return false;
}
</script>