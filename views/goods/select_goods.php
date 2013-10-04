<?php if($response->fullpage):?>
<div class="list-div">
	<form action = '' name="searchForm" onsubmit="return search_form();">
		商品编码<input type="text" name="goods_sn"/>&nbsp;&nbsp;商品名称<input type="text" name="goods_name"/>&nbsp;&nbsp;
		分类<select name="cat_id">
		<option value="">全部</option>
		<?php foreach($response->categories as $cat):?>
		<option value="<?php echo $cat['id']?>"><?php echo $cat['name'];?></option>
		<?php endforeach;?>
		</select>
		&nbsp;&nbsp;品牌
		<select name="brand_id">
		<option value="">全部</option>
		<?php foreach($response->brands as $brand):?>
		<option value="<?php echo $brand['id'];?>"><?php echo $brand['name']?></option>
		<?php endforeach;?>
		</select>
		&nbsp;&nbsp;<input type="submit" value="搜索" name="search"  />
	</form>
</div>
<div id="select_goods_div"  class="list-div">
<?php endif;?>


<table cellspacing="1" cellpadding="3" >
	<tr>
		<th>商品编码</th>
		<th>商品名称</th>
		<th>分类</th>
		<th>品牌</th>
		<th>价格</th>
		<th>在售</th>
		<th>库存</th>
		<th>操作</th>
	</tr>
	<?php foreach($response->goods as $goods):?>
	<tr>
		<td align="center"><?php echo $goods['goods_sn'];?> </td>
		<td align="center"><?php echo $goods['goods_name'];?> </td>
		<td align="center"><?php echo $goods['cat_name'];?> </td>
		<td align="center"><?php echo $goods['brand_name'];?> </td>
		<td align="center"><?php echo $goods['market_price'];?> </td>
		<td align="center"><?php echo $goods['is_sale'] == 1? lib_functions::image('shop/yes.gif'):lib_functions::image('shop/no.gif');?> </td>
		<td align="center"></td>
		<td align="center">
		<a href="javascript:javascript:select_goods_one(<?php echo $goods['id'] ?>)">添加</a>
		</td>
	</tr>
	<?php endforeach;?>
	<tr>
		<td colspan="8" align="right">
			共<?php echo count($response->goods);?>条 &nbsp;&nbsp;
		</td>
	</tr>
 </table>

<?php if($response->fullpage):?>
</div>

<script type="text/javascript">
var Utils = new Utils();


function search_form() {
	var eles = document.forms.searchForm.elements;
	var goods_sn = Utils.trim(eles['goods_sn'].value);
	var goods_name = Utils.trim(eles['goods_name'].value);
	var cat_id = eles['cat_id'].value;
	var brand_id = eles['brand_id'].value;
	var url = "<?php echo lib_functions::url('goods/select_goods')?>";

	$.post(url,{'goods_sn':goods_sn,'goods_name':goods_name,
				'cat_id':cat_id,'brand_id':brand_id,'act':'query'},
		function(data) {
			document.getElementById('select_goods_div').innerHTML = data;

	});

	return false;

}

var order_id =  window.dialogArguments.order_id;
var obj = new Object();
obj.order_id = order_id;

function select_goods_one(goods_id) {
	
	window.returnValue = goods_id;
	window.opener = null;
	window.close();
	return false;
	
}

</script>

<?php endif;?>