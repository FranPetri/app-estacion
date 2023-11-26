<?php 

	class DbModel
	{
		public $db;
		public $last_query;
		
		private function connect(){
			$this->db = new mysqli(HOST, USER, PASS, DB, PORT);

			// var_dump($this->db);
		}


		public function query($query){

			$this->connect();

			$this->last_query = $query;

			$list = $this->db->query($query);


			if(strstr($query, 'SELECT')){
				return $list->fetch_all(MYSQLI_ASSOC);
			}

			// pasa por aca en caso de DELETE, UPDATE, INSERT
			return true;
		}
	}



 ?>