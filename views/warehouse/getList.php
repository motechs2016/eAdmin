<div class="pageheader">
	<h1>
		<span class="action-span1">仓库--列表</span>
		<div style="clear: both;"></div>
	</h1>
</div>

<div class="list-div" style="text-align:center">
	  
		<table class="table-div" cellspacing="0" cellpadding="0" border="1">
			<tr>
				<th>仓库名称</th>
				<th>仓库描述</th>
				<th>操作</th>
			</tr>
			<?php foreach($response->list as $list):?>
			<tr>
				<td align="center"><?php echo $list['name'];?></td>
				<td align="center"><?php echo $list['description'];?></td>
				<td align="center">
					<?php echo lib_functions::action('warehouse/delete/'.$list['id'],'删除',array('onclick'=>"return remove_shop({$list['id']})"));?>
				</td>
			</tr>
			<?php endforeach;?>
		</table>
</div>
<script type="text/javascript">

function remove_shop(id) {
	if(!confirm('确认删除？')) {
		return false;
	}
	return true;
}

</script>