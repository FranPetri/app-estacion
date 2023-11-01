<?php 
	
	class EngineTpl
	{
		
		public $url_tpl;
		public $tpl;

		function __construct($url_tpl)
		{

			$this->url_tpl = $url_tpl;

			$this->tpl = file_get_contents($url_tpl);

			if($this->testVar("URL_APP"))
				$this->assignVar("URL_APP", URL_APP);

			if($this->testVar("CACHE"))
				if(CACHE==MODO_DEBUG)
					$this->assignVar("CACHE", "?rand=".date("YmdHis"));
				else
					$this->assignVar("CACHE", "");
		}


		private function testVar($var_tpl){
			return strpos($this->tpl, "{{{$var_tpl}}}");		
		}


		public function assignVar($var_tpl, $value){

			if(!$this->testVar($var_tpl)){
				echo "<b>error tpl:</b> No se encontro la variable <u>$var_tpl</u>";
			}

			$this->tpl=str_replace("{{{$var_tpl}}}", $value, $this->tpl);
		}


		public function printToScreen(){
			echo $this->tpl;
		}
	}
	
 ?>