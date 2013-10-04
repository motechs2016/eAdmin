<div class="pageheader">
	<h1>
		<span class="action-span1">拣货单明细</span>
		<div style="clear: both;"></div>
	</h1>
</div>

<div class="list-div" style="text-align:center">
	<form name="theForm" method="post" action="<?php echo lib_functions::url('goodsPurchase/edit/'.$response->id);?>" width="60%" onsubmit="return form_submit('theForm')">
	  
		<table class="table-div" cellspacing="0" cellpadding="0" border="1">
			<tr>
				<td align="right">单据编号：</td>
				<td><?php echo $response->result['djbh'];?></td>
				<td align="right">业务日期：</td>
				<td>
					<?php echo date('Y-m-d H:i',$response->result['time']);?>
				</td>
			</tr>
	
			<tr>
				<td align="right">仓库：</td>
				<td>
					<?php echo $response->result['warehouse_name'];?>
				</td>
				<td align="right" >快递方式：</td>
				<td>
					<?php echo $response->result['ship_name'];?>
				</td>
			</tr>
			<tr>
				<td align="right">制单人：</td>
				<td><?php echo $response->result['maker'];?></td>
				<td align="right">状态：</td>
				<td style='color:red'><?php echo $response->result['status']==0?'未验收':'已验收';?></td>
			</tr>
			
			<tr>
				<td colspan='4' align='center'>
					<?php if($response->result['status']==0):?>
					<input type="button" name="ys" value="验收" onclick="ys_dj('<?php echo $response->id;?>')"/>
					<?php endif;?>
					<input type="button" name="back" value='返回' onclick="window.history.back()">
				</td>
			</tr>
			

		</table>
	</form>

</div>
<div class="list-div">
<!-- 商品明细 -->

	<table cellspacing="1" cellpadding="3" width="100%">
	  <tr>
		<th colspan="10">商品信息</th>
	  </tr>
	  <tr>
		<th>订单号</th>
		<th>商品</th>
		<th>颜色</th>
		<th>尺码</th>
		<th>数量</th>
		<th>金额</th>
		
	  </tr>
	  <?php foreach($response->goods as $k=>$v):?>
	  <tr>
		<td align="center" rowspan="<?php echo count($v);?>"><?php echo lib_functions::action('order/info/'.$v[0]['order_id'],$k);?></td>
		<?php foreach($v as $goods):?>
		<td align="center"><?php echo $goods['goods_name']?>(<?php echo $goods['goods_sn']?>)</td>
		<td align="center"><?php echo $goods['color_name']?>(<?php echo $goods['color_code']?>)</td>
		<td align="center"><?php echo $goods['size_name']?>(<?php echo $goods['size_code']?>)</td>
		<td align="center"><?php echo $goods['goods_number'];?></td>
		<td align="center"><?php echo $goods['pay_fee'];?></td>
		
		</tr>
		<?php endforeach;?>
		
	  <?php endforeach;?>
	</table>
	
</div>

<script type="text/javascript">

function ys_dj(id) {
	if(!confirm('你确认要验收？')) {
		return false;
	}
	var url = '<?php echo lib_functions::url('psend/ys/');?>'+id;
	HTTP.post(url,{},function (data) {
		if(data.status == 0) {
			alert(data.msg);
			var url_head =  '<?php echo lib_functions::url('psend/info/');?>'+id;
			window.location.href= url_head;
		}else {
			alert(data.msg);
		}
	},'json');
	return false;
}
</script>