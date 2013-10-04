<div class="pageheader">
	<h1>
		<span class="action-span1">店铺--添加 </span>
		<div style="clear: both;"></div>
	</h1>
</div>

<div class="list-div" style="text-align:center">
	<form name="theForm" method="post" action="<?php echo lib_functions::url('shopMarket/add');?>" width="60%" >
	  
		<table class="table-div" cellspacing="0" cellpadding="0" border="1">
			<tr>
				<td align="right">店铺名称：</td>
				<td><input type="text" name="name" class="required" value="<?php echo $response->shop['name'];?>" /></td>
			</tr>
			<tr>
				<td align="right">店铺代码：</td>
				<td><input type="text" name="code" class="required" readOnly='readOnly' value="<?php echo $response->shop['code'];?>"/></td>
			</tr>
			<tr>
				<td align="right">销售平台：</td>
				<td>
					<select name="class_id"  class="required">
						<option value="">请选择</option>
						<?php foreach($response->shop_class as $class): ?>
						<option value="<?php echo $class['id'];?>" <?php if($response->shop['class_id']== $class['id']) {echo 'selected';}?>><?php echo $class['name'];?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
		</table>
		<table class="table-div" cellspacing="0" cellpadding="0" border="1">

			<tr>
				<td align="right">参数一名称：</td>
				<td><input type="text" name="params_key" value="<?php echo isset($response->api_params[0][0])?$response->api_params[0][0]:'';?>" /></td>
				<td align="right">值：</td>
				<td><input type="text" name="params_value" value="<?php echo isset($response->api_params[0][1])?$response->api_params[0][1]:'';?>"  size="35"/></td>
			</tr>
			<tr>
				<td align="right">参数二名称：</td>
				<td><input type="text" name="params_key" value="<?php echo isset($response->api_params[1][0])?$response->api_params[1][0]:'';?>" /></td>
				<td align="right">值：</td>
				<td><input type="text" name="params_value" value="<?php echo isset($response->api_params[1][1])?$response->api_params[1][1]:'';?>"  size="35"/></td>
			</tr>
			<tr>
				<td align="right">参数三名称：</td>
				<td><input type="text" name="params_key" value="<?php echo isset($response->api_params[2][0])?$response->api_params[2][0]:'';?>" /></td>
				<td align="right">值：</td>
				<td><input type="text" name="params_value" value="<?php echo isset($response->api_params[2][1])?$response->api_params[2][1]:'';?>"  size="35"/></td>
			</tr>
			<tr>
				<td align="right">参数四名称：</td>
				<td><input type="text" name="params_key" value="<?php echo isset($response->api_params[3][0])?$response->api_params[3][0]:'';?>" /></td>
				<td align="right">值：</td>
				<td><input type="text" name="params_value" value="<?php echo isset($response->api_params[3][1])?$response->api_params[3][1]:'';?>"  size="35"/></td>
			</tr>
			<tr>
				<td colspan="4" align="center">
					<input type="submit" name="submit" value='提交' class="button">
					<input type="button" name="back" value='返回' class="button" onclick="back_list()">
				</td>
			</tr>
		</table>
	</form>
</div>

<script type="text/javascript">
	document.forms['theForm'].onsubmit = function () {
		var b = form_submit('theForm');
		if(!b) {
			return false;
		}
		var params = new Object();
		var ele = document.forms['theForm'].elements;
		var url = '<?php echo lib_functions::url('shopMarket/edit');?>';
		params.name = ele['name'].value;
		params.code = ele['code'].value;
		params.class_id = ele['class_id'].value;
		params.submit = 1;
		params.keys = new Array();
		params.values = new Array();
		var keys = document.getElementsByName('params_key');
		var values = document.getElementsByName('params_value');
		for(var i=0;i<keys.length;i++) {
			params.keys[i] = keys[i].value;
			params.values[i] = values[i].value;
		}
	
		$.post(url,params,function(data){
			if(data.status==0) {
				alert(data.msg);
				window.location.reload();
			}else {
				alert(data.msg);
			}
		},'json');
		
		return false;
	}

	function back_list() {
		var url = '<?php echo lib_functions::url('shopMarket/getList');?>';
		window.location.href=url;
	}
</script>