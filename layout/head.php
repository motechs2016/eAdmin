<head>
<?php 
$head = new lib_head();

echo $head->addCss('font.css')
		  ->addCss('bgcolor.css')
		  ->getCss();
echo $head
		  # ->addScript('jquery-1.6.js')
		  # ->addScript('plugins/galleria/galleria-1.2.5.min.js')
		   ->addScript('utils.js')
		   ->addScript('ajax.js')
		   ->addScript('openDiv.js')
		   ->getScript();
?>
</head>
