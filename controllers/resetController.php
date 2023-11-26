<?php 

	if (isset($_SESSION['user'])) {
		header('Location: '.URL_APP.'/panel');
	}
	
	$explode_get = explode('/',$_GET['seccion']);

	$token = $explode_get[1];
	

	$tpl = new EngineTpl('views/templates/reset.html');

	$tpl->assignVar('alert_max',$token);
	$tpl->assignVar('token',$token);

	$tpl->printToScreen();

 ?>