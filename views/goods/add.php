<div class="header_div">
	<span><h1>添加商品</h1>
	<input type="button" class="header_button" value="返回" onclick="window.history.go(-1);"/></span>
</div>

<div>
	<a href="javascript:void(0)"style="font-size:18px;background-color:#99BBE8" onclick="exchange_info('general-table')">基本信息</a>
	<a href="javascript:void(0)" style="font-size:18px;background-color:#99BBE8" onclick="exchange_info('price-table')">价格设置</a>
	<a href="javascript:void(0)" style="font-size:18px;background-color:#99BBE8" onclick="exchange_info('color-table')">颜色设置</a>
	<a href="javascript:void(0)" style="font-size:18px;background-color:#99BBE8" onclick="exchange_info('size-table')">尺码设置</a>
	
	<form action='<?php echo lib_functions::url('goods/save')?>' method="post" name="theForm">
		<table width="90%" id="general-table">
          <tr>
            <td class="label">
             商品货号： </td>
            <td><input type="text" size="20" value="" name="goods_sn">
            <span class="require-field">*</span></td>
          </tr>	    		  
          <tr>
            <td class="label">商品名称：</td>
            <td>
            <input type="text" size="30" style="float: left;" value="" name="goods_name">
			<span class="require-field">*</span
			</td>
          </tr>

           <tr>
            <td class="label">商品分类：</td>
            <td>
				<select name="cat_id">
				<option value="0">请选择</option>
				<?php foreach($response->categories as $cat): ?>
					<option value="<?php echo $cat['id'];?>"><?php echo $cat['name'];?></option>
				<? endforeach;?>
     			</select>
               	<span class="require-field">*</span>
            </td>
          </tr>
		  
          <tr>
            <td class="label">商品品牌：</td>
            <td>
				<select  name="brand_id">
				<option value="0">请选择</option>
				<?php foreach($response->brands as $brand): ?>
					<option value="<?php echo $brand['id'];?>"><?php echo $brand['name'];?></option>
				<? endforeach;?>
               	<span class="require-field">*</span>
            </td>
          </tr>
		  <tr>
            <td class="label">商店：</td>
            <td>
				<select  name="shop_id">
				<option value="0">请选择</option>
				<?php foreach($response->shops as $shop): ?>
					<option value="<?php echo $shop['id'];?>"><?php echo $shop['name'];?></option>
				<? endforeach;?>
               	
            </td>
          </tr>

          <tr>
            <td class="label">
            是否在售： 
            </td>
            <td>
            <input type="checkbox" name="is_sale" checked="checked" value="1">
            </td>
          </tr>
	
          <tr>
            <td class="label">商品重量：</td>
            <td><input type="text" size="20" value="0" name="goods_weight"> 
             <span class="require-field">克</span>             </td>
          </tr>
		  <tr>
            <td class="label">商品简单描述：</td>
            <td><textarea rows="3" cols="40" name="desc"></textarea></td>
          </tr>
		  
		</table>

		<!--  价格 -->
		<table width="90%" id="price-table" style="display:none">
          <tr>
            <td class="label">
             市场价： </td>
            <td><input type="text" size="20" value="" name="market_price">
            </td>
          </tr>	    		  
          <tr>
            <td class="label">成本价：</td>
            <td>
            <input type="text" size="20"  value="" name="cost_price">
			</td>
          </tr>

           <tr>
            <td class="label">商品售价：</td>
            <td>
				 <input type="text" size="20"  value="" name="shop_price">
            </td>
          </tr>
		</table>

		<!-- 颜色设置 -->
		<table cellspacing="1" cellpadding="0" border="0" align="center" width="96%" style="display: none; background-color:white" id="color-table">
		  <tr style="background-color: rgb(255, 255, 255);">
			<td style="padding-left: 5px; padding-bottom: 5px;" colspan="3">
			  颜色
			  <input type="text" name="color_keyword" id="color_keyword">
			  <input type="button" class="button" onclick="search_color()" value=" 搜索 ">            
			</td>
		  </tr>
		   <tr>
					<th align="center" style="font-weight: bold;">颜色</th>
					<th align="center" style="font-weight: bold;">操作</th>
					<th align="center" style="font-weight: bold;">选项</th>
		   </tr>
		  
		  <tr>
					<td align="left" width="45%">
						 <div style="width: 100%; height: 240px; border: 1px solid rgb(202, 215, 208); overflow: scroll;" id="source_color_list">             
						 </div>
					</td>
					<td align="center">
						<p>
						<input type="button" style="width: 60px;" onclick="add_colors_all()" value="&gt;&gt;">
						</p>
						<p><input type="button" style="width: 60px;" onclick="add_color_selected()" value="&nbsp;&gt;&nbsp;"></p>
						<p>
						<input type="button" style="width: 60px;" onclick="remove_color_selected()" value="&nbsp;&lt;&nbsp;">
						</p>
						<p><input type="button" style="width: 60px;" onclick="remove_colors_all()" value="&lt;&lt;"></p>
					</td>
					<td align="left" width="50%">
						<div style="width: 99%; height: 240px; border: 1px solid rgb(202, 215, 208); overflow: scroll;" id="target_color_list">
												
						</div>            
					</td>
			</tr>
					  
		</table>

		<!-- 尺码设置 -->
		<table cellspacing="1" cellpadding="0" border="0" align="center" width="96%" style="display: none; background-color:white" id="size-table">
		  <tr style="background-color: rgb(255, 255, 255);">
			<td style="padding-left: 5px; padding-bottom: 5px;" colspan="3">
			  尺码
			  <input type="text" name="size_keyword" id="size_keyword">
			  <input type="button" class="button" onclick="search_size()" value=" 搜索 ">            
			</td>
		  </tr>
		   <tr>
					<th align="center" style="font-weight: bold;">尺码</th>
					<th align="center" style="font-weight: bold;">操作</th>
					<th align="center" style="font-weight: bold;">选项</th>
		   </tr>
		  
		  <tr>
					<td align="left" width="45%">
						 <div style="width: 100%; height: 240px; border: 1px solid rgb(202, 215, 208); overflow: scroll;" id="source_size_list">             
						 </div>
					</td>
					<td align="center">
						<p>
						<input type="button" style="width: 60px;" onclick="add_sizes_all()" value="&gt;&gt;">
						</p>
						<p><input type="button" style="width: 60px;" onclick="add_size_selected()" value="&nbsp;&gt;&nbsp;"></p>
						<p>
						<input type="button" style="width: 60px;" onclick="remove_size_selected()" value="&nbsp;&lt;&nbsp;">
						</p>
						<p><input type="button" style="width: 60px;" onclick="remove_sizes_all()" value="&lt;&lt;"></p>
					</td>
					<td align="left" width="50%">
						<div style="width: 99%; height: 240px; border: 1px solid rgb(202, 215, 208); overflow: scroll;" id="target_size_list">
												
						</div>            
					</td>
			</tr>
					  
		</table>
	</form>
	
	<table width="90%">
		<tr>
			<td align="center">
				<input type="button" name="submit" onclick="form_submit()" value="提交">
				<input type="button" name="reset" onclick="form_reset()" value="重置">
			</td>
		  </tr>
	</table>
</div>


<script type="text/javascript">
var Utils  = new Utils();

function exchange_info(id) {

	var obj = document.getElementById(id);
	obj.style.display = '';
	var others = Utils.get_brothers(obj);
	for(var i=0;i<others.length;i++) {
		others[i].style.display = 'none';
	}
	
}

function form_submit() {
	if(!confirm('确认提交？')) {
		return;
	}
	var form = document.forms['theForm'].elements;
	var goods_sn = Utils.trim(form['goods_sn'].value);
	var goods_name = Utils.trim(form['goods_name'].value);
	var cat_id = form['cat_id'].value;
	var brand_id = form['brand_id'].value;
	var shop_id = form['shop_id'].value;
	var is_sale = form['is_sale'].value;
	var goods_weight = Utils.trim(form['goods_weight'].value);

	var desc = Utils.trim(form['desc'].value);
	var market_price = Utils.trim(form['market_price'].value);
	var cost_price = Utils.trim(form['cost_price'].value);
	var shop_price = Utils.trim(form['shop_price'].value);
	
	if(!goods_sn) {
		alert('商品货号不能为空');
		return;
	}
	if(!goods_name) {
		alert('商品名称不能为空');
		return;
	}
	if(!cat_id) {
		alert('请选择分类');
		return;
	}
	if(!brand_id) {
		alert('请选择品牌');
		return;
	}
	if(!shop_id) {
		alert('请选择店铺');
		return;
	}

	var colors_obj = Utils.findChildsByName(document.getElementById('target_color_list'),'input');
	var colors = new Array();
	for(var i=0;i<colors_obj.length;i++) {
		colors.push(colors_obj[i].value);
	}

	var sizes_obj = Utils.findChildsByName(document.getElementById('target_size_list'),'input');
	var sizes = new Array();
	for(var i=0;i<sizes_obj.length;i++) {
		sizes.push(sizes_obj[i].value);
	}
	var url = '<?php echo lib_functions::url('goods/save');?>';

	$.post(url,{'goods_sn':goods_sn,'goods_name':goods_name,'cat_id':cat_id,'brand_id':brand_id,'shop_id':shop_id,
				'is_sale':is_sale,'goods_weight':goods_weight,'desc':desc,'market_price':market_price,'cost_price':cost_price,
				'shop_price':shop_price,'colors':colors,'sizes':sizes},
		
		function(data){
			alert(data);
			window.location.href = '<?php echo lib_functions::url('goods/getList');?>';
		});
}

function form_reset() {
	var form = document.forms['theForm'];
	form.reset();
}

function search_color() {
	var key = document.getElementById('color_keyword').value;
	var url = "<?php echo lib_functions::url('goods/search_color')?>";
	$.post(url,{'color_keyword':key},function(data) {
		
		var color_list = document.getElementById('source_color_list');
		color_list.innerHTML = '';
		for(var i=0;i<data.length;i++) {
			var checkbox = document.createElement('input');
			checkbox.setAttribute('type','checkbox');
			checkbox.setAttribute('value',data[i].code);
			checkbox.setAttribute('name','colors');
			color_list.appendChild(checkbox);
			var text = document.createTextNode(data[i].name);
			color_list.appendChild(text);
			var br = document.createElement('br');
			color_list.appendChild(br);
		}

	},'json');
}

function search_size() {
	var key = document.getElementById('size_keyword').value;
	var url = "<?php echo lib_functions::url('goods/search_size')?>";
	$.post(url,{'size_keyword':key},function(data) {
		
		var size_list = document.getElementById('source_size_list');
		size_list.innerHTML = '';
		for(var i=0;i<data.length;i++) {
			var checkbox = document.createElement('input');
			checkbox.setAttribute('type','checkbox');
			checkbox.setAttribute('value',data[i].code);
			checkbox.setAttribute('name','sizes');
			size_list.appendChild(checkbox);
			var text = document.createTextNode(data[i].name);
			size_list.appendChild(text);
			var br = document.createElement('br');
			size_list.appendChild(br);
		}

	},'json');
}

/**
 *全部移过去
 */
function add_colors_all() {
	var color_list = document.getElementById('source_color_list').innerHTML;
	document.getElementById('target_color_list').innerHTML = color_list;
}
function add_sizes_all() {
	var size_list = document.getElementById('source_size_list').innerHTML;
	document.getElementById('target_size_list').innerHTML = size_list;
}

function remove_colors_all() {
	document.getElementById('target_color_list').innerHTML = '';
}
function remove_sizes_all() {
	document.getElementById('target_size_list').innerHTML = '';
}

function add_color_selected() {
	var nodes = document.getElementById('source_color_list').childNodes;
	var target = document.getElementById('target_color_list');

	for(var i=0;i<nodes.length;i++) {
		if(nodes[i].nodeType == 1 && nodes[i].nodeName=='INPUT') {
			if(nodes[i].checked) {
			//	alert(nodes[i].value);return;
				//判断是否已经存在
				var node = nodes[i].cloneNode(false);
				var childs = target.childNodes;
				var b =0;
				for(var t=0;t<childs.length;t++) {
					if(childs[t].nodeType==1 && childs[t].value == node.value) {
						b=1;
						break;
					}
				}
				if(b == 1) {
					continue;
				}
				target.appendChild(node);
				target.appendChild(nodes[i+1].cloneNode(false));
				target.appendChild(nodes[i+2].cloneNode(false));
			}
		}
	}
}

function add_size_selected() {
	var nodes = document.getElementById('source_size_list').childNodes;
	var target = document.getElementById('target_size_list');

	for(var i=0;i<nodes.length;i++) {
		if(nodes[i].nodeType == 1 && nodes[i].nodeName=='INPUT') {
			if(nodes[i].checked) {
			//	alert(nodes[i].value);return;
				//判断是否已经存在
				var node = nodes[i].cloneNode(false);
				var childs = target.childNodes;
				var b =0;
				for(var t=0;t<childs.length;t++) {
					if(childs[t].nodeType==1 && childs[t].value == node.value) {
						b=1;
						break;
					}
				}
				if(b == 1) {
					continue;
				}
				target.appendChild(node);
				target.appendChild(nodes[i+1].cloneNode(false));
				target.appendChild(nodes[i+2].cloneNode(false));
			}
		}
	}
}

function remove_color_selected() {
	var childs = document.getElementById('target_color_list').childNodes;

	for(var i=0;i<childs.length;i++) {
		if(childs.length <= 3 && childs[0].checked) {
			i =0;
		}
		if(childs[i].nodeType == 1 && childs[i].checked) {
		//	alert(childs[i+1].nodeType);
			document.getElementById('target_color_list').removeChild(childs[i+2]);
			document.getElementById('target_color_list').removeChild(childs[i+1]);
			document.getElementById('target_color_list').removeChild(childs[i]);
			
			childs = document.getElementById('target_color_list').childNodes;
			i=0;
			//continue;
		}
	}
}

function remove_size_selected() {
	var childs = document.getElementById('target_size_list').childNodes;

	for(var i=0;i<childs.length;i++) {
		if(childs.length <= 3 && childs[0].checked) {
			i =0;
		}
		if(childs[i].nodeType == 1 && childs[i].checked) {
		//	alert(childs[i+1].nodeType);
			document.getElementById('target_size_list').removeChild(childs[i+2]);
			document.getElementById('target_size_list').removeChild(childs[i+1]);
			document.getElementById('target_size_list').removeChild(childs[i]);
			
			childs = document.getElementById('target_size_list').childNodes;
			i=0;
			//continue;
		}
	}
}


</script>
