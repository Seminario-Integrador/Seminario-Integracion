<?php

	/**
 	* .............................................
 	* UNIVERSIDAD  FRANCISCO  DE  PAULA  SANTANDER
 	*    PROGRAMA  DE  INGENIERIA  DE  SISTEMAS
 	*      ALAN Y EL MISTERIOSO REINO DE ENIAC
 	*             SAN JOSE DE CUCUTA-2014
	 * ............................................
 	*/

	include_once "aplicacion/modelo/usuarioBD.php";
	include_once "aplicacion/modelo/docenteBD.php";
	include_once "aplicacion/modelo/CursoBD.php";
	include_once "aplicacion/vista/assets.php";
	

	/**
	* Clase encargada del control principal del juego. Recibe llamados desde el index.php
	* Esta clase será extendida por las demas clases del controlador
	* @author Gerson Yesid Lázaro Carrillo 1150972
	* @author Angie Melissa Delgado León 1150990
	*/
	class Controlador{


		/**
		* Metodo que toma el archivo estatico de la pagina inicial y lo carga en pantalla
		*/
		public function inicio()
		{
			$inicio = $this->leerPlantilla("aplicacion/vista/splash.html");
			$inicio = $this->reemplazar($inicio, "{{selectCursos}}", $this->leerCursos());
			$this->mostrarVista($inicio);
		}


		/**
		* Metodo que muestra el registro para los docentes
		*/
		public function inicioDocente()
		{
			$inicio = $this->leerPlantilla("aplicacion/vista/splashDocente.html");
			$this->mostrarVista($inicio);
		}


		/**
		* Metodo que se encarga de mostrar una alerta cuando no se ha podido iniciar sesión
		*/
		public function inicioErrorLog()
		{
			$inicio = $this->leerPlantilla("aplicacion/vista/splash.html");
			$inicio = $this->reemplazar($inicio, "{{selectCursos}}", $this->leerCursos());
			$inicio = $this->alerta($inicio, "No se ha podido iniciar sesión", "Verifique sus datos e intentelo nuevamente");
			$this->mostrarVista($inicio);
		}


		/**
		* Metodo que carga un archivo de la vista
		* @param $plantilla - Ruta del archivo a cargar
		* @return string con el valor html que debe ser mostrado
		*/
		public function leerPlantilla($plantilla)
		{
				return file_get_contents($plantilla);
		}


		/**
		*	Toma una vista y la muestra en pantalla en el cliente
		* 	@param $vista - vista preconstruida para mostrar en el navegador
		*/
		public function mostrarVista($vista)
		{
			echo $vista;
		}


		/**
		*	Reemplaza un valor por otro en una cadena de texto. Utilizado para formatear las vistas
		* 	@param $ubicacion - String donde se reemplazará el valor
		* 	@param $cadenaReemplazar - Cadena que será buscada en la $ubicación
		*	@param $reemplazo - Cadena con la que se reemplazará $cadenaReemplazar
		*	@return cadena sobreescrita
		*/
		public function reemplazar($ubicacion, $cadenaReemplazar, $reemplazo)
		{
			return str_replace($cadenaReemplazar, $reemplazo, $ubicacion);
		}


		/**
		*	Registra un nuevo usuario en la base de datos (esto se realiza desde el modelo)
		*   @param $nickname - Username (diferente al nombre, este sera su login y no puede contener espacios)
		*	@param $nombre - Nombre del usuario
		* 	@param $codigo - codigo del usuario (El codigo en divisist)
		*	@param $email - Correo electronico del usuario (para validación y cambio de contraseña)
		*	@param $contrasena - Contraseña elegida por el usuario
		*   @param $curso - curso del usuario (puede estar vacio)
		*/
		public function registro($nickname, $nombre, $codigo, $email, $contrasena, $curso)
		{
			$contrasenaSH = $this->encriptarContrasena($contrasena);
			$usuarioBD = new usuarioBD();
			$usuarioBD->registrar($nickname, $nombre, $codigo, $email, $contrasenaSH, $curso);
			$this->login($nickname, $contrasena);
		}


		/**
		*	Registra un nuevo docente en la base de datos (esto se realiza desde el modelo)
		*   @param $nickname - Username (diferente al nombre, este sera su login y no puede contener espacios)
		*	@param $nombre - Nombre del usuario
		* 	@param $codigo - codigo del usuario (El codigo en divisist)
		*	@param $email - Correo electronico del usuario (para validación y cambio de contraseña)
		*	@param $contrasena - Contraseña elegida por el usuario
		*/
		public function registroDocente($nickname, $nombre, $codigo, $email, $contrasena)
		{
			$contrasenaSH = $this->encriptarContrasena($contrasena);
			$docenteBD = new docenteBD();
			$docenteBD->registrar($nickname, $nombre, $codigo, $email, $contrasenaSH);
			$this->login($nickname, $contrasena);
		}


		/**
		*	Metodo que inicia sesión y crea una clase del tipo usuario o docente
		*	@param $usuario - Nombre de usuario o docente a verificar
		*	@param $contrasena - Contrasena del usuario o docente a verificar
		*/
		public function login($usuario, $contrasena)
		{
			$contrasenaSH = $this->encriptarContrasena($contrasena);
			$usuarioBD = new usuarioBD();
			$datos = $usuarioBD->login($usuario, $contrasenaSH);
			if($datos!=false){
				$_SESSION["nombre"] = $datos;
				$_SESSION["tipoUsuario"] = "usuario";
				$this->cargarPerfil($datos);
				header('Location: index.php');
			}else{
				$docenteBD = new docenteBD();
				$datos2 = $docenteBD->login($usuario, $contrasenaSH);
					if($datos2!=false){
						$_SESSION["nombre"] = $datos2;
						$_SESSION["tipoUsuario"] = "docente";
						header('Location: index.php');
					}else{
						$this->inicioErrorLog();
					}
			}
		}


		/**
		*	Metodo de seguridad. Encripta la contraseña mediante el algoritmo SHA1. 
		*	Todas las validaciones y almacenamientos se hacen en este sistema.
		*	Las bases de datos no guardaran contraseñas tal cual las incluye el usuario.
		*	@param $password - Contraseña a encriptar
		*	@return contraseña encriptada en SHA1
		*/
		public function encriptarContrasena($password)
		{
			return sha1($password);
		}

	
		/**
		*	Método que consulta todos los cursos existentes (se realiza a traves del modelo)
		*	@return   string con el valor html del combo box de cursos
		*/
		public function leerCursos()
		{
			$cursos = new CursoBD();
			$valores = $cursos->listaDeCursos();
			$select = $this->crearSelect("curso", $valores);
			return $select;
		}


		/**
		*	Método que organiza la estructura html de un combo box
		*	@param   $nombre - nombre con el que se inicializará el select
		*	@param   $arrayOpciones - array que contiene las opciones a colocar en el select
		*	@return   string con el valor html del combo box de cursos
		*/
		public function crearSelect($nombre, $arrayOpciones)
		{
			$kit = new Assets();
			$select = $kit->getSelect();
			$select = $this->reemplazar($select, "{{nombre}}", $nombre);
			$valores = $kit->getOptionSelect();
			$contenido = "";
			if($arrayOpciones!=false){
				$contenido .= $this->reemplazar($valores, "{{valor}}", "");
				foreach ($arrayOpciones as $valor) {
					$contenido .= $this->reemplazar($valores, "{{valor}}", $valor);
				}
			}else{
				$contenido .= $this->reemplazar($valores, "{{valor}}", "");
			}
			return $this->reemplazar($select, "{{valores}}", $contenido);
		}


		/**
		*	Método que se encarga de iniciar la variable de sesión con el username y la foto de perfil del usuario
		*	@param   $nombre - nombre del usuario
		*/
		public function cargarPerfil($nombre)
		{
			$usuario = new usuarioBD();
			$datos = $usuario->obtenerDatos($nombre);
			$_SESSION["username"] = $datos[1];
			$_SESSION["fotoPerfil"] = $datos[10];
		}


		/**
		*	Método que se encarga de obtener todos los datos del usuario (a traves del modelo)
		*	@param   $nombre - nombre del usuario
		*	@return  un diccionario con todos los datos del usuario
		*/
		public function leerPerfil($nombre)
		{
			$usuario = new usuarioBD();
			$datos = $usuario->obtenerDatos($nombre);
			$array = [
    			"nombre" => $datos[0],
    			"username" => $datos[1],
    			"descripcion" => $datos[6],
    			"fotoPerfil" => $datos[10],
    			"portada" => $datos[11],
    			"puntaje" => $datos[7],
    			"nivel" => $datos[8],
    			"subnivel" => $datos[9],
			];
			return $array;
		}


		/**
		*	Método que se encarga de verifivar el perfil del usuario
		*	@param   $nombre - nombre del usuario
		*	@return  un boolean con la validación del usuario
		*/
		public function validarPerfil($nombre)
		{
			$usuario = new usuarioBD();
			$valor = $usuario->obtenerDatos($nombre);
			if($valor==false){
				return $valor;
			}else{
				return true;
			}
		}


		/**
		*	Método que se encarga de mostrar el perfil del Usuario
		*	@param $nombre - username del usuario del cual se desea ver perfil
		*/
		public function mostrarPerfil($nombre)
		{
			$array = $this->leerPerfil($nombre);
			$plantilla = $this->leerPlantilla("aplicacion/vista/index.html");
			$barraIzq = $this->leerPlantilla("aplicacion/vista/lateralIzquierda.html");
			$barraIzq = $this->reemplazar($barraIzq, "{{username}}", $_SESSION["username"]);
			$barraIzq = $this->reemplazar($barraIzq, "{{fotoPerfil}}", $_SESSION["fotoPerfil"]);
			$plantilla = $this->reemplazar($plantilla, "{{lateralIzquierda}}", $barraIzq);
			$barraDer = $this->leerPlantilla("aplicacion/vista/perfil.html");
			$superiorDer = $this->leerPlantilla("aplicacion/vista/superiorDerecho.html");
			$barraDer = $this->reemplazar($barraDer, "{{superior}}", $superiorDer);
			$barraDer = $this->reemplazar($barraDer, "{{fotoPerfil}}", $array["fotoPerfil"]);
			$barraDer = $this->reemplazar($barraDer, "{{fotoPortada}}", $array["portada"]);
			$barraDer = $this->reemplazar($barraDer, "{{nombreUsuario}}", $array["nombre"]);
			$barraDer = $this->reemplazar($barraDer, "{{nickname}}", $array["username"]);
			$barraDer = $this->reemplazar($barraDer, "{{descripcion}}", $array["descripcion"]);
			$barraDer = $this->reemplazar($barraDer, "{{puntos}}", $array["puntaje"]);
			$barraDer = $this->reemplazar($barraDer, "{{nivel}}", $array["nivel"]);
			$barraDer = $this->reemplazar($barraDer, "{{subnivel}}", $array["subnivel"]);
			$medalla = $this->leerPlantilla("aplicacion/vista/medalla.html");
			$medallas = "";
			$usuario = new usuarioBD();
			$aux="";
			$usuarioMedallas = $usuario->listarMedallasUsuario($nombre);
			foreach ($usuarioMedallas as $valor) {
				$aux = $this->reemplazar($medalla,"{{logro}}", $valor[0]);
				$medallas .= $this->reemplazar($aux,"{{tipo}}", $valor[1]);
			}
			$barraDer = $this->reemplazar($barraDer, "{{medallas}}", $medallas);
			$footer = $this->leerPlantilla("aplicacion/vista/footer.html");
			$plantilla = $this->reemplazar($plantilla, "{{lateralDerecha}}", $barraDer);
			$plantilla = $this->reemplazar($plantilla, "{{footer}}", $footer);
			$this->mostrarVista($plantilla);
		}


		/**
		*	Método que se encarga de agregar una alerta al documento html
		*	@param   $plantilla - plantilla sobre la cua se debe mostrar la alerta
		*	@param   $titulo - titulo de la alerta
		*	@param   $alerta - mensaje de la alerta
		*	@return  un string del html de la plantilla que permite la ejecucion de la alerta
		*/
		public function alerta($plantilla, $titulo, $alerta)
		{
			return $plantilla."<script>alerta(\"".$titulo."\",\"".$alerta."\",3000);</script>";
		}	


		/**
		*	Método que se encarga del proceso para recordar la contraseña del usuario
		*	@param   $correo - correo del usuario al cual se le enviaran los pasos a seguir
		*	@return  un boolean dependiendo del estado de la solicitud
		*/
		public function recordarPass($correo)
		{
			$modeloBD = new usuarioBD();
			$user = $modeloBD->buscarCorreo($correo);
			if($user!=false){
				$link = $modeloBD->buscarps($correo);
				$this->enviarVerificacion($correo, $user, $link);
				return true;
			}else{
				return false;
			}
		}


		/**
		*	Método que se encarga de enviar un correo electronico para reestablecer la contraseña del usuario
		*	@param   $correo - correo del usuario al cual se le enviaran los pasos a seguir
		*	@param   $user - nombre del usuario al cual se le enviará el correo
		*	@param   $enlace - enlace a traves del cual podrá recuperar la contraseña
		*/
		public function enviarVerificacion($correo, $user, $enlace){
			header('Location: http://alanyeniac.esy.es/recordarPassword.php?correo='.$correo.'&user='.$user.'&enlace='.$enlace);
		}

		/**
		* Valida el link enviado por correo, y si es correcto permite cambiar la contraseña
		* @param correo - Correo electronico de la cuenta a cambiar
		* @param validacion - clave secretra generada por SHA al correo.
		*/
		public function reestablecerPassword($correo, $validacion)
		{
			$modeloBD = new usuarioBD();
			$user = $modeloBD->buscarCorreo($correo);
			if($user!=false){
				$pass = $modeloBD->buscarps($correo);
				$pass = sha1($pass);
				if($pass==$validacion){
					$inicio = $this->leerPlantilla("aplicacion/vista/splash2.html");
					$inicio = $this->reemplazar($inicio, "{{correo}}", $correo);
					$inicio = $this->reemplazar($inicio, "{{validacion}}", $validacion);
					$this->mostrarVista($inicio);
				}else{
					$this->acuse("Revise el link", "Su correo y clave secreta no coinciden, o la contraseña ya fué reestablecida.");
				}
			}else{
				$this->acuse("Revise el link", "El correo suministrado no existe");
			}
		}

		/**
		*	Metodo que se encarga de guardar en la base de datos una contraseña nueva
		*	Cuando el usuario la ha olvidado.
		*	Este metodo solo puede ser accedido desde el mensaje recibido en el correo
		*	@param $pass - Nueva contraseña
		*	@param $pass2 - Nueva contraseña (el usuario debe escribiarla dos veces para verificar que esté bien escrita)
		*	@param $correo - Correo electronico del usuario que cambia la contraseña
		*	@param $validacion - Valor aleatorio para verificar que el usuario realmente recibió el correo
		*/
		public function reestablecerContra($pass, $pass2, $correo, $validacion)
		{
			$modeloBD = new usuarioBD();
			$user = $modeloBD->buscarCorreo($correo);
			if($user!=false){
				$passw = $modeloBD->buscarps($correo);
				$passw2 = sha1($passw);
				if($passw2==$validacion){
					if($pass==$pass2){
						$aux = $modeloBD->cambiarPassword($modeloBD->buscarUsername($correo), $passw, sha1($pass));
						if($aux){
							$this->inicio();
							$this->acuse("Contraseña cambiada con exito", "Ahora puedes iniciar sesión");
						}else{
							$this->acuse("Ha ocurrido un error", "Intentalo de nuevo");
						}
					}else{
						$this->reestablecerPassword($correo, $validacion);
						$this->acuse("Las contraseñas no coinciden", "Intentalo de nuevo");
					}
				}else{
					$this->acuse("Revise el link", "Su correo y enlace no coinciden, o la contraseña ya fué reestablecida.");
				}
			}else{
				$this->acuse("Revise el link", "El correo suministrado no existe");
			}
		}


		/**
		*	Método que se encargá de mostrar una alerta modal en html utilizando javascript
		*	@param $titulo - Titulo de la alerta
		* 	@param $texto - Mensaje de la alerta
		*/
		public function acuse($titulo, $texto)
		{
			$inicio = $this->leerPlantilla("aplicacion/vista/splash.html");
			$inicio = $this->reemplazar($inicio, "{{selectCursos}}", $this->leerCursos());
			$inicio = $this->alerta($inicio, $titulo, $texto);
			$this->mostrarVista($inicio);
		}



		/**
		*	Método que se encarga de transformar los datos almacenados en un JSON
		*/
		public function obtenerJSON()
		{
			$usuario = new usuarioBD();
			echo json_encode($usuario->getUsuario($_SESSION["username"]));
		}

		/**
		*	Metodo que actualiza el avance del usuario en el juego
		*	Este metodo se activará desde el javascript via Ajax cuando un usuario supera un nivel
		* 	@param $nivel - Nuevo nivel del usuario
		* 	@param $subnivel - Nuevo subnivel del usuario
		*	@param $puntaje - Nuevo puntaje del usuario
		*/
		public function actualizarJuego($nivel, $subnivel, $puntaje)
		{
			$base = new usuarioBD();
			$base->actualizarNivel($_SESSION["username"],$nivel);
			$base->actualizarSubnivel($_SESSION["username"],$subnivel);
			$base->actualizarPuntaje($_SESSION["username"], $puntaje);
		}

	}
?>