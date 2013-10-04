<?php 
echo $head->script('My97DatePicker/WdatePicker.js');
?>

<div class="pageheader">
	<h1>
		<span class="action-span1">订单添加 </span>
		<div style="clear: both;"></div>
	</h1>
</div>
<div class="clear"></div>
<form name="theForm" method="post" action="<?php echo lib_functions::url('order/save');?>">
  
    <table cellspacing="0" cellpadding="0" width="100%" class="poptable">
      <tbody><tr>
        <th colspan="4"> 基本信息 </th>
      </tr>
            <tr>
        <td><div align="right"><strong>订单来源：</strong></div></td>
        <td align="left"> 
		   		     
          <select style="position: relative; left: -5px;" id="order_source" name="order_source">
				<option value="0">请选择</option>
                <?php foreach($response->shop_from as $data) :?>
				<option value="<?php echo $data['code'];?>"><?php echo $data['name'];?></option>    	
				<?php endforeach;?>
          </select>
		  
		  
		  <span id="shop_control">
		  	所属商店
		  	<select id="select_shop" name="select_shop">
				 <option value="0">请选择</option>
              </select><span name="shop_id" style="color: red;">*</span>
		  </span>
         
          </td>
        <td><div align="right"><strong>下单时间：</strong></div></td>
        <td align="left" valign="middle"><input type="text" readonly="true" value="<?php echo date('Y-m-d H:i',time());?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',readOnly:true})" size="18" name="add_time">
        </td>
      </tr>
      <tr>
        <td><div align="right"><strong>配送方式：</strong></div></td>
        <td align="left">
		<select style="position: relative; left: -5px;" id="shipping_id" name="shipping_id">
     		<?php foreach($response->ships as $ship): ?>					
            <option value="<?php echo $ship['id'];?>"><?php echo $ship['name'];?></option>
			<?php endforeach;?>		    
          </select>
        </td>
        <td>
		</td>
        <td align="left">
				
		</td>
      </tr>
      <tr>
        <td><div align="right"><strong>交易号：</strong></div></td>
        <td align="left"><input type="text" value="" size="20" name="deal_code" id="deal_code">
          </td>
        <td><div align="right"><strong>支付方式：</strong></div></td>
        <td align="left">
			<select id="pay_id" name="pay_id">
			 <?php foreach($response->payments as $pay): ?>				
			 <option value="<?php echo $pay['id'];?>" <?php if($pay['code'] == 'alipay'):?> selected <?php endif;?> ><?php echo $pay['name'];?></option>
			 <?php endforeach; ?>
           </select>
        </td>
      </tr>
      <tr>
        <td><div align="right"><strong>备注：</strong></div></td>
        <td align="left" colspan="3"><textarea rows="3" cols="80" name="bz"></textarea></td>
      </tr>
      <tr>
        <td><div align="right"><strong>购买人姓名：</strong></div></td>
        <td align="left"><input type="text" value="" size="20" name="buyer_name" id="buyer_name">
          </td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <th colspan="4">收货人信息 </th>
      </tr>
      <tr>
        <td><div align="right"><strong>收货人：</strong></div></td>
        <td align="left"><input type="text" value="" name="consignee" id="consignee" style="width: 90%;">
          <span style="color: red;">*</span>
        </td>
        <td><div align="right"><strong>电子邮件：</strong></div></td>
        <td align="left"><input type="text" value="" id="email" name="email" style="width: 90%;">
          
        </td>
      </tr>
      <tr>
       <td><div align="right"><strong>省市区：</strong></div></td>
       <td align="left">
         省份<select id="province" name="province">
				<option selected="selected" value="0">请选择</option>
				<?php foreach($response->provinces as $province): ?>
				<option value="<?php echo $province['id'];?>"><?php echo $province['name'];?></option>
				<?php endforeach;?>
			</select>
		市<select id="city" name="city">
			    <option selected="selected" value="0">请选择</option>
		  </select>
		地区<select  id="district" name="district">
			<option selected="selected" value="0">请选择</option>
			</select>
			<span style="color: red;">*</span>
          
        </td>
         <td><div align="right"><strong>详细地址：</strong></div></td>
        <td align="left">
        <input type="text" value="" name="address" id="address" style="width: 90%;"><span style="color: red;">*</span>
        </td>
      </tr>
      <tr>
        <td><div align="right"><strong>电话：</strong></div></td>
        <td align="left"><input type="text" value="" name="tel" id="tel" style="width: 90%;">
          
        </td>
        <td><div align="right"><strong>手机：</strong></div></td>
        <td align="left"><input type="text" value="" name="mobile" id="mobile" style="width: 90%;">
          
        </td>
      </tr>
       <tr>
        <td><div align="right"><strong>邮编：</strong></div></td>
        <td align="left"><input type="text" value="" name="zipcode" id="zipcode" style="width: 90%;">
          
        </td>
        <td>
        </td>
        <td></td>
      </tr>
      
    </tbody></table>
    <table width="100%">
      <tbody><tr>
        <td style="text-align: center;"><input type="submit"value="保存订单" id="btnSave_consignee">
          
        </td>
      </tr>
      <tr>
        <td></td>
      </tr>
    </tbody></table>
     
</form>
<div class="clear"></div>

<script type="text/javascript">

var lylx_obj = document.getElementById('order_source');
var Utils = new Utils();

//order_source绑定change事件
Utils.bindFunction(lylx_obj,'change',function(){
	var url = "<?php echo lib_functions::url('order/add');?>";
	var params = {'shop_class_code':lylx_obj.value};
	
	$.post(url,params,function(data) {
		var shop  = document.getElementById('select_shop');
		var option =null;
		var cont = null;
		shop.innerHTML = "";
		option = document.createElement('option');
		cont  = document.createTextNode('请选择');
		option.value = '';
		option.appendChild(cont);
		shop.appendChild(option);

		for(var i=0;i<data.length;i++) {
			option = document.createElement('option');
			cont  = document.createTextNode(data[i]['name']);
			option.appendChild(cont);
			option.value=data[i].id;
			shop.appendChild(option);
		}
	},'json');
});

var province = document.getElementById('province');
Utils.bindFunction(province,'change',function() {
	var url = "<?php echo lib_functions::url('order/add');?>";
	var params = {'province':province.value};
	$.post(url,params,function(data){
		var city  = document.getElementById('city');
		var option =null;
		var cont = null;
		city.innerHTML = '';
		option = document.createElement('option');
		cont  = document.createTextNode('请选择');
		option.appendChild(cont);
		option.value = '';
		city.appendChild(option);

		var district  = document.getElementById('district');
		district.innerHTML = '';
		option = document.createElement('option');
		cont  = document.createTextNode('请选择');
		option.appendChild(cont);
		option.value = '';
		district.appendChild(option);

		for(var i=0;i<data.length;i++) {
			option = document.createElement('option');
			cont  = document.createTextNode(data[i]['name']);
			option.appendChild(cont);
			option.value=data[i].id;
			city.appendChild(option);
		}
	},'json');
});

var city = document.getElementById('city');
Utils.bindFunction(city,'change',function() {
	var url = "<?php echo lib_functions::url('order/add');?>";
	var params = {'city':city.value};
	$.post(url,params,function(data){
		var district  = document.getElementById('district');
		var option =null;
		var cont = null;

		district.innerHTML = '';
		option = document.createElement('option');
		cont  = document.createTextNode('请选择');
		option.appendChild(cont);
		option.value = '';
		district.appendChild(option);

		for(var i=0;i<data.length;i++) {
			option = document.createElement('option');
			cont  = document.createTextNode(data[i]['name']);
			option.appendChild(cont);
			option.value=data[i].id;
			district.appendChild(option);
		}
	},'json');
});

/**
 * onsubmit
 *提单提交
 */
document.forms['theForm'].onsubmit = function() {
//	var tip_obj = document.getElementsByName('error_tip');
	var tip_obj = new Array();
	var obj = document.getElementsByTagName('span');
	for(var i=0;i<obj.length;i++) {
		if(obj[i].getAttribute('name') == 'error_tip') {
			tip_obj.push(obj[i]);
		}
	}

	for(var i=0;i<tip_obj.length;i++) {
	
		if(document.all) {  //ie
			tip_obj[i].removeNode(true);
		}else {  //firefox
			tip_obj[i].parentNode.removeChild(tip_obj[i]);
		}
	}

	return error_tip('select_shop')  &&error_tip('shipping_id')&& error_tip('pay_id')&&error_tip('deal_code')&& error_tip('buyer_name')&& error_tip('consignee')&& error_tip('district')&& error_tip('address')&& error_tip('mobile');
}

function error_tip(id) {
	var value = document.getElementById(id).value;
	var value = Utils.trim(value);

	if(!value || value == 0)  {
	
		var obj = document.getElementById(id);
		var node = document.createElement('span');
		var text = document.createTextNode('不能为空');
		node.style.color = 'red';
		node.setAttribute('name','error_tip');
		node.appendChild(text);
		obj.parentNode.appendChild(node);
			
		return false;
	}
	return true;
	
}
</script>
