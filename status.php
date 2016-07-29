<?php
	require_once('modelo.php');
	session_start();
	if(isset($_SESSION['automata'])){
		$_SESSION['automata']->GenerarCodigoVisual();	
	}else{
		echo('no se encuentra el automata creado');	
	}
?>