<?php 
	session_start();
	
	require 'env.php';
	include 'lib/enginetpl.php';
	include 'model/db/credentials.php';
	// require 'model/dbModel.php';
	
	$_ROUTE = explode("/", $_GET["seccion"]);

	// var_dump($_ROUTE);

	if($_ROUTE[0]!=""){
		$section = $_ROUTE[0];
		if(!file_exists("controllers/{$section}Controller.php")){
			$section = "error404";
		}
	}else{
		$section = "landing";
	}

	// if(isset($_SESSION[APP_NAME]["user_name"])){
	// 	if($section=="login" || $section=="register" || $section=="landing"){
	// 		$section="panel";
	// 	}
	// }else{
	// 	if($section=="panel"){
	// 		$section="landing";
	// 	}
	// }
	

	include "controllers/{$section}Controller.php";

 ?>