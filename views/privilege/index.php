<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>e-Admin 登录中心</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
	$head = new lib_head();
	echo $head->addCss('shop/general.css')->addCss('shop/main.css')->getCss();
	echo $head->addScript('utils.js')->addScript('jquery-1.6.js')->getScript();
?>
<style type="text/css">
body {
  color: white;
}
</style>
</head>

<body style="background: #278296">
<form method="post" action="<?php echo lib_functions::url('shop/index');?>" name='theForm' onsubmit="return validate();" >
  <table cellspacing="0" cellpadding="0" style="margin-top: 100px" align="center">
  <tr>
    <td>
		<?php echo lib_functions::image('shop/login.png',array('width'=>178,'height'=>'256','border'=>0,'alt'=>'ECSHOP'));?>
	</td>
    <td style="padding-left: 50px">
      <table>
	  <tr><td></td>
		  <td><span id="error_tip" style="color:red"></span></td>
	  </tr>
      <tr>
        <td>管理员姓名：</td>

        <td><input type="text" name="username" /></td>
      </tr>
      <tr>
        <td>管理员密码：</td>
        <td><input type="password" name="password" /></td>
      </tr>
            
      <tr><td colspan="2"><input type="checkbox" value="1" name="remember" id="remember" /><label for="remember">请保存我这次的登录信息。</label></td></tr>
      <tr><td>&nbsp;</td><td><input type="submit" value="进入管理中心" class="button" /></td></tr>

      <tr>
        <td colspan="2" align="right">&raquo; <a href="../" style="color:white">返回首页</a> &raquo; <a href="get_password.php?act=forget_pwd" style="color:white">您忘记了密码吗?</a></td>
      </tr>
      </table>
    </td>
  </tr>

  </table>
</form>
<script language="JavaScript">

<!--
 var Utils = new Utils();
  /**
   * 检查表单输入的内容
   */
  function validate()
  {
	  $ele = document.forms['theForm'].elements;
	  var username = Utils.trim($ele['username'].value);
	  var password = Utils.trim($ele['password'].value);

	  if(!username) {
		document.getElementById('error_tip').innerHTML = '请输入用户名';
		return false;
	  }
	  if(!password) {
		document.getElementById('error_tip').innerHTML = '请输入密码';
		return false;
	  }
	 
	  $.post("<?php echo lib_functions::url('privilege/login');?>",
			 {'username':username,'password':password},function(data) {
			   if(data.status == 0) {
				  // document.forms['theForm'].submit();
					window.location.href="<?php echo lib_functions::url('shop');?>";
			   }else {
					document.getElementById('error_tip').innerHTML = data.msg;
			   }

			},'json'
		);

		return false;
  }
  
//-->
</script>
</body>