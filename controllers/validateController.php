<?php 

	if (isset($_SESSION['user'])) {
		header('Location: '.URL_APP.'/panel');
	}

	$tpl = new EngineTpl('views/templates/validate.html');
		
	$explode_get = explode('/',$_GET['seccion']);

	$token = $explode_get[1];
	
	$tpl->assignVar('alert_max',$token);

	$tpl->printToScreen();
 ?>