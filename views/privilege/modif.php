<?php 
	header("Content-type:text/html;charset=utf-8");
	$head = new lib_head();
	echo $head->addCss('shop/general.css')->addCss('shop/main.css')->getCss();
	echo $head->addScript('utils.js')->addScript('jquery-1.6.js')->getScript();
	$head->setTitle('ECSHOP 管理中心 - 编辑个人资料 ');
?>

<h1>

<span class="action-span1"><a href="index.php?act=main">ECSHOP 管理中心</a>  - 编辑个人资料 </span>
<div style="clear:both"></div>
</h1><div class="main-div">
<form name="theForm" method="post" action="<?php echo lib_functions::url('privilege/save');?>" enctype="multipart/form-data" onsubmit="return form_submit();">
<table width="100%">
  <tr>
    <td class="label">用户名</td>
    <td>

      <input type="text" name="user_name" maxlength="20" value="<?php echo $response->user_name;?>" size="34"/><span class="require-field">*</span></td>
  </tr>
  <tr>
    <td class="label">Email地址</td>
    <td>
      <input type="text" name="email" value="<?php echo $response->admin_user['email'];?>" size="34" /><span class="require-field">*</span></td>
  </tr>

     <tr>
    <td class="label">
      <a href="javascript:showNotice('passwordNotic');" title="点击此处查看提示信息">
	  <?php echo lib_functions::image('shop/notice.gif',array('width'=>16,'height'=>16,'border'=>0,'alt'=>'点击此处查看提示信息'));?>
        </a>旧密码</td>
    <td>
      <input type="password" name="old_password" size="34" /><span class="require-field">*</span>      <br /><span class="notice-span" style="display:block"  id="passwordNotic">如果要修改密码,请先填写旧密码,如留空,密码保持不变</span></td>
  </tr>

  <tr>
    <td class="label">新密码</td>
    <td>
      <input type="password" name="new_password" maxlength="32" size="34" /><span class="require-field">*</span></td>
  </tr>
  <tr>
    <td class="label">确认密码</td>

    <td>
      <input type="password" name="pwd_confirm" value="" size="34" /><span class="require-field">*</span></td>
  </tr>
        <tr>
  <td align="left" class="label">设置个人导航</td>
  <td>
      <table style="width:300px" cellspacing="0">
        <tr>

        <td valign="top">
          <input type="hidden" name="nav_list[]" id="nav_list[]" />
          <select name="menus_navlist" id="menus_navlist" multiple="true" style="width: 120px; height: 180px" onclick="setTimeout('toggleButtonSatus()', 1);">    
				<?php foreach($response->cote_action_list as $list):?>
				<option value="<?php echo $list['action_code'];?>"><?php echo $list['action_name'];?></option>
				<?php endforeach;?>
		  </select>
		</td>
        <td align="center">
			 <input type="button" class="button" value="上移" id="btnMoveUp" onclick="moveOptions('up')" disabled="true" />

			 <input type="button" class="button" value="下移" id="btnMoveDown" onclick="moveOptions('down')" disabled="true" />
			 <input type="button" value="增加" id="btnAdd" onclick="addItem()" class="button" disabled="true" /><br />
			 <input type="button" value="移除" onclick="delItem()" class="button" disabled="true" id="btnRemove" />
	   </td>
        <td> 
          <select id="all_menu_list" name="all_menu_list" size="15" multiple="true" style="width:150px; height: 180px" onchange="toggleAddButton()">
				<?php foreach($response->groups_action_list as $data) :?>
				<option value="<?php echo $data['action_code']?>" style="font-weight:bold"><?php echo $data['action_name'];?></option>
					<?php if(is_array($data['children']) && count($data['children']) > 0) :?>
						<?php foreach($data['children'] as $url) :?>
						<option value="<?php echo $url['action_code']?>">----<?php echo $url['action_name'];?></option>
						<?php endforeach;?>
					<?php endif;?>
				<?php endforeach;?>
		   </select>
		</td>
        </tr>
      </table></td>

  </tr>
    <tr>
    <td colspan="2" align="center">
      <input type="submit" value=" 确定 " class="button" />&nbsp;&nbsp;&nbsp;
      <input type="reset" value=" 重置 " class="button" />
      <input type="hidden" name="act" value="update_self" />
      <input type="hidden" name="id" value="1" /></td>
  </tr>
</table>

</form>
</div>

<script type="text/javascript">
var Utils = new Utils();


function toggleButtonSatus() {
	document.getElementById('btnMoveUp').disabled = false;
	document.getElementById('btnMoveDown').disabled = false;
	document.getElementById('btnRemove').disabled = false;
}

/**
 *移动导航条
 */
function moveOptions(way)  {
	var selected_options = getSelectedOptions('menus_navlist');
	var list = document.getElementById('menus_navlist');
	var index;
	
	
	switch(way){

		//上移
		case 'up':
			for(var i=0;i<selected_options.length;i++) {
				index = selected_options[i].index;
				var value = selected_options[i].value;
				var text = selected_options[i].text;
				if(index >=1 ) {
					list.options[index].value = list.options[index-1].value;
					list.options[index].text = list.options[index-1].text;
					list.options[index].selected = false;
					list.options[index-1].value = value;
					list.options[index-1].text = text;
					list.options[index-1].selected = true;
				}
		
			}
			break;
		case 'down':
			for(var i=0;i<selected_options.length;i++) {
				index = selected_options[i].index;
				var value = selected_options[i].value;
				var text = selected_options[i].text;
				
				if(index <= (list.options.length-2)) {
					list.options[index].value = list.options[index+1].value;
					list.options[index].text = list.options[index+1].text;
					list.options[index].selected = false;
					list.options[index+1].value = value;
					list.options[index+1].text = text;
					list.options[index+1].selected = true;
				}
		
			}
			break;

	}

}

function getSelectedOptions(id) {
	var all_options = document.getElementById(id).options;
	var selected_options = new Array();
	for(var i=0;i<all_options.length;i++) {
		if(all_options[i].selected) {
			selected_options.push(all_options[i]);
		}
	}
	return selected_options;
}

function toggleAddButton() {
	document.getElementById('btnAdd').disabled = false;
	document.getElementById('btnRemove').disabled = false;
}

function addItem() {
	var add_options = getSelectedOptions('all_menu_list');
	var nav_options = document.getElementById('menus_navlist').options;
//	alert(typeof nav_options);return;
	for(var i=0;i<add_options.length;i++) {
	//	nav_options.push(add_options[i]);
		nav_options[nav_options.length+i] = add_options[i];
		add_options[i].text = add_options[i].text.replace(/^-*/,'');
	}
}

function delItem() {
	var selected_options = getSelectedOptions('menus_navlist');
	var list = document.getElementById('menus_navlist');
	for(var i=0;i<selected_options.length;i++) {
		list.remove(selected_options[i].index);
	}
}

/**
 *表单提交
 */
function form_submit() {
	
	var ele = document.forms['theForm'].elements;
	var user_name = Utils.trim(ele['user_name'].value);
	var pass = Utils.trim(ele['new_password'].value);
	var email = Utils.trim(ele['email'].value);
	var old_pass = Utils.trim(ele['old_password'].value);
	var pwd_confirm = Utils.trim(ele['pwd_confirm'].value);
	var menus_navlist = ele['menus_navlist'].options;
	
	var cote = new Array();
	for(var i=0;i<menus_navlist.length;i++) {
		var title = menus_navlist[i].text.replace(/^-*/,'');
		cote[i] = {'action_name':title,'action_code':menus_navlist[i].value};	
	}
	if(!pass) {
		alert('密码不为空');
		return false;
	}
	if(pass != pwd_confirm ) {
		alert('密码与确认密码不一致');
		return false;
	}
	var url = "<?php echo lib_functions::url('privilege/save');?>";
	$.post(url,
	{'user_name':user_name,
	 'old_pass':old_pass,
	 'pass':pass,
	 'email':email,
	 'cote':cote
	},function(data) {
		alert(data.msg);
	},'json');

	return false;
	 
}
</script>