<div class="pageheader">
	<h1>
		<span class="action-span1">会员--<?php echo $response->user_name;?>
			<input type="button" class="header_button" value="返回" onclick="window.history.go(-1);"/>
		</span>
		<div style="clear: both;"></div>
	</h1>
</div>

<div class="list-div" style="text-align:center">
	  
		<table class="table-div" cellspacing="0" cellpadding="0" border="1">
			<tr>
				<th>省</th>
				<th>市</th>
				<th>区</th>
				<th>详细地址</th>
				<th>收货人</th>
				<th>电话</th>
				<th>手机</th>
				<th>默认</th>
				<th>操作</th>
			</tr>
			<?php foreach($response->list as $list):?>
			<tr>
				<td align="center"><?php echo $list['province_name'];?></td>
				<td align="center"><?php echo $list['city_name'];?></td>
				<td align="center"><?php echo $list['district_name'];?></td>
				<td align="center"><?php echo $list['address'];?></td>
				<td align="center"><?php echo $list['consignee'];?></td>
				<td align="center"><?php echo $list['tel'];?></td>
				<td align="center"><?php echo $list['mobile'];?></td>
				<td align="center">
					<?php 
					if($list['is_default']) {
						echo lib_functions::image('shop/state_ok.png');
					};
					?>
				</td>
				<td align="center">
					<a href="javascript:remove_address(<?php echo $list['id']?>)" >删除</a>
				</td>
			</tr>
			<?php endforeach;?>
		</table>
</div>
<script type="text/javascript">
function remove_address(id) {
	if(!confirm('您确定删除？')) {
		return;
	}
	var url = '<?php echo lib_functions::url('users/delete');?>';
	HTTP.post(url,{'id':id},function(data){
		if(data.status == 0) {
			alert(data.msg);
			window.location.reload();
		}else {
			alert(data.msg);
		}
	},'json');
}


</script>