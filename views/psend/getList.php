<?php if(empty($response->query)):?>
<?php 
echo $head->script('My97DatePicker/WdatePicker.js');
?>
<h1>拣货单列表</h1>
<div class="form-div">
	<form name="searchForm" action="<?php echo lib_functions::url('stock/query');?>" onsubmit="return query();">
	
		快递公司<select name='ship_id'>
				<option value="">全部</option>
				<?php foreach($response->ships as $h):?>
					<option value='<?php echo $h['id'];?>'><?php echo $h['name'];?></option>
					<?php endforeach;?>
				</select>
		仓库<select name='warehouse_id'>
				<option value="">全部</option>
				<?php foreach($response->warehouse as $h):?>
					<option value='<?php echo $h['id'];?>'><?php echo $h['name'];?></option>
					<?php endforeach;?>
				</select>
		业务时间<input type="text" name="start_add_time" class="required" readOnly='readOnly' value="" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',readOnly:true})"/>
		~<input type="text" name="end_add_time" class="required" readOnly='readOnly' value="" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',readOnly:true})"/>
		<input type="submit" name="submit" value="查询" class='button'/>
	</form>
</div>


<div id="listDiv" class="list-div">
<?php endif;?>
	<table cellspacing="1" cellpadding="3">
		  <tr>
				<th>
				  <input type="checkbox" id="check_all" onclick ="Utils.check_all('check_all','check_id');"/>拣货单号</th>
				<th>仓库</th>
				<th>快递方式</th>
				<th>操作人</th>
				<th>业务日期</th>
				<th>状态</th>
				<th>操作</th>
		  </tr>
		  <?php foreach($response->list as $list):?>
		  <tr>
				<td nowrap="nowrap" align="center" valign="top" style="background-color: rgb(255, 255, 255);">
					<input type="checkbox" name="check_id" value="<?php echo $list['id'];?>"/><?php echo lib_functions::action("psend/info/{$list['id']}",$list['djbh']) ;?>
				</td>
				<td nowrap="nowrap" align="center" valign="top" style="background-color: rgb(255, 255, 255);">
					<?php echo $list['warehouse_name'];?>
				</td>
				<td nowrap="nowrap" align="center" valign="top" style="background-color: rgb(255, 255, 255);">
					<?php echo $list['ship_name'];?>
				</td>
				<td nowrap="nowrap" align="center" valign="top" style="background-color: rgb(255, 255, 255);">
					<?php echo $list['maker'];?>
				</td>
				<td nowrap="nowrap" align="center" valign="top" style="background-color: rgb(255, 255, 255);">
					<?php echo date('Y-m-d H:i',$list['time']);?>
				</td>
				<td nowrap="nowrap" align="center" valign="top" style="background-color: rgb(255, 255, 255);">
					<?php echo $list['status']==0?'未验收':'已验收';?>
				</td>
				<td nowrap="nowrap" align="center" valign="top" style="background-color: rgb(255, 255, 255);">
					<?php echo lib_functions::action("psend/info/{$list['id']}",'查看');?>
				</td>
				
		  </tr>
		  <?php endforeach;?>
		  <tr>
				<td align='right' colspan='10'>
				<?php  include(DS.'/views/page/page.php');?>
				</td>
		  </tr>
		 
	  </table>
<?php if(empty($response->query)):?>		
</div>
<script type="text/javascript">
var Utils = new Utils();
page.query = '<?php echo lib_functions::url('psend/getList')?>';

function query() {
	
	var ele = document.forms['searchForm'].elements;
	page.filter = {};

	page.filter.ship_id = ele['ship_id'].value;
	page.filter.warehouse_id = ele['warehouse_id'].value;
	page.filter.start_add_time = ele['start_add_time'].value;
	page.filter.end_add_time = ele['end_add_time'].value;
	page.filter.query = 1;
	
	page.reload();

	return false;
}


</script>
<?php endif;?>