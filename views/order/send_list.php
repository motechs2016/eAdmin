<?php if(empty($response->query)):?>

<h1>发货订单列表</h1>
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
	
		付款状态<select name='pay_status'>
				<option value="">全部</option>
				<option value="0">未付款</option>
				<option value="1">已付款</option>
			</select>
		<br/>
		发货状态<select name='status'>
				<option value="">全部</option>
				<option value="4" selected='selected'>未发货</option>
				<option value="5" >已发货</option>
			</select>
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
				<th>地址</th>
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
				<td nowrap="nowrap" align="center" valign="top" style="background-color: rgb(255, 255, 255);" title="<?php echo $list['province_name'].$list['city_name'].$list['district_name'].$list['address'];?>">
					<?php 
						$address = $list['province_name'].$list['city_name'].$list['district_name'].$list['address'];
						echo $address;
					//	echo substr_replace($address,'...',35);
					?>
				</td>
				
				<td nowrap="nowrap" align="center" valign="top" style="background-color: rgb(255, 255, 255);">
					<?php echo lib_functions::action("order/info/{$list['id']}",'查看');?>

					<?php 
						if($list['status']==4) {
							echo lib_functions::action("order/send/{$list['id']}",'发货');
					    }
					?>
				</td>
				
		  </tr>
		  <?php endforeach;?>
		  <tr>
				<td align='right' colspan='8'>
				<?php  include(DS.'/views/page/page.php');?>
				</td>
		  </tr>
		  <?php if($response->pageinfo['filter']['status'] == 4):?>
		  <tr>
				<td colspan="10" align="left">
					<input type="button" class="button" value="批量发货" onclick="send_all()"/>
				</td>
		  </tr>
		  <?php endif;?>
	</table>

<?php if(empty($response->query)):?>		
</div>
<script type="text/javascript">
var Utils = new Utils();
page.query = '<?php echo lib_functions::url('order/send_list')?>';

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

function send_all() {
	var ids = document.getElementsByName('check_id');
	if(ids.length == 0) {
		alert('没有合适订单可以发货');
		return;
	}
	var id_arr = new Array();
	for(var i=0;i<ids.length;i++) {
		if(ids[i].checked) {
			id_arr.push(ids[i].value);
		}
	}
	var url = '<?php echo lib_functions::url('order/pl_send');?>';
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
