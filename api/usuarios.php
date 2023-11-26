<?php 
	
	include '../model/db/credentials.php';
	include '../model/userModel.php';
	
	session_start();

	header('Content-Type: application/json');

	$db = new mysqli(HOST,USER,PASS,DB,PORT);

	// var_dump($user);

	if ($_GET['type'] == 'login') {
		$user = new User($_GET['email']);

		$login = $user->login($_GET['pass']);

		$response = false;

		if ($login['errno'] == 200) {
			$_SESSION['user'] = $user->email;
			$ssql = "SELECT * FROM `appestacion__usuarios` WHERE `email` = '".$_GET['email']."' AND `contraseña` = '".md5($_GET['pass'])."'";		
			$response = $db->query($ssql);
		}

		if ($login['errno'] == 401) {
			$ssql = "SELECT `token` FROM `appestacion__usuarios` WHERE `email` = '".$_GET['email']."'";		
			$response = $db->query($ssql);				
		}

		if($response && $response->num_rows>0){
			$list = $response->fetch_all(MYSQLI_ASSOC);
			
			if (isset($_SERVER['HTTP_SEC_CH_UA'])) {
				$client = explode('"', $_SERVER['HTTP_SEC_CH_UA']);
				$client = $client[1];
			}else{
				$client = explode('/', $_SERVER['HTTP_USER_AGENT']);
				$client = explode(' ', $client[2]);
				$client = $client[1];
			}

			$so = explode('(', $_SERVER['HTTP_USER_AGENT']);
			$so = explode(';', $so[1]);

			$list['errno'] = $login['errno'];
			array_push($list[0],$client);
			array_push($list[0],$_SERVER['REMOTE_ADDR']);
			array_push($list[0],$so[0]);
		}else{
			if ($login['errno'] == 402 || $login['errno'] == 403 || $login['errno'] == 404) {
				$list = $login;
			}
		}
	}

	if ($_GET['type'] == 'register') {
		$user = new User($_GET['email']);
		
		$register = $user->register($_GET['pass']);

		// var_dump($register);
		$list = false;

		if (isset($_SERVER['HTTP_SEC_CH_UA'])) {
			$client = explode('"', $_SERVER['HTTP_SEC_CH_UA']);
			$client = $client[1];
		}else{
			$client = explode('/', $_SERVER['HTTP_USER_AGENT']);
			$client = explode(' ', $client[2]);
			$client = $client[1];
		}

		$so = explode('(', $_SERVER['HTTP_USER_AGENT']);
		$so = explode(';', $so[1]);

		$list['errno'] = $register['errno'];
		$list['token_action'] = $register['token_action'];
		array_push($list,$client);
		array_push($list,$_SERVER['REMOTE_ADDR']);
		array_push($list,$so[0]);


		if ($register['errno'] == 300) {
			$list = $register;	
		}
	}
	
	if ($_GET['type'] == 'validate') {
		$token = $_GET['token'];
		
		$ssql = "SELECT * FROM `appestacion__usuarios` WHERE `token_action` = '$token'";
		
		$response = $db->query($ssql);

		$list = $response->fetch_all(MYSQLI_ASSOC);

		$date = date('Y-m-d');

		if (count($list)>0) {
			$ssql = "UPDATE `appestacion__usuarios` SET `activo`= 1,`token_action`='',`active_date`='$date' WHERE `token_action` = '$token'";
			$db->query($ssql);
		}else{
			$list = array('errno' => 404, 'error' => 'El token no corresponde a un usuario');
		}
	}

	if ($_GET['type'] == 'blocked') {
		$token = $_GET['token'];
		
		$ssql = "SELECT * FROM `appestacion__usuarios` WHERE `token` = '$token'";
		
		$response = $db->query($ssql);

		$list = $response->fetch_all(MYSQLI_ASSOC);

		$date = date('Y-m-d');

		$token_action = md5(uniqid().$date.$list[0]['id']);

		if (count($list)>0) {
			$ssql = "UPDATE `appestacion__usuarios` SET `bloqueado`= 1,`token_action`='$token_action',`blocked_date`='$date' WHERE `token` = '$token'";
			$db->query($ssql);
			$list['token_action'] = $token_action;
		}else{
			$list = array('errno' => 404, 'error' => 'El token no corresponde a un usuario');
		}
	}

	if ($_GET['type'] == 'recover') {
		$email = $_GET['email'];
		$ssql = "SELECT * FROM `appestacion__usuarios` WHERE `email` = '$email'";
		
		$response = $db->query($ssql);

		$list = $response->fetch_all(MYSQLI_ASSOC);

		$date = date('Y-m-d');

		if (count($list)>0) {
			$token_action = md5(uniqid().$date.$list[0]['id']);
			$ssql = "UPDATE `appestacion__usuarios` SET `recupero`= 1,`token_action`='$token_action',`recover_date`='$date' WHERE `email` = '$email'";
			$db->query($ssql);
			$list['token_action'] = $token_action;
		}else{
			$list = array('errno' => 404, 'error' => 'El email no se encuentra registrado');
		}
	}

	if ($_GET['type'] == 'reset') {
		$token = $_GET['token'];
	
		$ssql = "SELECT `token`,`email` FROM `appestacion__usuarios` WHERE `token_action` = '$token'";

		$response = $db->query($ssql);

		$list = $response->fetch_all(MYSQLI_ASSOC);

		if (count($list)>0) {
			$token_user = $list[0]['token'];
			$pass = md5($_GET['pass']);
			
			$ssql = "UPDATE `appestacion__usuarios` SET `contraseña` = '$pass',`bloqueado`=0,`token_action`='' WHERE `token` = '$token_user'";
			$response = $db->query($ssql);

			if (isset($_SERVER['HTTP_SEC_CH_UA'])) {
				$client = explode('"', $_SERVER['HTTP_SEC_CH_UA']);
				$client = $client[1];
			}else{
				$client = explode('/', $_SERVER['HTTP_USER_AGENT']);
				$client = explode(' ', $client[2]);
				$client = $client[1];
			}

			$so = explode('(', $_SERVER['HTTP_USER_AGENT']);
			$so = explode(';', $so[1]);

			$list['errno'] = 200;
			array_push($list[0],$client);
			array_push($list[0],$_SERVER['REMOTE_ADDR']);
			array_push($list[0],$so[0]);
		}
	}

	if ($_GET['type'] == 'reset-test') {
		$token = $_GET['token'];

		$ssql = "SELECT * FROM `appestacion__usuarios` WHERE `token_action` = '$token'";

		$response = $db->query($ssql);

		$list = $response->fetch_all(MYSQLI_ASSOC);

		if (count($list)>0) {
			$list = array('errno' => 200, 'error' => 'token correcto');
		}else{
			$list = array('errno' => 404, 'error' => 'El token no le pertence a ningun usuario');
		}
	}

	echo json_encode($list);
 ?>