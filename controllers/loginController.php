<?php
	// include 'model/userModel.php';

	if (isset($_SESSION['user'])) {
		header('Location: '.URL_APP.'/panel');
	}

	if (empty($_SESSION['station'])) {
		$_SESSION['station'] = 713630;
	}

	if (isset($_SESSION['preset_station'])) {
		header('Location: '.URL_APP.'/detalle/'.$_SESSION['station']);
	}

	$tpl = new EngineTpl('views/templates/login.html');

	$tpl->assignVar('station',$_SESSION['station']);

	$tpl->assignVar('URL',URL_APP);

	$tpl->printToScreen();

 ?>