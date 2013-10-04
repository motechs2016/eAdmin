/**
 * 各种常见的js方法组成的对象
 * @returns {Utils}
 * @author wq
 * @time 2011-12-08
 */
function Utils() {
	
	/**
	 * 文字上下滚动
	 * @param id string 元素Id
	 * @param fast Int 速度 时间 200 毫秒为单位
	 * @returns void
	 */
	this.scrollUp = function(id, fast) {
		var element = document.getElementById(id);
		var height = element.offsetHeight;
		setInterval(function() {
			element.scrollTop++;
			if (element.scrollTop == height) {
				element.scrollTop = 0;
			}
		}, fast);
	}
	
	
	/**
	 * 实现jquery的$.trim()功能 去掉字符串的前后空格
	 * @param str string 字符串        
	 * @returns string
	 */
	this.trim = function (str){
		return str.replace(/(^\s+)|(\s+$)/g,'');
	}
	
	/**
	 * 增加class属性
	 * @param id string 元素Id
	 * @param addClassName string 要增加的class名字
	 * @returns void
	 */
	this.addClass = function (id,addClassName) {
		var element = document.getElementById(id);
		if('className' in element){
			element.className += ' '+addClassName;
		}else {
			var str = element.getAttribute('class');
			
			if(str == undefined || str == null) {
				str = addClassName;
			}else {
				str += ' '+addClassName;
			}
			element.setAttribute('class',str);
		}
	
	}
	
	/**
	 * 判断是否含有class
	 * @param id string 元素Id
	 * @param name string className
	 * @returns void
	 */
	this.hasClass = function (id,name) {
		var element = document.getElementById(id);
		var str = element.className;
		eval("var pattern = /\\b"+name+"\\b/i");
		return pattern.test(str);		
	}
	
	/**
	 * 移除class属性
	 * @param id string 元素Id
	 * @param name string className
	 * @returns void
	 */
	this.removeClass = function (id,name) {
		var element = document.getElementById(id);
		var str = element.className;
		var regex = new RegExp("\\b"+name+"\\b",'ig');
		str = str.replace(regex,'');
		element.className = str;
	}
	
	/**
	 * 查找子元素 查找符合的一个，就停止查找
	 * @param element 将要查找的元素对象，在此元素查找子元素
	 * @param attributeName 属性名
	 * @param value 属性值
	 * @returns object element
	 * Utils.findByAttribute(ele,'value','1');  ele.value = 1
	 */
	this.findByAttribute = function (element,attributeName,value) {
		var children = element.childNodes;
		for(var i= 0;i<children.length;i++) {
			if(children[i].nodeType == 1) {
				if(children[i].getAttribute(attributeName) == value) {
					return children[i];
				}
			}
		}
	}

	/**
	 *查找子元素
	 *@param parentObj 父节点
	 *@param tagName 子节点tagName 例如input p div
	 */
	 this.findChildsByName = function (parentObj,tagName) {
		var children  = parentObj.childNodes;
		var data = new Array();
		for(var i=0;i<children.length;i++) {
			if(children[i].nodeName == tagName.toUpperCase()) {
				data.push(children[i]);
			}
		}
		return data;
	 }

	/**
	 *js阻止冒泡
	 *@event 事物 event规定，不能更改
	 *
     */
	 this.stopBubble = function (event) {
		if(window.event) {   //IE
			window.event.cancelBubble = true;
		}else{ //firefox
			event.stopPropagation();
		}
	 }

	 /**
	  *绑定函数
	  *@param obj要绑定函数的对象
	  *@param fun_type 绑定事件 click,change,select 等
	  *@param callback 回调函数
	  */
	  this.bindFunction = function (obj,fun_type,callback) {
			if(obj.addEventListener) {  //firefox
				obj.addEventListener(fun_type,callback,false);
			}else if(obj.attachEvent) {   //IE
				obj.attachEvent('on'+fun_type,callback);
			}
	  }

	/**
	 *获取兄弟节点
	 *@param obj object 
	 */
	 this.get_brothers = function (obj) {
		var parent_node = obj.parentNode;
		var children = parent_node.childNodes;
		var brothers = new Array();
		for(var i=0;i<children.length;i++) {
			if(children[i] != obj && children[i].nodeType == 1) {
				brothers.push(children[i]);
			}
		}
		return brothers;
	}

	/**
	 *判断父节点是否存在某个子节点
	 *
	 */
	 this.hasChildObj = function (parentObj,childObj) {
		var childs = parentObj.childNodes;
		for(var i=0;i<childs.length;i++) {
			if(childs[i] == childObj) {
				return true;
			}
		}
		
		return false;
	 }

	 /**
	  *全选/反选
	  *@param id <input type="checkbox" id="check_all" />
	  *@param name  <input type="checkbox" name="check_id" />
	  */
	  this.check_all = function (id,name) {
		var check_obj = document.getElementById(id);
		var check_ids = document.getElementsByName(name);
		for(var i=0;i<check_ids.length;i++) {
			check_ids[i].checked = check_obj.checked;
		}
	  }

	/**
	 *判断是正整数
	 *
	 *
	 */
	 this.is_int = function (value) {
		var value = this.trim(value);

		//判断是否是正整数
		var regex = /^\d+$/;
		return regex.test(value);
	 }

}