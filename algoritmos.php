<?php
	require_once('modelo.php');
	session_start();
	$subconjuntos = new Subconjuntos($_SESSION['automata']->getEstados());
	echo("creado");
	
	
	
	class Subconjuntos{
		private $Estados;
		
		function __construct($Estados){
			$this->Estados = $Estados;
		}
		
		function Transformar(){
				
		}
		
		function Cerradura($Estado){
			
		}
		private $itCerradura;
		function CalculoCerradura($Estado){
			if($this->itCerradura["$Estado"] != NULL){
				$this->itCerradura["$Estado"] = '1';
				$TransicionesEpsilon = $this->Estados["$Estado"]->getTransicionesEpsilon();
				foreach($TransicionesEpsilon as $it){
					CalculoCerradura($it);
				}
			}
		}
	}
?>