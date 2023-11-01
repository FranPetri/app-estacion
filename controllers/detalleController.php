<?php 

	$tpl = new EngineTpl('views/templates/detalle.html');

	$get_url = explode('/',$_GET['seccion']);

	$chipid = $get_url[1]; 
	
	$tpl->assignVar('chipid',$chipid);

	$tpl->printToScreen();
?>
