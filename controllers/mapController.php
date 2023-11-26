<?php 
	if (isset($_SESSION['user']) && $_SESSION['user'] != 'admin-estacion') {
		header('Location: '.URL_APP.'/panel');
	}

	$tpl = new EngineTpl('views/templates/map.html');

	$tpl->assignVar('URL',URL_APP);
	
	$tpl->printToScreen();



 ?>