<div class="pageheader">
	<h1>
		<span class="action-span1">店铺--添加 </span>
		<div style="clear: both;"></div>
	</h1>
</div>

<div class="list-div" style="text-align:center">
	<form name="theForm" method="post" action="<?php echo lib_functions::url('shopMarket/add');?>" width="60%" onsubmit="return form_submit('theForm')">
	  
		<table class="table-div" cellspacing="0" cellpadding="0" border="1">
			<tr>
				<td align="right">店铺名称：</td>
				<td><input type="text" name="name" class="required" value="" /></td>
			</tr>
			<tr>
				<td align="right">店铺代码：</td>
				<td><input type="text" name="code" class="required" value=""/></td>
			</tr>
			<tr>
				<td align="right">销售平台：</td>
				<td>
					<select name="class_id"  class="required">
						<option value="">请选择</option>
						<?php foreach($response->shop_class as $class): ?>
						<option value="<?php echo $class['id'];?>"><?php echo $class['name'];?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right"></td>
				<td>
					<input type="submit" name="submit" value='提交'>
				</td>
			</tr>
		</table>
	</form>
</div>
