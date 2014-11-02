<?php
	session_start();
	require "aplicacion/controlador/controlador.php";
	$alan = new controlador();
	if(isset($_SESSION["nombre"])){
		if(isset($_POST["tipo"])){
			if($_POST["tipo"]=="cerrar"){
				$_SESSION["nombre"] = false;
				session_destroy();
				header('location:index.php');
			}
		}else{
			$alan->inicioValidado();
		}
	}else if(isset($_POST["tipo"])){
		if($_POST["tipo"]=="registro"){
			$alan->registro($_POST["usuario"], $_POST["nombre"] ,$_POST["codigo"], $_POST["email"], $_POST["contrasena"], $_POST["curso"]);
		}else if($_POST["tipo"]=="login"){
			$alan->login($_POST["usuario"], $_POST["contrasena"]);
		}
	}else{
		$alan->inicio();
	}
?>