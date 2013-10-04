<?php 
	header("Content-type:text/html;charset=utf-8");
	$head = new lib_head();
	echo $head->addCss('shop/general.css')->addCss('shop/main.css')->addCss('header_div.css')->getCss();

	echo $head->addScript('utils.js')
			   ->addScript('jquery-1.6.js')
			   ->addScript('openDiv.js')
			   ->addScript('form.js')
			   ->addScript('ajax.js')
			   ->addScript('page.js')
			   ->getScript();
	$head->setTitle('ECSHOP 管理中心');
?>
<?php //lib_functions::require_layout('head.php');?>

