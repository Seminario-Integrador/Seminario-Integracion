<?php
	require "aplicacion/controlador/controlador.php";
	$alan = new controlador();
	if(isset($_POST["tipo"])){
		if($_POST["tipo"]=="registro"){
			$alan->registro($_POST["usuario"],$_POST["codigo"], $_POST["email"], $_POST["contrasena"]);
		}
	}
	$alan->inicio();
?>