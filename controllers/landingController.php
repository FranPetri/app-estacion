<?php 
	
	// session_start();
	session_unset();
	session_destroy();
	
	$tpl = new EngineTpl('views/templates/landing.html');

	$tpl->printToScreen();


 ?>