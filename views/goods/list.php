<?php if(empty($response->query)):?>
<span><h1>商品列表</h1></span>
<div class="header_div">
	<span><input type="button" class="header_button" value="添加" onclick="window.location.href='<?php echo lib_functions::url('goods/add')?>'"/></span>
</div>
<div class="form-div">
	<form name="searchForm" action="<?php echo lib_functions::url('stock/query');?>" onsubmit="return query();">
		商品<input type="text" name="goods_key" />
		品牌<select name='brand_id'>
			<option value=''>全部</option>
			<?php foreach($response->brands as $h):?>
			<option value='<?php echo $h['id'];?>'><?php echo $h['name'];?></option>
			<?php endforeach;?>
			</select>
		分类<select name='cat_id'>
				<option value=''>全部</option>
				<?php foreach($response->cats as $h):?>
				<option value='<?php echo $h['id'];?>'><?php echo $h['name'];?></option>
				<?php endforeach;?>
			</select>
		在售<select name="is_sale">
				<option value=''>全部</option>
				<option value='1'>是</option>
				<option value='0'>否</option>
			</select>
		<input type="submit" name="submit" value="查询" class='button'/>
	</form>
</div>

<div class="list-div" style="text-align:center" id="listDiv">
<?php endif;?>
<table cellspacing="1" cellpadding="3">
	<tr>
		<th>商品编码</th>
		<th>商品名称</th>
		<th>分类</th>
		<th>品牌</th>
		<th>价格</th>
		<th>在售</th>
		<th>操作</th>
	</tr>
	<?php foreach($response->goods as $goods):?>
	<tr>
		<td align="center" onmouseover="show_img('<?php echo $goods['img_url'];?>',event,this)" onmouseout="remove_img(this)"><?php echo $goods['goods_sn'];?> </td>
		<td align="center"><?php echo $goods['goods_name'];?> </td>
		<td align="center"><?php echo $goods['cat_name'];?> </td>
		<td align="center"><?php echo $goods['brand_name'];?> </td>
		<td align="center"><?php echo $goods['market_price'];?> </td>
		<td align="center"><?php echo $goods['is_sale'] == 1? lib_functions::image('shop/yes.gif'):lib_functions::image('shop/no.gif');?> </td>
		<td align="center">
			<?php echo lib_functions::action('goods/edit/'.$goods['id'],'编辑');?>
			<?php echo lib_functions::action('goods/delete/'.$goods['id'],'删除',array('onclick'=>"return remove_goods({$goods['id']});"));?>
		
		</td>
	</tr>

	<?php endforeach;?>
	<tr>
		<td align='right' colspan='7'>
		<?php  include(DS.'/views/page/page.php');?>
		</td>
	</tr>
</table>
<?php if(empty($response->query)):?>
</div>

<script type='text/javascript'>
	function remove_goods(id) {
		if(!confirm('确认删除？')) {
			return false;
		}
		var url = '<?php echo lib_functions::url('goods/delete/');?>'+id;
		HTTP.post(url,{},function(data) {
			if(data.status==0) {
				alert(data.msg);
				window.location.reload();
			}else {
				alert(data.msg);
			}
		},'json');

		return false;
	}

	page.query = '<?php echo lib_functions::url('goods/getList')?>';
	var Utils = new Utils();

	function query() {
		
		var ele = document.forms['searchForm'].elements;
		page.filter = {};
		
		page.filter.goods_key = Utils.trim(ele['goods_key'].value);
		page.filter.brand_id = ele['brand_id'].value;
		page.filter.cat_id = ele['cat_id'].value;
		page.filter.is_sale = ele['is_sale'].value;
		page.filter.query = 1;
		
		page.reload();

		return false;
	}

	function show_img(img_url,event,obj) {
		if(img_url == '' ) {
			return;
		}
		var childs = obj.childNodes;
		for(var i=0;i<childs.length;i++) {
			if(childs[i].nodeName == 'DIV' && childs[i].className=='img') {
				obj.removeChild(childs[i]);
			}
		}

		event = window.event?window.event:event;
		var px = event.clientX;
		var py = event.clientY;
		var url = '<?php echo lib_functions::image_src();?>'+img_url;
		var img = document.createElement('img');
		var div = document.createElement('div');
		img.setAttribute('src',url);
		img.style.width=100;
		img.style.height=100;
		div.style.position = 'absolute';
		div.style.zIndex = 1;
		div.style.left = px;
		div.style.top = py;
		div.className = 'img';
		div.appendChild(img);
		obj.appendChild(div);
		return;
	}

	function remove_img(obj) {
		var childs = obj.childNodes;
		for(var i=0;i<childs.length;i++) {
			if(childs[i].nodeName == 'DIV' && childs[i].className=='img') {
				obj.removeChild(childs[i]);
			}
		}
	}
</script>

<?php endif;?>