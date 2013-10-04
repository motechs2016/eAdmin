<div class="pageheader">
	<h1>
		<span class="action-span1">仓库--添加 </span>
		<div style="clear: both;"></div>
	</h1>
</div>

<div class="list-div" style="text-align:center">
	<form name="theForm" method="post" action="<?php echo lib_functions::url('warehouse/add');?>" width="60%" onsubmit="return form_submit('theForm')">
	  
		<table class="table-div" cellspacing="0" cellpadding="0" border="1">
			<tr>
				<td align="right">仓库名称：</td>
				<td><input type="text" name="name" class="required" value="" /></td>
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

