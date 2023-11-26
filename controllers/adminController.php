<?php 
	$tpl = new EngineTpl('views/templates/admin.html');

	$tpl->assignVar('URL',URL_APP);

	$db = new mysqli(HOST,USER,PASS,DB,PORT);

	$ssql_users = "SELECT * FROM `appestacion__usuarios`";

	$response_users = $db->query($ssql_users);

	$response_users = $response_users->fetch_all(MYSQLI_ASSOC);

	$tpl->assignVar('users',count($response_users));

	// Contador de Clientes 

	$ssql_clients = "SELECT * FROM `appestacion__tracker_ip`";

	$response_tracker = $db->query($ssql_clients);

	$response_tracker = $response_tracker->fetch_all(MYSQLI_ASSOC);

	$tpl->assignVar('clients',count($response_tracker));

	$tpl->printToScreen();


 ?>