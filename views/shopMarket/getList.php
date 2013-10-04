<div class="pageheader">
	<h1>
		<span class="action-span1">店铺--列表</span>
		<div style="clear: both;"></div>
	</h1>
</div>

<div class="list-div" style="text-align:center">
	  
		<table class="table-div" cellspacing="0" cellpadding="0" border="1">
			<tr>
				<th>店铺名称</th>
				<th>店铺代码</th>
				<th>销售平台</th>
				<th>操作</th>
			</tr>
			<?php foreach($response->list as $list):?>
			<tr>
				<td align="center"><?php echo $list['name'];?></td>
				<td align="center"><?php echo $list['code'];?></td>
				<td align="center"><?php echo $list['class_name'];?></td>
				<td align="center">
					<?php echo lib_functions::action('shopMarket/view/'.$list['id'],'查看');?>
					<?php echo lib_functions::action('shopMarket/edit/'.$list['id'],'编辑');?>
					<?php echo lib_functions::action('shopMarket/delete/'.$list['id'],'删除',array('onclick'=>"return remove_shop({$list['id']})"));?>
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