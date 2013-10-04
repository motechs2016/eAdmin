/**
 *表单验证
 *要求 input 字段class=required 表明必须不能为空
 *@return boolean
 */

function form_submit(form_name) {
	var ele= document.forms[form_name].elements;
	var b = false;
	for(var i=0;i<ele.length;i++) {
		if(ele[i].className == 'required') {
			var val  = ele[i].value;
			val = val.replace(/^(\s+)|(\s+$)/g,'');
	
			if(false == val) {

				var span_node = document.createElement('span');
				var text_node = document.createTextNode('不能为空');
				span_node.appendChild(text_node);
				span_node.style.color = 'red';
				span_node.className = 'tip_error_form';
				var childs = ele[i].parentNode.childNodes;
				
				var exist_tip = false;
				for(var t=0;t<childs.length;t++) {
					if(childs[t].className=='tip_error_form') {
						//childs.removeChild(childs[t]);	
						exist_tip = true;
					}
				}
				if(!exist_tip) {
					ele[i].parentNode.appendChild(span_node);
				}
				b = true;
			}else {
				var childs = ele[i].parentNode.childNodes;
				for(var t=0;t<childs.length;t++) {
					if(childs[t].className=='tip_error_form') {
						ele[i].parentNode.removeChild(childs[t]);	
						
					}
				}
			}
		}
	}
	if(b) {
		return false;
	}else {
		return true;
	}
}

