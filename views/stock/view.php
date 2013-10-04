<?php if(empty($response->query)):?>
<div class="pageheader">
	<h1>
		<span class="action-span1">库存帐查询</span>
		<div style="clear: both;"></div>
	</h1>
</div>

<div class="form-div">
	<form name="searchForm" action="<?php echo lib_functions::url('stock/query');?>" onsubmit="return query();">
		商品<input type="text" name="goods_key" />
		条形码<input type='text' name='barcode' />
		仓库<select name='warehouse_id'>
			<option value=''>全部</option>
			<?php foreach($response->warehouses as $h):?>
			<option value='<?php echo $h['id'];?>'><?php echo $h['name'];?></option>
			<?php endforeach;?>
		<input type="submit" name="submit" value="查询" class='button'/>
	</form>
</div>

<div class="list-div" style="text-align:center" id="listDiv">
<?php endif;?>

		<table class="table-div" cellspacing="0" cellpadding="0" border="1">
			<tr>
				<th>商品名称</th>
				<th>颜色</th>
				<th>尺码</th>
				<th>仓库</th>
				<th>实际库存</th>
				<th>锁定库存</th>
				<th>警告库存</th>				
			</tr>
			<?php foreach($response->list as $list):?>
			<tr>
				<td align="center" rowspan="<?php echo count($list);?>"><?php echo "{$list[0]['goods_name']} ({$list[0]['goods_sn']})";?></td>
				<?php foreach($list as $n=>$v):?>
				<?php if($n):?><tr> <?php endif;?>
				<td align="center"><?php echo "{$v['color_name']} ({$v['color_code']})";?></td>
				<td align="center"><?php echo "{$v['size_name']} ({$v['size_code']})";?></td>
				<td align="center"><?php echo "{$v['warehouse_name']}";?></td>
				<td align="center"><?php echo "{$v['actual_quantity']}";?></td>
				<td align="center"><?php echo "{$v['lock_quantity']}";?></td>
				<td align="center"><span class="warn_kc" value="<?php echo $v['id'];?>"><?php echo "{$v['warn_quantity']}";?></span></td>
				</tr>
				<?php endforeach;?>
			
			<?php endforeach;?>
			<tr>
				<td align='right' colspan='7'>
				<?php  include(DS.'/views/page/page.php');?>
				</td>
			</tr>
		</table>
<?php if(empty($response->query)):?>

</div>
<script type="text/javascript">

page.query = '<?php echo lib_functions::url('stock/view')?>';
var Utils = new Utils();

function query() {
	
	var ele = document.forms['searchForm'].elements;
	page.filter = {};
	page.filter.goods_key = Utils.trim(ele['goods_key'].value);
	page.filter.barcode = Utils.trim(ele['barcode'].value);
	page.filter.warehouse_id = Utils.trim(ele['warehouse_id'].value);
	page.filter.query = 1;
	page.reload();
	return false;
}

function remove_shop(id) {
	if(!confirm('确认删除？')) {
		return false;
	}
	return true;
}

//编辑警告库存
var objs = document.getElementsByTagName('span');

for(var i=0;i<objs.length;i++) {
	if(objs[i].className != 'warn_kc') {
		continue;
	}
	
	objs[i].onmouseover = function() {
		this.style.backgroundColor = 'red';
	}
	objs[i].onmouseout = function() {
		this.style.backgroundColor = '';
	}
	objs[i].onclick = function() {
		
		var value = this.innerHTML;
		var id = this.getAttribute('value');
		if(Utils.is_int(value)) {
			var input_obj = document.createElement('input');
			input_obj.type = 'text';
			input_obj.title = id;
			input_obj.size = 5;
			input_obj.value = value;
			input_obj.onblur = function() {
				set_warn_num(this);
			};
			input_obj.onkeyup = function() {
				check_type(this);
			}
			this.innerHTML = '';
			this.appendChild(input_obj);
			input_obj.focus();
		}
	
	}
	

}

function set_warn_num(obj)　{
	var val = Utils.trim(obj.value);
	var id = obj.title;
	var url = '<?php echo lib_functions::url('stock/update_warn');?>';
	HTTP.post(url,{'id':id,'value':val},function() {
		var p_obj = obj.parentNode;
		p_obj.innerHTML = val;
	});

}

function check_type(obj) {
	var value = Utils.trim(obj.value);

	//判断是否是正整数
	var regex = /^\d+$/;
	if(regex.test(value)) {
		//符合条件
	}else {
		obj.value = 0;
	}
}

</script>

<? endif;?>