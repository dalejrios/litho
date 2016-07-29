<?php
	$data = json_decode(file_get_contents('php://input'),true);
	$cop = $data['cop'];
	require_once('modelo.php');
	session_start();
	
	switch($cop){
		case 98165:{
			NuevoAutomata();
			break;	
		}
		case 63227:{
			NuevoEstado();
			break;	
		}
		case 63228:{
			EliminarEstado();
			break;	
		}
		case 64337:{
			EstablecerAceptacion();
			break;	
		}
		case 64338:{
			EliminarAceptacion();
			break;	
		}
		case 85973:{
			$_SESSION['automata']->EliminarTerminal($data['cod']);
			echo('1Terminal eliminado correctamente');
			break;	
		}
		case 85671:{
			$_SESSION['automata']->agregarTerminal(new Cadena($_SESSION['automata']->getKeyTerminales(),$data['et'],$data['cad']));
			echo('1Terminal Cadena = ' . $data["cad"] . ' agregada al correctamente');
			break;
		}
		case 85672:{
			$_SESSION['automata']->agregarTerminal(new Rango($_SESSION['automata']->getKeyTerminales(),$data['et'],$data['in'],$data['fn']));
			echo('1Terminal Rango = ' . $data["in"] . ' - ' . $data["fn"] . ' agregada al correctamente');
			break;
		}
		case 85673:{
			$_SESSION['automata']->agregarTerminal(new TodoExcepto($_SESSION['automata']->getKeyTerminales(),$data['et'],$data['cad']));
			echo('1Terminal todo excepto la cadena ' . $data["cad"] . ' agregada al correctamente');
			break;
		}
		case 47917:{
			$_SESSION['automata']->AgregarTransicionEpsilon($data['ini'],$data['fin']);
			echo('1Transición EPSILON agregada');
			break;	
		}
		case 47918:{
			$_SESSION['automata']->AgregarTransicion($data['ini'],$data['fin'],$data['ter']);
			echo('1Transicion agregada');
			break;	
		}
		case 39612:{
			$_SESSION['automata']->EliminarTransicion($data['ini'],$data['i']);
			echo('1Transicion Eliminada');
			break;	
		}
		case 39613:{
			$_SESSION['automata']->EliminarTransicionEpsilon($data['ini'],$data['i']);
			echo('1Transicion epsilon Eliminada');
			break;
		}
	}
	
	function NuevoAutomata(){
		$_SESSION['automata'] = new Automata(4);
		echo('1Automata creado correctamente.');
	}
	
	function NuevoEstado(){
		global $data;
		$etiqueta = $data['et'];
		$_SESSION['automata']->AgregarEstado($etiqueta);
		echo('1Estado agregado correctamente');
	}
	
	function EliminarEstado(){
		global $data;
		$numero = $data['cod'];
		$_SESSION['automata']->EliminarEstado($numero);
		echo('1Estado eliminado correctamente');	
	}
	
	function EstablecerAceptacion(){
		global $data;
		$numero = $data['cod'];
		$_SESSION['automata']->setAceptacion($numero);
		echo('1Se estableció el estado de aceptación correctamente.');
	}
	
	function EliminarAceptacion(){
		global $data;
		$numero = $data['cod'];
		$_SESSION['automata']->remAceptacion($numero);
		echo('1Se quitó el estado de aceptación correctamente.');
	}
?>