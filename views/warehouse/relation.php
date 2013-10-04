<div class="pageheader">
	<h1>
		<span class="action-span1">店铺仓库关联--添加 </span>
		<div style="clear: both;"></div>
	</h1>
</div>

<div class="list-div" style="text-align:center">
	<form name="theForm" method="post" action="<?php echo lib_functions::url('warehouse/add_relation');?>" width="60%" onsubmit="return form_submit('theForm')">
	  
		<table class="table-div" cellspacing="0" cellpadding="0" border="1">
			<tr>
				<td align="right">店铺名称：</td>
				<td>
					<select name='shop' class="required" >
						<option value="0">请选择</option>
					<?php foreach($response->shops as $shop):?>
						<option value="<?php echo $shop['id'];?>"><?php echo $shop['name'];?></option>
					<?php endforeach;?>
					</select>
				</td>
			</tr>
			
			<tr>
				<td align="right">仓库名称：</td>
				<td>
					<select name='warehouse' class="required" >
						<option value="0">请选择</option>
					<?php foreach($response->warehouses as $ware):?>
						<option value="<?php echo $ware['id'];?>"><?php echo $ware['name'];?></option>
					<?php endforeach;?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right">优先级：</td>
				<td><input type="text" name="level" class="required" value="1" /></td>
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

