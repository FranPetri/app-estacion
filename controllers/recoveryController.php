<?php 

	if (isset($_SESSION['user'])) {
		header('Location: '.URL_APP.'/panel');
	}
	
	$tpl = new EngineTpl('views/templates/recovery.html');

	$tpl->printToScreen();

 ?>