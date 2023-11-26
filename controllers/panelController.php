<?php 

	$tpl = new EngineTpl('views/templates/panel.html');
	// CREAR UN FORMULARIO CON JS PARA QUE REDIRIJA A LOGIN Y GUARDAR EN UN SESSION EL NUMERO DE CHIP
	
	if (isset($_SESSION['station'])) {
		$_SESSION['preset_station'] = $_SESSION['station'];	
	}

	if (isset($_POST['btnStation'])) {
		$_SESSION['station'] = $_POST['station'];
		header('Location: login');	
	}

	$ip_client = $_SERVER['REMOTE_ADDR'];

	$tpl->assignVar('ip_client',$ip_client);

	$tpl->printToScreen();

 ?>