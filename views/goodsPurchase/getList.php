<div class="pageheader">
	<h1>
		<span class="action-span1">商品进货单--列表 </span>
		<div style="clear: both;"></div>
	</h1>
</div>

<div class="list-div" style="text-align:center">
	<table class="table-div" cellspacing="0" cellpadding="0" border="1">
		<tr>
			<th>单据编号</th>
			<th>供应商</th>
			<th>仓库</th>
			<th>业务日期</th>
			<th>描述</th>
			<th>状态</th>
			<th>操作</th>
		</tr>
		
		<?php foreach($response->list as $dj):?>
		<tr>
			<td align="center"><?php echo lib_functions::action('goodsPurchase/view/'.$dj['id'],$dj['djbh']);?></td>
			<td align="center"><?php echo $dj['supper'];?></td>
			<td align="center"><?php echo $dj['warehouse_name'];?></td>
			<td align="center"><?php echo date('Y-m-d H:i',$dj['ywrq']);?></td>
			<td align="center" title="<?php echo $dj['description'];?>">
				<?php echo substr_replace($dj['description'],'...',15);?>
			</td>
			<td align="center">
				<?php 
				if($dj['status'] == 1) {
					echo '已验收';
				}elseif($dj['status'] == 0) {
					echo '未验收';
				}
				
				?>
			
			</td>
			<td align="center">
			<?php echo lib_functions::action('goodsPurchase/view/'.$dj['id'],'查看');?>
			<?php 
				if($dj['status'] == 0) {
					echo lib_functions::action('goodsPurchase/edit/'.$dj['id'],'编辑');
					echo '&nbsp;';
					echo lib_functions::action('goodsPurchase/ys/'.$dj['id'],'验收',array('onclick'=>"return ys_dj({$dj['id']})"));
				}
			?>
			</td>
		</tr>
		<?php endforeach;?>
	</table>

</div>

<script type="text/javascript">
function ys_dj(id) {
	if(!confirm('你确认要验收？')) {
		return false;
	}
	var url = '<?php echo lib_functions::url('goodsPurchase/ys/');?>'+id;
	$.post(url,{},function (data) {
		if(data.status == 0) {
			alert(data.msg);
			window.location.reload();
		}else {
			alert(data.msg);
		}
	},'json');
	return false;
}
</script>