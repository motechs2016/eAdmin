<div class="pageheader">
	<h1>
		<span class="action-span1">店铺仓库关联--列表</span>
		<div style="clear: both;"></div>
	</h1>
</div>

<div class="list-div" style="text-align:center">
	  
		<table class="table-div" cellspacing="0" cellpadding="0" border="1">
			<tr>
				<th>店铺名称</th>
				<th>仓库名称</th>
				<th>优先级</th>
				<th>操作</th>
			</tr>
			<?php foreach($response->list as $list):?>
			<tr>
				<td align='center' rowspan="<?php echo count($list);?>">
					<?php echo $list[0]['shop_name'];?>
				</td>
				<?php foreach($list as $v):?>
				<td align="center"><?php echo $v['ware_name'];?></td>
				<td align="center"><?php echo $v['level'];?></td>
				<td align="center">
					<?php echo lib_functions::action('warehouse/delete_relation/'.$v['id'],'删除',array('onclick'=>"return remove_shop({$v['id']})"));?>
				</td>
				</tr>
				<?php endforeach;?>
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