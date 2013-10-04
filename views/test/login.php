<?php lib_head::setTitle('Login')?>
<div class="login">
	<form action="login" method="post">
		<table>
			<tr><td>用户名:<td><td><input type="text" name="username" /></td></tr>
			<tr><td>密码:<td><td><input type="password" name="password" /></td></tr>
			<tr><td><td><td><input type="submit" name="submit" value="Login" /></td></tr>
		</table>
		<input type="button" value="good" name="good"/>
	</form>
</div>
<?php //lib_functions::format_pre($_SERVER);?>
<div id="div_1" style="overflow: hidden;width:40px;height:40px;">
 	子窗口返回假子窗口返回假子窗口返回假 <p title='d' name="p">abc<span name="sp">abd</span></p>
</div>
<div id="div_2" style="opacity:0%;-moz-opacity:.75;filter:alpha(opacity=75)" class="abc">
负责本项调查的ECA International亚洲区域总监关礼廉当日在记者会上表示，<br/>
随着受到美元作为单一结算货币持续疲弱影响，香港已跌出生活费用最高昂城市的名单。<br/>
香港的全球排名已跌至第56位，较去年9月的调查下跌了26位，为亚洲区下跌幅度最大的城市。<br/>
若以亚洲区而言，尽管货品的售价已较去年同期上升更多，香港的排名依然由第6位下降至第9位。
</div>

<input type="button" value="good" name="good" onclick="HTTP.post('test.php',{'a':1,'b':2},response);"/>
<script type="text/javascript">
var Utils = new Utils();
Utils.scrollUp('div_1',200);
//alert(Utils.trim('abc   ').length);
Utils.addClass('div_2','red_class');
//alert(Utils.hasClass('div_2',"abc"));
Utils.removeClass('div_2','abc');
document.getElementsByName('good')[0].addEventListener('click', function() {alert('db')},false);
document.getElementsByName('password')[0].focus();
//document.forms[0].addEventListener('submit',function() {alert('error');return false;},false);

function get_http(url) {
	var request = HTTP.newRequest();
	request.open("GET",url,false);
	request.send(null);

	if(request.status == 200){
		alert(request.responseText);
	}else {
		alert("error "+request.status+": "+request.statusText);
	}
}

function response(data) {
	alert(data);
}

var p_ele = Utils.findByAttribute(document.getElementById('div_1'),'name','p');
alert(p_ele.innerHTML);
</script>