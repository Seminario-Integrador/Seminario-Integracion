 <?php

 	/**
 	* .............................................
 	* UNIVERSIDAD  FRANCISCO  DE  PAULA  SANTANDER
 	*    PROGRAMA  DE  INGENIERIA  DE  SISTEMAS
 	*      ALAN Y EL MISTERIOSO REINO DE ENIAC
 	*             SAN JOSE DE CUCUTA-2014
	 * ............................................
 	*/


 	/**
 	*	Enrutador
 	* 	Este archivo se encarga de detectar las peticiones (interacciones) que realiza el usuario
 	*	Y llamar al controlador correspondiente a llevar a cabo la acción solicitada
 	*	@author - Gerson Yesid Lázaro 
 	*	@author - Angie Melissa Delgado
 	*/
	session_start();
	require "aplicacion/controlador/controlador.php";
	require "aplicacion/controlador/usuario.php";
	require "aplicacion/controlador/docente.php";
	$alan = new controlador();

	//Si la variable de sesión "tipoUsuario" existe, el usuario está logueado
	if(isset($_SESSION["tipoUsuario"])){
		//Si la variable de post "tipo" existe, el usuario ha enviado un formulario
		if(isset($_POST["tipo"])){
			//Si la variable tipo tiene el valor cerrar, el usuario ha pedido cerrar sesión.
			//En este caso, se borran las variables de sesión, y se destruye dicha sesión.
			//Tanto en docentes como en alumnos aplica por igual
			if($_POST["tipo"]=="cerrar"){
				$_SESSION["nombre"] = false;
				$_SESSION["tipoUsuario"] = false;
				session_destroy();
				header('location:index.php');
			}
			//Acciones POST que solo puede realizar un usuario estudiante
			else if($_SESSION["tipoUsuario"]=="usuario"){
				$usuarioAlan = new Usuario();
				//Envia ediciones de datos del perfil a la aplicación
				if($_POST["tipo"]=="edicion"){
					$nombre = $usuarioAlan->procesarImagen($_FILES['imagen']['tmp_name']);
					if($usuarioAlan->editarPerfil($nombre,$_SESSION["username"],$_POST["nombre"],$_POST["descripcion"],$_POST["portada"])){
						$usuarioAlan->edicionCorrecta();
					}else{
						$usuarioAlan->edicionIncorrecta();
					}
				}
				//El usuario solicita un cambio de contraseña
				else if($_POST["tipo"]== "cambiarPass"){
					$aux = $usuarioAlan->cambiarPass($_POST["actual"],$_POST["nueva"],$_POST["repetida"]);
					if($aux=="diferentes"){
						$usuarioAlan->passNoCoinciden();
					}else if($aux=="cambio"){
						$usuarioAlan->edicionCorrecta();
					}else if($aux=="error"){
						$usuarioAlan->edicionIncorrecta();
					}
				}
			}
			//Acciones que solo pueden realizarse por un docente
			else if($_SESSION["tipoUsuario"]=="docente"){
				$docenteAlan = new Docente();
				//Se ejecuta si el usuario crea un nuevo curso
				if($_POST["tipo"]=="crearCurso"){
					$docenteAlan->crearCurso($_POST["nombreCurso"]);
					$docenteAlan->crearNuevoCurso();
				}

			}
		}
		//Acciones que solo puede realizar un usuario, y han sido enviadas por GET
		else if($_SESSION["tipoUsuario"]=="usuario"){
			$usuarioAlan = new Usuario();
			//Se ejecuta cuando un usuario desea ver su perfil o el de alguien mas
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
			}
			//Envia los datos via JSON para ser accedidos por el javascript del juego
			else if(isset($_GET["JSON"]) && isset($_SESSION["username"])){
				$alan->obtenerJSON();
			}
			//Actualiza los valores de juego del usuario
			else if(isset($_POST["nivel"]) && isset($_POST["subnivel"]) && isset($_POST["puntaje"])){
				$alan->actualizarJuego($_POST["nivel"],$_POST["subnivel"],$_POST["puntaje"]);
			}
			//Entra al inicio (juego)
			else{
				$usuarioAlan->inicioValidado();
			}
		}
		//Acciones que solo puede realizar un docente, y han sido enviadas por GET
		else if($_SESSION["tipoUsuario"]=="docente"){
			$docenteAlan = new Docente();
			//Crea un curso nuevo o lista los ya existentes
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
	}
	//Acciones para usuarios no logueados
	else if(isset($_POST["tipo"])){
		//Registra un nuevo usuario
		if($_POST["tipo"]=="registro"){
			$alan->registro($_POST["usuario"], $_POST["nombre"] ,$_POST["codigo"], $_POST["email"], $_POST["contrasena"], $_POST["curso"]);
		}
		//Un usuario (docente o estudiante) inicia sesión
		else if($_POST["tipo"]=="login"){
			$alan->login($_POST["usuario"], $_POST["contrasena"]);
		}
		//Un usuario estudiante pide reestablecimiento de su contraseña
		else if($_POST["tipo"]=="recordarPass"){
			$registro = $alan->recordarPass($_POST["email"]);
			if($registro){
				$alan->acuse("Revise su correo", "Se ha enviado un correo a su buzón con las instrucciones");
			}else{
				$alan->acuse("Verifique sus datos", "El correo suministrado no se encuentra en nuestra base");
			}
		}
		//Un docente se registra
		else if($_POST["tipo"]=="registroDocente"){
			$alan->registroDocente($_POST["usuario"], $_POST["nombre"] ,$_POST["codigo"], $_POST["email"], $_POST["contrasena"]);
		}
		//Reestablece la contraseña luego de recibir el correo
		else if($_POST["tipo"]=="reestablecerPass"){
			$alan->reestablecerContra($_POST["pass"], $_POST["passRepetida"], $_POST["correo"], $_POST["validacion"]);
		}
	}
	//Muestra el formulario de registro docente
	else if(isset($_GET["perfil"])){
		if($_GET["perfil"]=="registrarDocente"){
			$alan->inicioDocente();
		}else{
			$alan->inicio();
		}
	}
	//Verifica el envio del correo al recuperar contraseña
	else if(isset($_GET["recuperado"])){
		if($_GET["recuperado"]=="si"){
			$alan->acuse("Revise su correo", "Se ha enviado un correo a su buzón con las instrucciones");
		}else{
			$alan->acuse("Verifique sus datos", "El correo suministrado no se encuentra en nuestra base");
		}

	}
	//Muestra el campo para reestablecimiento de contraseña
	else if(isset($_GET["correo"]) && isset($_GET["recuperar"])){
		$alan->reestablecerPassword($_GET["correo"], $_GET["recuperar"]);
	}
	//Muestra el inicio del juego
	else{
		$alan->inicio();
	}
	
?>