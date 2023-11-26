<?php 
	
	include_once 'dbModel.php';

	class User extends DbModel
	{
		// Nombre y Contraseña de Usuario
		public $user;
		public $pass;

		// Token
		public $token;

		// Estado
		public $state = 'offline';
		
		// Data del usuario
		public $email;
		public $name;
		public $apel;
		
		// Validaciones
		public $active;
		public $block;
		public $recove;

		// si el usuario esta activo, $token_action es 0 si es bloqueado/recuperar es 1
		public $token_action;

		private $register = true;

		function __construct($email){
			$this->email = $email;

			// Busco si el usuario esta registrado
			$sql = "SELECT * FROM `appestacion__usuarios` WHERE `email`='$this->email';";

			$user_data = $this->query($sql);

			// var_dump($user_data);	
			
			if (count($user_data) > 0) {
				$this->register = false;
				$this->pass = $user_data[0]['contraseña'];

				$this->active = $user_data[0]['activo'];
				$this->block = $user_data[0]['bloqueado'];
				$this->recove = $user_data[0]['recupero'];
			}
		}

		function register($pass){
			if (!$this->register) {
				return array('errno' => 300, 'error' => 'El usuario ya esta registrado');
			}
			$pass_enc = md5($pass);
			$token_create = md5(uniqid().$this->email.$this->user);
			$token_action_create = md5(uniqid().rand(10,1000));

			$ssql = "INSERT INTO `appestacion__usuarios` (`email`,`contraseña`,`token`,`token_action`) 
			VALUES ('$this->email','$pass_enc','$token_create','$token_action_create')";

			$this->query($ssql);

			return array('errno' => 200, 'error' => 'Se ha agregado correctamente el usuario', 'token_action' => $token_action_create);
		}

		function login($pass){
			if (!$this->register) {
				if (md5($pass) == $this->pass) {
					if ($this->block == 1 || $this->recove == 1) {
						return array('errno' => 402, 'error' => 'El usuario esta bloqueado, revise su casilla de correo');
					}
					if ($this->active == 0) {
						return array('errno' => 403, 'error' => 'El usuario aun no se ha validado, revise su casilla de correo');
					}
					$this->state = 'online';
					return array('errno' => 200, 'error' => 'El usuario ha iniciado sesion');
				
				}else{
					return array('errno' => 401, 'error' => 'Credenciales Incorrectas','token' => $this->token);
				}
			}
			return array('errno' => 404, 'error' => 'Credenciales no validas');	
		}

		function validate($token){
			$ssql = "SELECT * FROM `appestacion__usuarios` WHERE `token_action` = '$token'";
			if ($this->query($ssql)) {
				var_dump($this->query($ssql));	
			}
			return array('errno' => 404, 'error' => 'EL Token no correspende a ningun usuario');
		}
	}

 ?> 