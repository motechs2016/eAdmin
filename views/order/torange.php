<?php if(empty($response->query)):?>
<?php 
echo $head->script('My97DatePicker/WdatePicker.js');
?>
<h1>通知配货订单列表</h1>
<div class="form-div">
	<form name="searchForm" action="<?php echo lib_functions::url('stock/query');?>" onsubmit="return query();">
		订单号<input type="text" name="order_sn" />
		交易号<input type="text" name="deal_code" />
		店铺<select name='shop_id'>
			<option value=''>全部</option>
			<?php foreach($response->shops as $h):?>
			<option value='<?php echo $h['id'];?>'><?php echo $h['name'];?></option>
			<?php endforeach;?>
			</select>
		收货人<input type="text" name="consignee" />
		状态<select name='status'>
				<option value="">全部</option>
				<option value="0">未确定</option>
				<option value="1">已确定</option>
				<option value="2">通知配货</option>
			</select>
		付款状态<select name='pay_status'>
				<option value="">全部</option>
				<option value="0">未付款</option>
				<option value="1">已付款</option>
			</select>
		<br/>
		快递公司<select name='ship_id'>
				<option value="">全部</option>
				<?php foreach($response->ships as $h):?>
					<option value='<?php echo $h['id'];?>'><?php echo $h['name'];?></option>
					<?php endforeach;?>
				</select>
		支付方式<select name='pay_id'>
				<option value="">全部</option>
				<?php foreach($response->payments as $h):?>
					<option value='<?php echo $h['id'];?>'><?php echo $h['name'];?></option>
					<?php endforeach;?>
				</select>
		下单时间<input type="text" name="start_add_time" class="required" readOnly='readOnly' value="" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',readOnly:true})"/>
		~<input type="text" name="end_add_time" class="required" readOnly='readOnly' value="" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',readOnly:true})"/>
		<input type="submit" name="submit" value="查询" class='button'/>
	</form>
</div>


<div id="listDiv" class="list-div">
<?php endif;?>
	<table cellspacing="1" cellpadding="3">
		  <tr>
				<th>
				  <input type="checkbox" id="check_all" onclick ="Utils.check_all('check_all','check_id');"/>订单号 </th>
				<th>交易号</th>
				<th>下单时间</th>
				<th>收货人</th>
				<th>店铺</th>
				<th>总金额</th>
				<th>订单状态</th>
				<th>支付</th>
				<th>备注</th>
				<th>操作</th>
		  </tr>
		  <?php foreach($response->order_list as $list):?>
		  <tr>
				<td nowrap="nowrap" align="center" valign="top" style="background-color: rgb(255, 255, 255);">
					<input type="checkbox" name="check_id" value="<?php echo $list['id'];?>"/><?php echo lib_functions::action("order/info/{$list['id']}",$list['order_sn']) ;?>
				</td>
				<td nowrap="nowrap" align="center" valign="top" style="background-color: rgb(255, 255, 255);">
					<?php echo $list['deal_code'];?>
				</td>
				<td nowrap="nowrap" align="center" valign="top" style="background-color: rgb(255, 255, 255);">
					<?php echo date('Y-m-d H:i',$list['add_time']);?>
				</td>
				<td nowrap="nowrap" align="center" valign="top" style="background-color: rgb(255, 255, 255);">
					<?php echo $list['consignee'];?>
				</td>
				<td nowrap="nowrap" align="center" valign="top" style="background-color: rgb(255, 255, 255);">
					<?php echo $list['shop_name'];?>
				</td>
				<td nowrap="nowrap" align="center" valign="top" style="background-color: rgb(255, 255, 255);">
					<?php echo $list['total_amount'];?>
				</td>
				<td nowrap="nowrap" align="center" valign="top" style="background-color: rgb(255, 255, 255);">
					<?php 
					switch($list['status']){
						case 0:
							echo '未确定';break;
						case 1:
							echo '确定';break;
						case 2:
							echo '通知配货';break;
						case 3:
							echo '已发货';break;
					}
					?>
				</td>
				<td nowrap="nowrap" align="center" valign="top" style="background-color: rgb(255, 255, 255);">
					<?php echo $list['pay_status']==0?'未付款':'已付款';?>
				</td>
				<td nowrap="nowrap" align="center" valign="top" style="background-color: rgb(255, 255, 255);" title="<?php echo $list['bz'];?>">
					<?php echo substr_replace($list['bz'],'...',15);?>
				</td>
				<td nowrap="nowrap" align="center" valign="top" style="background-color: rgb(255, 255, 255);">
					<?php echo lib_functions::action("order/info/{$list['id']}",'查看');?>
				</td>
				
		  </tr>
		  <?php endforeach;?>
		  <tr>
				<td align='right' colspan='10'>
				<?php  include(DS.'/views/page/page.php');?>
				</td>
		  </tr>
		  <tr>
				<td colspan="10" align="left">
					<input type="button" class="button" value="生成拣货单" onclick="make_psend()"/>
				</td>
		  </tr>
	  </table>
<?php if(empty($response->query)):?>		
</div>
<script type="text/javascript">
var Utils = new Utils();
page.query = '<?php echo lib_functions::url('order/torange')?>';

function query() {
	
	var ele = document.forms['searchForm'].elements;
	page.filter = {};
	
	page.filter.order_sn = Utils.trim(ele['order_sn'].value);
	page.filter.deal_code = Utils.trim(ele['deal_code'].value);
	page.filter.consignee = Utils.trim(ele['consignee'].value);
	page.filter.shop_id = ele['shop_id'].value;
	page.filter.status = ele['status'].value;
	page.filter.pay_status = ele['pay_status'].value;
	page.filter.ship_id = ele['ship_id'].value;
	page.filter.pay_id = ele['pay_id'].value;
	page.filter.start_add_time = ele['start_add_time'].value;
	page.filter.end_add_time = ele['end_add_time'].value;
	page.filter.query = 1;
	
	page.reload();

	return false;
}

function make_psend() {
	var ids_obj = document.getElementsByName('check_id');
	var id_arr = new Array();
	for(var i=0;i<ids_obj.length;i++) {
		if(ids_obj[i].checked) {
			id_arr.push(ids_obj[i].value);
		}
	}
	if(id_arr.length == 0) {
		alert('请选择');
		return;
	}
	if(!confirm('您确定操作？')) {
		return;
	}
	var url = '<?php echo lib_functions::url('psend/create');?>';
	HTTP.post(url,{'ids':id_arr},function(data){
		if(data.status == 0) {
			alert(data.msg);
			window.location.reload();
		}else {
			alert(data.msg);
		}
	},'json');

}
</script>
<?php endif;?>