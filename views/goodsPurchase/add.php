<?php 
echo $head->script('My97DatePicker/WdatePicker.js');
?>
<div class="pageheader">
	<h1>
		<span class="action-span1">商品进货单--添加 </span>
		<div style="clear: both;"></div>
	</h1>
</div>

<div class="list-div" style="text-align:center">
	<form name="theForm" method="post" action="<?php echo lib_functions::url('goodsPurchase/add');?>" width="60%" onsubmit="return form_submit('theForm')">
	  
		<table class="table-div" cellspacing="0" cellpadding="0" border="1">
			<tr>
				<td align="right">单据编号：</td>
				<td><input type="text" name="djbh" class="required" value="<?php echo $response->djbh;?>" /></td>
			</tr>
			<tr>
				<td align="right">业务日期：</td>
				<td><input type="text" name="ywrq" class="required" readOnly='readOnly' value="<?php echo date('Y-m-d H:i')?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',readOnly:true})"/></td>
			</tr>
			<tr>
				<td align="right">供应商：</td>
				<td>
					<select name="supplier"  class="required">
						<option value="">请选择</option>
						<?php foreach($response->suppliers as $supplier): ?>
						<option value="<?php echo $supplier['id'];?>"><?php echo $supplier['name'];?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right" >仓库：</td>
				<td>
					<select name="warehouse"  class="required">
						<option value="">请选择</option>
						<?php foreach($response->warehouses as $warehouse): ?>
						<option value="<?php echo $warehouse['id'];?>"><?php echo $warehouse['name'];?></option>
						<?php endforeach; ?>
					</select>
				</td>
			</tr>
			<tr>
				<td align="right">描述：</td>
				<td>
					<textarea name="desc" cols="24" rows="3">

					</textarea>
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
