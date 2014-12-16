 <?php

 	/**
 	* .............................................
 	* UNIVERSIDAD  FRANCISCO  DE  PAULA  SANTANDER
 	*    PROGRAMA  DE  INGENIERIA  DE  SISTEMAS
 	*      ALAN Y EL MISTERIOSO REINO DE ENIAC
 	*             SAN JOSE DE CUCUTA-2014
	 * ............................................
 	*/
	session_start();
	require "aplicacion/controlador/controlador.php";
	require "aplicacion/controlador/usuario.php";
	require "aplicacion/controlador/docente.php";
	$alan = new controlador();
	if(isset($_SESSION["tipoUsuario"])){
		if(isset($_POST["tipo"])){
			if($_POST["tipo"]=="cerrar"){
				$_SESSION["nombre"] = false;
				$_SESSION["tipoUsuario"] = false;
				session_destroy();
				header('location:index.php');
			}else if($_SESSION["tipoUsuario"]=="usuario"){
				$usuarioAlan = new Usuario();
				if($_POST["tipo"]=="edicion"){
					$nombre = $usuarioAlan->procesarImagen($_FILES['imagen']['tmp_name']);
					if($usuarioAlan->editarPerfil($nombre,$_SESSION["username"],$_POST["nombre"],$_POST["descripcion"],$_POST["portada"])){
						$usuarioAlan->edicionCorrecta();
					}else{
						$usuarioAlan->edicionIncorrecta();
					}
				}else if($_POST["tipo"]== "cambiarPass"){
					$aux = $usuarioAlan->cambiarPass($_POST["actual"],$_POST["nueva"],$_POST["repetida"]);
					if($aux=="diferentes"){
						$usuarioAlan->passNoCoinciden();
					}else if($aux=="cambio"){
						$usuarioAlan->edicionCorrecta();
					}else if($aux=="error"){
						$usuarioAlan->edicionIncorrecta();
					}
				}
			}else if($_SESSION["tipoUsuario"]=="docente"){
				$docenteAlan = new Docente();
				if($_POST["tipo"]=="crearCurso"){
					$docenteAlan->crearCurso($_POST["nombreCurso"]);
					$docenteAlan->crearNuevoCurso();
				}

			}
		}else if($_SESSION["tipoUsuario"]=="usuario"){
			$usuarioAlan = new Usuario();
			if(isset($_GET["perfil"])){
				if($_GET["perfil"]=="ranking"){
					$usuarioAlan->mostrarRanking();
				}else if($_GET["perfil"]=="editar"){
					$usuarioAlan->verEditar();
				}else if($alan->validarPerfil($_GET["perfil"])){
					$alan->mostrarPerfil($_GET["perfil"]);
				}else{
					header('location:index.php');
				}
			}else if(isset($_GET["JSON"]) && isset($_SESSION["username"])){
				$alan->obtenerJSON();
			}else if(isset($_POST["nivel"]) && isset($_POST["subnivel"]) && isset($_POST["puntaje"])){
				$alan->actualizarJuego($_POST["nivel"],$_POST["subnivel"],$_POST["puntaje"]);
			}else{
				$usuarioAlan->inicioValidado();
			}
		}else if($_SESSION["tipoUsuario"]=="docente"){
			$docenteAlan = new Docente();
			if(isset($_GET["perfil"])){
				if($_GET["perfil"]=="crearCurso"){
					$docenteAlan->crearNuevoCurso();
				}else if($_GET["perfil"]=="verCursos"){
					$docenteAlan->listarCursos();
				}
			}else{
				$docenteAlan->crearNuevoCurso();
			}
			
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
		}else if($_POST["tipo"]=="registroDocente"){
			$alan->registroDocente($_POST["usuario"], $_POST["nombre"] ,$_POST["codigo"], $_POST["email"], $_POST["contrasena"]);
		}
	}else if(isset($_GET["perfil"])){
		if($_GET["perfil"]=="registrarDocente"){
			$alan->inicioDocente();
		}
	}else{
		$alan->inicio();
	}
	
?>