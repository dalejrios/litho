<?php
	abstract class Terminal{
		protected $Codigo;
		protected $Etiqueta;
		
		abstract protected function getCodigoVisual();
		
		function __construct($Codigo,$Etiqueta){
			$this->Codigo = $Codigo;
			$this->Etiqueta = $Etiqueta;	
		}
	}
	class TodoExcepto extends Terminal{
		private $Caracter;
		
		function __construct($Codigo,$Etiqueta,$Caracter){
			$this->Caracter = $Caracter;
			
			parent::__construct($Codigo,$Etiqueta);
		}
		
		public function getCodigoVisual(){
			$ret = array('tip' => '3',
						 'et' => $this->Etiqueta,
						 'cod' => $this->Codigo,
						 'val' => "*^$this->Caracter");
			return $ret;	
		}
	}
	class Rango extends Terminal{
		private $Inicial;
		private $Final;
		
		function __construct($Codigo,$Etiqueta,$Inicial,$Final){
			$this->Inicial = $Inicial;
			$this->Final = $Final;
			
			parent::__construct($Codigo,$Etiqueta);	
		}
		
		public function getCodigoVisual(){
			$ret = array('tip' => '2',
						 'et' => $this->Etiqueta,
						 'cod' => $this->Codigo,
						 'val' => "$this->Inicial - $this->Final");
			return $ret;	
		}
	}
	class Cadena extends Terminal{
		private $Cadena;
		
		function __construct($Codigo,$Etiqueta,$Caracteres){
			$this->Cadena = $Caracteres;
			parent::__construct($Codigo,$Etiqueta);
		}
		
		public function getCodigoVisual(){
			$ret = array('tip' => '1',
						 'et' => $this->Etiqueta,
						 'cod' => $this->Codigo,
						 'val' => $this->Cadena);
			return $ret;	
		}
	}
	class Estado{
		private $Numero;
		private $Etiqueta;
		private $Transiciones;
		private $TransicionesEpsilon;
		private $Aceptacion = false;
		
		function __construct($Numero){
			$this->Numero = $Numero;
			$this->Transiciones = array();
			$this->TransicionesEpsilon = array();
		}
		public function getTransicionesEpsilon(){
			return $this->TransicionesEpsilon;	
		}
		function EliminarTransicionEpsilon($indice){
			unset($this->TransicionesEpsilon[$indice]);
		}
		function EliminarTransicion($indice){
			unset($this->Transiciones[$indice]);	
		}
		function setAceptacion(){
			$this->Aceptacion = true;
		}
		function removeAceptacion(){
			$this->Aceptacion = false;	
		}
		function getAceptacion(){
			return $this->Aceptacion;	
		}
		function setEtiqueta($Etiqueta){
			$this->Etiqueta = $Etiqueta;	
		}
		function NuevaTransicion($TerminalTransicion,$EstadoSiguiente){
			array_push($this->Transiciones,array($TerminalTransicion,$EstadoSiguiente));
		}
		function NuevaTransicionEpsilon($EstadoSiguiente){
			array_push($this->TransicionesEpsilon,$EstadoSiguiente);	
		}
		function genCodTransiciones(){
			$retorno = array();
			//Para transiciones Epsilon
			foreach($this->TransicionesEpsilon as $key => $item){
				$retorno[] = array('ini' => $this->Numero,
								   'fin' => $item,
								   'sim' => 'ɛ',
								   'i' => $key);	
			}
			//Para transiciones
			foreach($this->Transiciones as $key => $item){
				$retorno[] = array('ini' => $this->Numero,
								   'fin' => $item[0],
								   'sim' => $item[1],
								   'i' => $key);	
			}
			return $retorno;
		}
		function getNumero(){
			return $this->Numero;	
		}
		function getEtiqueta(){
			return $this->Etiqueta;	
		}
		
		function Numero(){
			foreach($this->TransicionesEpsilon as $it){
				echo("<div>$this->Etiqueta -- > $it</div>");	
			}
		}
	}
	
	class Automata{
		private $Estados;
		private $EstadosAceptacion;
		private $ContadorEstados = 0;
		private $Terminales;
		
		function __construct($CantidadEstados){
			$this->Estados = array();
			$this->Terminales = array();
		}
		
		public function getEstados(){
			return $this->Estados;	
		}
		
		public function getKeyTerminales(){
			for($i = 0; $i < count($this->Terminales); $i++){
				if(!array_key_exists("$i",$this->Terminales)){
					return $i;	
				}
			}
			return count($this->Terminales);
		}
		function agregarTerminal($terminal){
			array_push($this->Terminales,$terminal);
		}
		function setAceptacion($estado){
			$this->Estados["$estado"]->setAceptacion();	
		}
		function remAceptacion($estado){
			$this->Estados["$estado"]->removeAceptacion();	
		}
		function getKeyEstados(){
			for($i = 0; $i < count($this->Estados); $i++){
				if(!array_key_exists("$i",$this->Estados)){
					return $i;	
				}
			}
			return count($this->Estados);
		}
		function AgregarEstado($Etiqueta){
			$key = $this->getKeyEstados();
			$Nuevo = new Estado($key);
			$Nuevo->setEtiqueta($Etiqueta);
			$this->Estados[$key] = $Nuevo;
		}
		
		function EliminarEstado($Numero){
			unset($this->Estados[$Numero]);
			foreach($this->Estados as $it){
				$it->EliminarTransicionEpsilon($Numero);
			}
		}
		function EliminarTerminal($key){
			unset($this->Terminales[$key]);	
		}
		function AgregarTransicion($EstadoInicial,$CodigoTerminal,$EstadoFinal){
			$this->Estados["$EstadoInicial"]->NuevaTransicion($CodigoTerminal,$EstadoFinal);
		}
		function AgregarTransicionEpsilon($EstadoInicial,$EstadoFinal){
			$this->Estados["$EstadoInicial"]->NuevaTransicionEpsilon($EstadoFinal);	
		}
		function EliminarTransicion($estado,$indice){
			$this->Estados["$estado"]->EliminarTransicion($indice);	
		}
		function EliminarTransicionEpsilon($estado,$indice){
			$this->Estados["$estado"]->EliminarTransicionEpsilon($indice);	
		}
		function GenerarCodigoVisual(){
			$estados = array();
			$terminales = array();
			$transiciones = array();
			foreach($this->Estados as $iterator){
				$estados[] = array('No' => $iterator->getNumero(),
								   'Et' => $iterator->getEtiqueta(),
								   'Ac' => $iterator->getAceptacion());
				$transiciones = array_merge($transiciones,$iterator->genCodTransiciones());
			}
			foreach($this->Terminales as $iterator){
				$terminales[] = $iterator->getCodigoVisual();	
			}
			$automata = array('estados' => $estados,'terminales' => $terminales,'transiciones' => $transiciones);
			echo json_encode($automata);
		}
	}
?>