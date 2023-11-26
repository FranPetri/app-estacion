<?php 
	session_unset();
	session_destroy();

	$tpl = new EngineTpl('views/templates/blocked.html');
		
	$explode_get = explode('/',$_GET['seccion']);

	$token = $explode_get[1];
	
	$tpl->assignVar('alert_max',$token);

	$tpl->printToScreen();
 ?>