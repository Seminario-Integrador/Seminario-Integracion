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
			}else if($_POST["tipo"]=="edicion"){
				$nombre = $alan->procesarImagen($_FILES['imagen']['tmp_name']);
				if($alan->editarPerfil($nombre,$_SESSION["username"],$_POST["nombre"],$_POST["descripcion"],$_POST["portada"])){
					$alan->edicionCorrecta();
				}else{
					$alan->edicionIncorrecta();
				}
			}else if($_POST["tipo"]== "cambiarPass"){
				$aux = $alan->cambiarPass($_POST["actual"],$_POST["nueva"],$_POST["repetida"]);
				if($aux=="diferentes"){
					$alan->passNoCoinciden();
				}else if($aux=="cambio"){
					$alan->edicionCorrecta();
				}else if($aux=="error"){
					$alan->edicionIncorrecta();
				}
			}
		}else if(isset($_GET["perfil"])){
			if($_GET["perfil"]=="ranking"){
				$alan->mostrarRanking();
			}else if($_GET["perfil"]=="editar"){
				$alan->verEditar();
			}else if($alan->validarPerfil($_GET["perfil"])){
				$alan->mostrarPerfil($_GET["perfil"]);
			}else{
				header('location:index.php');
			}
		}
		else{
			$alan->inicioValidado();
		}
	}else if(isset($_POST["tipo"])){
		if($_POST["tipo"]=="registro"){
			$alan->registro($_POST["usuario"], $_POST["nombre"] ,$_POST["codigo"], $_POST["email"], $_POST["contrasena"], $_POST["curso"]);
		}else if($_POST["tipo"]=="login"){
			$alan->login($_POST["usuario"], $_POST["contrasena"]);
		}else if($_POST["tipo"]=="recordarPass"){
			$registro = $alan->recordarPass($_POST["email"]);
			if($registro){
				$alan->acuseCorreoEnviado();
			}else{
				$alan->acuseCorreoNoValido();
			}
		}
	}else{
		$alan->inicio();
	}
?>