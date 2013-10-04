<div class="pageheader">
	<h1>
		<span class="action-span1">会员--列表</span>
		<div style="clear: both;"></div>
	</h1>
</div>

<div class="list-div" style="text-align:center">
	  
		<table class="table-div" cellspacing="0" cellpadding="0" border="1">
			<tr>
				<th>会员名</th>
				<th>性别</th>
				<th>手机</th>
				<th>email</th>
				<th>收货人</th>
				<th>地址</th>
				<th>最后购买时间</th>
				<th>操作</th>
			</tr>
			<?php foreach($response->list as $list):?>
			<tr>
				<td align="center"><?php echo $list['user_name'];?></td>
				<td align="center"><?php echo $list['sex']==1?'男':'女';?></td>
				<td align="center"><?php echo $list['mobile'];?></td>
				<td align="center"><?php echo $list['email'];?></td>
				<td align="center"><?php echo $list['consignee'];?></td>
				<td align="center"><?php echo $list['address'];?></td>
				<td align="center">
				<?php echo date('Y-m-d',$list['last_login']);?>			
				</td>
				<td align="center">
				<?php echo lib_functions::action('users/edit/'.$list['id'],'编辑');?>			
				</td>
				
			</tr>
			<?php endforeach;?>
		</table>
</div>
<script type="text/javascript">



</script>