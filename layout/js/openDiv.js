/**
 *
 *弹出窗口，打开一个弹出窗口
 *jquery版本，在jquery下才能支持
 *添加一个div弹出层id为openDiv  <div id="openDiv">.....</div>
 *样式如下：<div id="openDiv"><div id='openDivMsg'>.....</div><p><a>关闭</a></p></div>
 */
function openDiv(url,params) {
	$.post(url,params,function(data) {
		var open_div = document.getElementById('openDiv');
		if(open_div) {
			document.getElementById('openDivMsg').innerHTML = data;
			open_div.style.display = 'block';
			return;
		}

		var div_content = document.createElement('div');
		div_content.id = 'openDiv';

		//title标题
		var header = document.createElement('p');
		header.innerHTML = "<a href='javascript::void(0)' onclick='close_open_div()' style='color:red'>关闭</a>";
		header.style.height= '30px';
		header.style.textAlign= 'right';
		header.style.marginTop= 0;
		header.style.marginBottom= 0;
		header.style.cursor="move"; 

		
		div_content.appendChild(header);

		var msg_obj = document.createElement('div');
		msg_obj.id = 'openDivMsg';
		msg_obj.innerHTML = data;
		div_content.appendChild(msg_obj);

		//样式
		div_content.style.border = "1px solid #336699";
		div_content.style.position = 'absolute';
		
		div_content.style.left = '120px';
		div_content.style.top = '120px';
		div_content.style.maxHeight = '80%';
		div_content.style.maxWight = '80%';
		
		div_content.setAttribute('align','center');
		div_content.style.zIndex = '10000';
		div_content.style.background = '#CCCCCC';
		div_content.style.border = '1';
		div_content.style.borderColor = 'white';

		msg_obj.style.background = 'white';
		msg_obj.style.marginLeft = '10px';
		msg_obj.style.marginRight = '10px';
		msg_obj.style.marginBottom = '10px';
		msg_obj.style.overflow = 'scroll';

		var drag = false;
		var px_old = div_content.style.left;
		var py_old = div_content.style.top;
		var px;
		var py;
	//	alert(px_old);
		header.onmousedown = function (event) {
			
			drag = true;
			if(window.event) {
				px = window.event.clientX;
				py = window.event.clientY;
			}else if(event) {
				//alert(event.clientX);
				px = event.clientX;
				py = event.clientY;
			}
			
		}
		header.onmouseup = function (event) {
			drag = false;
			px_old = div_content.style.left;
			py_old = div_content.style.top ;
		}
		header.onmouseout = function (event) {
			drag =false;
			px_old = div_content.style.left;
			py_old = div_content.style.top ;
		}

		header.onmousemove = function(event) {
			if(drag == false) {
				return;
			}
			
			if(window.event) {
				var px_1 = window.event.clientX;
				var py_2 = window.event.clientY;
			}else if(event) {
				//alert(event.clientX);
				var px_1 = event.clientX;
				var py_2 = event.clientY;
			}
		
			div_content.style.left = eval(parseInt(px_old)+parseInt(px_1)-parseInt(px))+'px';
			div_content.style.top = eval(parseInt(py_old)+parseInt(py_2)-parseInt(py))+'px';
			
		}
		
		document.getElementsByTagName('body')[0].appendChild(div_content);
		
	});

}

function close_open_div()  {
	document.getElementById('openDiv').style.display = 'none';
}
