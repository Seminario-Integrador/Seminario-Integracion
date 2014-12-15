<?php

	/**
 	* .............................................
 	* UNIVERSIDAD  FRANCISCO  DE  PAULA  SANTANDER
 	*    PROGRAMA  DE  INGENIERIA  DE  SISTEMAS
 	*      ALAN Y EL MISTERIOSO REINO DE ENIAC
 	*             SAN JOSE DE CUCUTA-2014
	 * ............................................
 	*/

	include "aplicacion/modelo/usuarioBD.php";
	include "aplicacion/modelo/docenteBD.php";
	include "aplicacion/modelo/CursoBD.php";
	include "aplicacion/vista/assets.php";
	
	/**
	* Clase encargada del control principal del juego. Recibe llamados desde el index.php
	* Esta clase será extendida por las demas clases del controlador
	* @author Gerson Yesid Lázaro Carrillo 1150972
	* @author Angie Melissa Delgado León 1150990
	*/
	class Controlador{

		/**
		* Metodo que toma el archivo estatico de la pagina inicial y lo carga 
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
		*	Muestra el inicio cuando el usuario esta logueado
		*/
		public function inicioValidado()
		{
			$plantilla = $this->leerPlantilla("aplicacion/vista/index.html");
			$barraIzq = $this->leerPlantilla("aplicacion/vista/lateralIzquierda.html");
			$barraIzq = $this->reemplazar($barraIzq, "{{username}}", $_SESSION["username"]);
			$barraIzq = $this->reemplazar($barraIzq, "{{fotoPerfil}}", $_SESSION["fotoPerfil"]);
			$plantilla = $this->reemplazar($plantilla, "{{lateralIzquierda}}", $barraIzq);
			$barraDer = $this->leerPlantilla("aplicacion/vista/espacioJuego.html");
			$superiorDer = $this->leerPlantilla("aplicacion/vista/superiorDerecho.html");
			$barraDer = $this->reemplazar($barraDer, "{{superior}}", $superiorDer);
			$footer = $this->leerPlantilla("aplicacion/vista/footer.html");
			$plantilla = $this->reemplazar($plantilla, "{{lateralDerecha}}", $barraDer);
			$plantilla = $this->reemplazar($plantilla, "{{footer}}", $footer);
			$this->mostrarVista($plantilla);
		}



		/**
		*	Creacion de nuevos cursos por parte del docente
		*/
		public function crearNuevoCurso()
		{
			$plantilla = $this->leerPlantilla("aplicacion/vista/index.html");
			$barraIzq = $this->leerPlantilla("aplicacion/vista/lateralIzquierdaDocente.html");
			$barraIzq = $this->reemplazar($barraIzq, "{{username}}", $_SESSION["nombre"]);
			$barraIzq = $this->reemplazar($barraIzq, "{{fotoPerfil}}", "docente.png");
			$plantilla = $this->reemplazar($plantilla, "{{lateralIzquierda}}", $barraIzq);
			$superiorDer = $this->leerPlantilla("aplicacion/vista/superiorDerecho.html");
			$barraDer = $this->leerPlantilla("aplicacion/vista/crearCurso.html");
			$barraDer = $this->reemplazar($barraDer, "{{superior}}", $superiorDer);
			$footer = $this->leerPlantilla("aplicacion/vista/footer.html");
			$plantilla = $this->reemplazar($plantilla, "{{lateralDerecha}}", $barraDer);
			$plantilla = $this->reemplazar($plantilla, "{{footer}}", $footer);
			$this->mostrarVista($plantilla);
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
			$valores = $cursos->listarCursos();
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
		*	Método que se encarga de mostrar el Ranking de usuarios
		*/
		public function mostrarRanking()
		{
			$ranking = new Modelo();
			$array = $ranking->obtenerRanking();
			$plantilla = $this->leerPlantilla("aplicacion/vista/index.html");
			$barraIzq = $this->leerPlantilla("aplicacion/vista/lateralIzquierda.html");
			$barraIzq = $this->reemplazar($barraIzq, "{{username}}", $_SESSION["username"]);
			$barraIzq = $this->reemplazar($barraIzq, "{{fotoPerfil}}", $_SESSION["fotoPerfil"]);
			$plantilla = $this->reemplazar($plantilla, "{{lateralIzquierda}}", $barraIzq);
			$barraDer = $this->leerPlantilla("aplicacion/vista/espacioRanking.html");
			$superiorDer = $this->leerPlantilla("aplicacion/vista/superiorDerecho.html");
			$barraDer = $this->reemplazar($barraDer, "{{superior}}", $superiorDer);
			$posicion = $this->leerPlantilla("aplicacion/vista/personaRanking.html");
			$posiciones = "";
			$aux;
			$cont=1;
			foreach ($array as $valor) {
				$aux = $this->reemplazar($posicion, "{{posicion}}",$cont);
				$aux = $this->reemplazar($aux, "{{username}}",$valor[0]);
				$aux = $this->reemplazar($aux, "{{nivel}}",$valor[1]);
				$posiciones .= $this->reemplazar($aux, "{{puntaje}}",$valor[2]);
				$cont++;
			}
			$barraDer = $this->reemplazar($barraDer, "{{tabla}}", $posiciones);
			$footer = $this->leerPlantilla("aplicacion/vista/footer.html");
			$plantilla = $this->reemplazar($plantilla, "{{lateralDerecha}}", $barraDer);
			$plantilla = $this->reemplazar($plantilla, "{{footer}}", $footer);
			$this->mostrarVista($plantilla);

		}

		/**
		*	Método que se encarga de mostrar el perfil del Usuario
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
		*	Método que se encarga de personalizar la página con los datos del usuario
		*/
		public function verEditarFun()
		{
			$array = $this->leerPerfil($_SESSION["username"]);
			$plantilla = $this->leerPlantilla("aplicacion/vista/index.html");
			$barraIzq = $this->leerPlantilla("aplicacion/vista/lateralIzquierda.html");
			$barraIzq = $this->reemplazar($barraIzq, "{{username}}", $_SESSION["username"]);
			$barraIzq = $this->reemplazar($barraIzq, "{{fotoPerfil}}", $_SESSION["fotoPerfil"]);
			$plantilla = $this->reemplazar($plantilla, "{{lateralIzquierda}}", $barraIzq);
			$superiorDer = $this->leerPlantilla("aplicacion/vista/superiorDerecho.html");
			$barraDer = $this->leerPlantilla("aplicacion/vista/editarPerfil.html");
			$barraDer = $this->reemplazar($barraDer, "{{fotoPerfil}}", $_SESSION["fotoPerfil"]);
			$barraDer = $this->reemplazar($barraDer, "{{username}}", $_SESSION["username"]);
			$barraDer = $this->reemplazar($barraDer, "{{nombre}}", $array["nombre"]);
			$barraDer = $this->reemplazar($barraDer, "{{descripcion}}", $array["descripcion"]);
			$barraDer = $this->reemplazar($barraDer, "{{superior}}", $superiorDer);
			$plantilla = $this->reemplazar($plantilla, "{{lateralDerecha}}",$barraDer);
			$footer = $this->leerPlantilla("aplicacion/vista/footer.html");
			$plantilla = $this->reemplazar($plantilla, "{{footer}}", $footer);
			return $plantilla;
		}

		/**
		*	Método que se encarga de personalizar  y mostrar la página con los datos del usuario
		*/
		public function verEditar()
		{
			$plantilla = $this->verEditarFun();
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
		*	Método que se encarga de la edición de los datos del usuario(a traves del modelo)
		*	@param   $imagen - ruta de la nueva imagen de perfil del usuario
		*	@param   $username - nombre de usuario
		*	@param   $nombre - nombre del usuario
		*	@param   $descripcion - descripcion del usuario
		*	@param   $portada - identificador de la portada del usuario
		*	@return  un boolean, true si la edición se realizó satisfactoriamente
		*/
		public function editarPerfil($imagen,$username,$nombre,$descripcion,$portada)
		{
			$usuarioBD = new usuarioBD();
			$salida = true;
			if($imagen!=""){
				$salida = $usuarioBD->actualizarImagen($username,$imagen);
				$_SESSION["fotoPerfil"] = $imagen;
				
			}
			if($nombre!=""){
				if(!$usuarioBD->actualizarNombre($username,$nombre)){
					$salida = false;
				}

			}
			if($descripcion!=""){
				if(!$usuarioBD->actualizarDescripcion($username,$descripcion)){
					$salida = false;
				}
			}
			if($portada!="" && $portada!="0"){
				if(!$usuarioBD->actualizarPortada($username,$portada)){
					$salida = false;
				}
			}
			return $salida;
		}

		/**
		*	Método que muestra una alerta si la edición de los datos se realizo satisfacotiramente
		*/
		public function edicionCorrecta()
		{
			$plantilla = $this->verEditarFun();
			$plantilla = $this->alerta($plantilla, "Edición Correcta", "Tus datos han sido guardados correctamente :)");
			$this->mostrarVista($plantilla);
		}

		/**
		*	Método que muestra una alerta si la edición de los datos no se pudo realizar
		*/
		public function edicionIncorrecta()
		{
			$plantilla = $this->verEditarFun();
			$plantilla = $this->alerta($plantilla, "Edición Incorrecta", "Por un error interno los datos no han podido guardarse :(");
			$this->mostrarVista($plantilla);
		}

		/**
		*	Método que muestra una alerta si ocurrio un fallo al intentar actualizar la contraseña
		*/
		public function passNoCoinciden()
		{
			$plantilla = $this->verEditarFun();
			$plantilla = $this->alerta($plantilla, "Importante", "Las contraseñas no coinciden o tu contraseña actual es erronea. Intentalo de nuevo");
			$this->mostrarVista($plantilla);
		}

		/**
		*	Método que se encarga de asignar el nombre con el que se guardará la imagen de perfil del usuario
		*	@param   $imagen - nombre de la nueva imagen de perfil del usuario
		*	@return  un string con el nombre con el que se almacenará la imagen
		*/
		public function procesarImagen($imagen)
		{
			$nombre = $_FILES['imagen']['name'];
			if($nombre!=""){
				if(!file_exists("estatico/img/perfil/".$nombre)){
					move_uploaded_file($_FILES['imagen']['tmp_name'],"estatico/img/perfil/".$nombre);
				}else{
					$cont=1;
					while(file_exists("estatico/img/perfil/"."[".$cont."]".$nombre)){
						$cont++;
					}
					move_uploaded_file($_FILES['imagen']['tmp_name'],"estatico/img/perfil/"."[".$cont."]".$nombre);
					$nombre =  "[".$cont."]".$_FILES['imagen']['name'];
				}
			}
			return $nombre;
		}

		/**
		*	Método que se encarga de validar la información para el cambio de contraseña
		*	@param   $actual - contraseña actual del usuario
		*	@param   $nueva - contraseña nueva del usuario
		*	@param   $repetida - contraseña actual del usuario
		*	@return  un string con el estado de la solicitud 
		*/
		public function cambiarPass($actual, $nueva, $repetida)
		{
			$_actual = sha1($actual);
			$_nueva = sha1($nueva);
			$_repetida = sha1($repetida);
			if($_nueva!=$_repetida){
				return "diferentes";
			}else{
				$usuarioBD = new usuarioBD();
				if($usuarioBD->login($_SESSION["username"], $_actual)!=false){
					if($usuarioBD->cambiarPassword($_SESSION["username"], $_actual, $_nueva)){
						return "cambio";
					}else{
						return "error";
					}
				}else{
					return "diferentes";
				}
			}
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
		*	Método que se encarga de enviar un correo electronico recordar la contraseña del usuario
		*	@param   $correo - correo del usuario al cual se le enviaran los pasos a seguir
		*	@param   $user - nombre del usuario al cual se le enviará el correo
		*	@param   $enlace - enlace a traves del cual podrá recuperar la contraseña
		*/
		public function enviarVerificacion($correo, $user, $enlace)
		{	$link= "http://localhost/integrador/Seminario-Integracion/index.php?recuperar="+sha1($enlace);
			$asunto = "Recuperar Contraseña - Alan y el reino de Eniac";
			//$mensaje = "Hola ".$user." ¿Como va todo? \n Enviamos este mensaje porque has solicitado cambiar tu contraseña en Alan y el reino 
			//de Eniac. Si realmente solicitaste este cambio haz clic en el siguiente link o copialo y pegalo en tu navegador. De lo contrario, elimina este mensaje. \n".$link."
			//\n Att. El equipo De Alan \n Gerson Lazaro-Melissa Delgado \n Ingenieria de Sistemas UFPS";
			$mensaje = "holiwis"; 
			$vari = mail($correo, $asunto, $mensaje);
			if($vari){
				echo "se envio";
			}else{
				echo "no se envio";
			}

		}

		/**
		* NO SE SABE xD 
		*/
		public function enviarVerificacio($value='')
		{
			# code...
		}

		/**
		*	Método que se encargá de mostrar una alerta si el correo electrónico fue enviado exitosamente
		*/
		public function acuseCorreoEnviado()
		{
			$inicio = $this->leerPlantilla("aplicacion/vista/splash.html");
			$inicio = $this->reemplazar($inicio, "{{selectCursos}}", $this->leerCursos());
			$inicio = $this->alerta($inicio, "Revise su correo", "Se ha enviado un correo a su buzón con las instrucciones");
			$this->mostrarVista($inicio);
		}


		/**
		*	Método que se encargá de mostrar una alerta si el correo electrónico no fue enviado 
		*/
		public function acuseCorreoNoValido()
		{
			$inicio = $this->leerPlantilla("aplicacion/vista/splash.html");
			$inicio = $this->reemplazar($inicio, "{{selectCursos}}", $this->leerCursos());
			$inicio = $this->alerta($inicio, "Verifique sus datos", "El correo suministrado no se encuentra en nuestra base");
			$this->mostrarVista($inicio);
		}

		/**
		*	Método que se encargá de generar una nueva contraseña temporal para ser reestablecida
		*/
		function generarPassword (){
		  $string = "";
		  $posible = "0123456789abcdfghjkmnpqrstvwxyz";
		  $i = 0;
		  while ($i < 8) {
		    $char = substr($posible, mt_rand(0, strlen($posible)-1), 1);
		    $string .= $char;
		    $i++;
		  }
		  return $string;
		}

		/**
		*	Método que se encarga de transformar los datos almacenados en un JSON
		*/
		public function obtenerJSON()
		{
			$usuario = new usuarioBD();
			echo json_encode($usuario->getUsuario($_SESSION["username"]));
		}
		public function actualizarJuego($nivel, $subnivel, $puntaje)
		{
			$base = new usuarioBD();
			$base->actualizarNivel($_SESSION["username"],$nivel);
			$base->actualizarSubnivel($_SESSION["username"],$subnivel);
			$base->actualizarPuntaje($_SESSION["username"], $puntaje);
		}
	}
?>