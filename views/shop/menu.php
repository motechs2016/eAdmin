<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php echo lib_head::css('shop/general.css');?>

<style type="text/css">
body {
  background: #80BDCB;
}
#tabbar-div {
  background: #278296;
  padding-left: 10px;
  height: 21px;
  padding-top: 0px;
}
#tabbar-div p {
  margin: 1px 0 0 0;
}
.tab-front {
  background: #80BDCB;
  line-height: 20px;
  font-weight: bold;
  padding: 4px 15px 4px 18px;
  border-right: 2px solid #335b64;
  cursor: hand;
  cursor: pointer;
}
.tab-back {
  color: #F4FAFB;
  line-height: 20px;
  padding: 4px 15px 4px 18px;
  cursor: hand;
  cursor: pointer;
}
.tab-hover {
  color: #F4FAFB;
  line-height: 20px;
  padding: 4px 15px 4px 18px;
  cursor: hand;
  cursor: pointer;
  background: #2F9DB5;
}
#top-div
{
  padding: 3px 0 2px;
  background: #BBDDE5;
  margin: 5px;
  text-align: center;
}
#main-div {
  border: 1px solid #345C65;
  padding: 5px;
  margin: 5px;
  background: #FFF;
}
#menu-list {
  padding: 0;
  margin: 0;
}
#menu-list ul {
  padding: 0;
  margin: 0;
  list-style-type: none;
  color: #335B64;
}
#menu-list li {
  padding-left: 16px;
  line-height: 16px;
  cursor: hand;
  cursor: pointer;
}
#main-div a:visited, #menu-list a:link, #menu-list a:hover {
  color: #335B64
  text-decoration: none;
}
#menu-list a:active {
  color: #EB8A3D;
}
.explode {
  background: url(<?php echo lib_functions::image_src('shop/menu_minus.gif');?>) no-repeat 0px 3px;
  font-weight: bold;
}
.collapse {
  background: url(<?php echo lib_functions::image_src('shop/menu_plus.gif');?>) no-repeat 0px 3px;
  font-weight: bold;
}
.menu-item {
  background: url(<?php echo lib_functions::image_src('shop/menu_arrow.gif');?>) no-repeat 0px 3px;
  font-weight: normal;
}
#help-title {
  font-size: 14px;
  color: #000080;
  margin: 5px 0;
  padding: 0px;
}
#help-content {
  margin: 0;
  padding: 0;
}
.tips {
  color: #CC0000;
}
.link {
  color: #000099;
}
</style>

</head>
<body>
<div id="tabbar-div">
<p>
	<span style="float:right; padding: 3px 5px;" >
		<a href="javascript:toggleCollapse();">
		<?php echo lib_functions::image('shop/menu_plus.gif',array('id'=>'toggleImg','width'=>'9','height'=>'9','border'=>0));?>
		</a>
	</span>
  <span class="tab-front" id="menu-tab">菜单</span><span class="tab-back" id="help-tab">帮助</span>
</p>
</div>
<div id="main-div">
<div id="menu-list">
<ul>
<?php foreach($response->action_list as $list):?>
  <li class="collapse" onclick="change_class(this)"><?php echo $list['action_name'];?>

    <ul style="display:none">
    <?php foreach($list['child_menu'] as $childs):?>
      <li class="menu-item">
	  <?php echo lib_functions::action($childs['action_code'],$childs['action_name'],array('target'=>'main-frame',
			'onclick'=>'delay_li_click(event)'
		));?>
	  
	  </li>
	<?php endforeach;?>
    </ul>

  </li>

<?php endforeach;?>
 
</ul>
</div>
<div id="help-div" style="display:none">
<h1 id="help-title"></h1>
<div id="help-content"></div>
</div>
</div>

<script language="JavaScript">
function change_class(obj){
	if(obj.className=='collapse') {
		obj.className='explode';
		getChildByTag(obj,'ul').style.display = 'block';
	}else if(obj.className=='explode') {
		obj.className='collapse';
		getChildByTag(obj,'ul').style.display = 'none';
	}

}

/**
 *获取子节点
 *@param obj 父元素
 *@param tag 子元素tagName
 *@return 子元素对象
 */
 function getChildByTag(obj,tag){
	var children = obj.childNodes;
	
	for(var i=0;i<children.length;i++) {
		if(children[i].nodeType == 1) {
			if(children[i].nodeName == tag.toUpperCase()) {
				return children[i];
			}
		}
	}
 }


var toggle_img_id = 0;

 function toggleCollapse(){
	 var plus = "<?php echo lib_functions::image_src('shop/menu_plus.gif');?>";
	 var minus = "<?php echo lib_functions::image_src('shop/menu_minus.gif');?>";
	 toggle_img_id++;
	 if(toggle_img_id >1) { toggle_img_id = 0;}
	 var src = (toggle_img_id == 0?plus:minus);
	
	document.getElementById('toggleImg').setAttribute('src',src);

	var menu_list = document.getElementById('menu-list');
	var lis = menu_list.getElementsByTagName('li');
	var class_name = (toggle_img_id == 0?'collapse':'explode');
	var display_style = (toggle_img_id == 0? 'none':'block');
	
	for(var i=0;i<lis.length;i++){
		if(lis[i].className != 'menu-item') {
			lis[i].className = class_name;
			getChildByTag(lis[i],'ul').style.display = display_style;
		}
	}
 }

 /**
   *js阻止冒泡
   *@param obj,
   *@event 事物
   */
 function delay_li_click(event) {
	if(window.event) {
		window.event.cancelBubble = true;
	}else{
		event.stopPropagation();
	}
 }

</script>


</body>
