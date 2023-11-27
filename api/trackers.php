<?php 
    include '../model/db/credentials.php';
    include '../model/userModel.php';
    
    session_start();

    header('Content-Type: application/json');


    $db = new mysqli(HOST,USER,PASS,DB,PORT);

    if ($_GET['modo'] == 'chargeTracker') {

        $ip = $_GET['ip'];

        $web = file_get_contents("http://ipwho.is/".$ip);

        $response = json_decode($web);    
        $latitud = $response->latitude;
        $longitud = $response->longitude;
        $pais = $response->country;

        $ssql_client = "SELECT * FROM `appestacion__tracker_ip` WHERE `ip` = '$ip'";

        $response_clients = $db->query($ssql_client);

        if ($response_clients->num_rows == 0) {
            $rand_id = rand(10,1000);
            $token = md5(uniqid().$ip);
            $ssql_client = "INSERT INTO `appestacion__tracker_ip`(`token`,`ip`, `idTracker`, `latitud`, `longitud`, `pais`) VALUES ('$token','$ip','$rand_id','$latitud','$longitud','$pais')";
            $response_clients = $db->query($ssql_client);
            echo json_encode(array('errno' => 200, 'error' => 'Cliente Ingresado'));
            return true;
        }else{
            $ssql_client = "SELECT `accesos` FROM `appestacion__tracker_ip` WHERE `ip` = '$ip'";
            
            $response_clients = $db->query($ssql_client);
            $response_clients = $response_clients->fetch_all(MYSQLI_ASSOC);
            $accesos = intval($response_clients[0]['accesos']);
            $accesos++;
            
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
            
            if ($so[1] == ' Ubuntu') {
                $so = 'Ubuntu';
            }
            if ($so[1] == ' Win64') {
                $so = $so[0];
            }

            $ssql_client = "SELECT `idTracker` FROM `appestacion__tracker_ip` WHERE `ip` = '$ip'";

            $response_clients = $db->query($ssql_client);
            $response_clients = $response_clients->fetch_all(MYSQLI_ASSOC);

            $id_tracker = $response_clients[0]['idTracker'];

            $ssql_client = "INSERT INTO `appestacion__tracker`(`idTracker`, `navegador`, `sistema`) VALUES ('$id_tracker','$client','$so')";
            $response_clients = $db->query($ssql_client);

            $ssql_client = "UPDATE `appestacion__tracker_ip` SET `accesos`='$accesos' WHERE `ip` = '$ip'";
            $response_clients = $db->query($ssql_client);
            echo json_encode(array('errno' => 201, 'error' => 'Registro exitoso'));
            return true;
        }        
        echo json_encode(array('errno' => 404, 'error' => 'Fatal error'));
    }  

    if ($_GET['modo'] == 'chargeMap') {
        
        $ssql_map = "SELECT `latitud`,`longitud`,`accesos` FROM `appestacion__tracker_ip`";
        
        $response = $db->query($ssql_map);
        $response = $response->fetch_all(MYSQLI_ASSOC);

        // echo json_encode($response);
    }

 ?>