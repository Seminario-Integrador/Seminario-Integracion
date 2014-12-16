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
				}else if($_GET["perfil"]=="guiaDeJuego"){
					$usuarioAlan->verGuia();
				}else if($alan->validarPerfil($_GET["perfil"])){
					$alan->mostrarPerfil($_GET["perfil"]);
				}else{
					$usuarioAlan->inicioValidado();
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
				$alan->acuse("Revise su correo", "Se ha enviado un correo a su buzón con las instrucciones");
			}else{
				$alan->acuse("Verifique sus datos", "El correo suministrado no se encuentra en nuestra base");
			}
		}else if($_POST["tipo"]=="registroDocente"){
			$alan->registroDocente($_POST["usuario"], $_POST["nombre"] ,$_POST["codigo"], $_POST["email"], $_POST["contrasena"]);
		}else if($_POST["tipo"]=="reestablecerPass"){
			$alan->reestablecerContra($_POST["pass"], $_POST["passRepetida"], $_POST["correo"], $_POST["validacion"]);
		}
	}else if(isset($_GET["perfil"])){
		if($_GET["perfil"]=="registrarDocente"){
			$alan->inicioDocente();
		}else{
			$alan->inicio();
		}
	}else if(isset($_GET["recuperado"])){
		if($_GET["recuperado"]=="si"){
			$alan->acuse("Revise su correo", "Se ha enviado un correo a su buzón con las instrucciones");
		}else{
			$alan->acuse("Verifique sus datos", "El correo suministrado no se encuentra en nuestra base");
		}

	}else if(isset($_GET["correo"]) && isset($_GET["recuperar"])){
		$alan->reestablecerPassword($_GET["correo"], $_GET["recuperar"]);
	}else{
		$alan->inicio();
	}
	
?>