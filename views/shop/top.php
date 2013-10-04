
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="styles/general.css" rel="stylesheet" type="text/css" />
<?php echo lib_head::css('shop/general.css');?>
<style type="text/css">
#header-div {
  background: #278296;
  border-bottom: 1px solid #FFF;
}

#logo-div {
  height: 50px;
  float: left;
}

#submenu-div {
  height: 50px;
}

#submenu-div ul {
  margin: 0;
  padding: 0;
  list-style-type: none;
}

#submenu-div li {
  float: right;
  padding: 0 10px;
  margin: 3px 0;
  border-left: 1px solid #FFF;
}

#submenu-div a:visited, #submenu-div a:link {
  color: #FFF;
  text-decoration: none;
}

#submenu-div a:hover {
  color: #F5C29A;
}

#loading-div {
  clear: right;
  text-align: right;
  display: block;
}

#menu-div {
  background: #80BDCB;
  font-weight: bold;
  height: 24px;
  line-height:24px;
  width:100%;
}

#menu-div ul {
  margin: 0;
  padding: 0;
  list-style-type: none;
}

#menu-div li {
  float: left;
  border-right: 1px solid #192E32;
  border-left:1px solid #BBDDE5;
}

#menu-div a:visited, #menu-div a:link {
  display:block;
  padding: 0 20px;
  text-decoration: none;
  color: #335B64;
  background:#9CCBD6;
}

#menu-div a:hover {
  color: #000;
  background:#80BDCB;
}

#submenu-div a.fix-submenu{ clear:both; margin-left:5px; padding:1px 5px; *padding:3px 5px 5px; background:#DDEEF2; color:#278296; }
#submenu-div a.fix-submenu:hover{ padding:1px 5px; *padding:3px 5px 5px; background:#FFF; color:#278296; }
#menu-div li.fix-spacel{ width:30px; border-left:none; }
#menu-div li.fix-spacer{ border-right:none; }
</style>

<body>
<div id="header-div">
  <div id="logo-div">
  <?php echo lib_functions::image('shop/ecshop_logo.gif',array('alt'=>'ECSHOP - power for e-commerce'));?>
  </div>
  <div id="submenu-div">
	<div id="send_info" style="padding: 5px 10px 0 0; clear:right;text-align: right; color: #FF9900;width:40%;float: right;">
         
	  <?php echo lib_functions::action('privilege/logout','退出',array('class'=>'fix-submenu','target'=>'_top'));?>

    </div>
  </div>
</div>
<div id="menu-div">
  <ul>
    <li class="fix-spacel">&nbsp;</li>
    
	<?php foreach($response->action_list as $list): ?>
    <li>
		
		<?php echo lib_functions::action($list['action_code'],$list['action_name'],array('target'=>'main-frame')); ?>
	
	</li>
    <?php endforeach; ?>
    <li class="fix-spacer">&nbsp;</li>
  </ul>
  <br class="clear" />
</div>
</body>
</html>