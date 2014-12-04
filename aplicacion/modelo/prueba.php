<?php
	include "usuarioBD.php";
	$usuario=new usuarioBD();
	$datos=$usuario->getUsuario("Meyito");
	echo json_encode($datos);
?>