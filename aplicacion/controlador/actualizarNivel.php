<?php

	if(isset($_POST["nivel"])&&isset($_POST["subnivel"])&& isset($_POST["puntaje"])){
		$bd = new usuarioBD();
		$bd->actualizarNivel($_SESSION["username"], $_POST["nivel"]);
		$bd->actualizarNivel($_SESSION["username"], $_POST["subnivel"]);
		$bd->actualizarNivel($_SESSION["username"], $_POST["puntaje"]);
		echo "fuck";

	}
?>