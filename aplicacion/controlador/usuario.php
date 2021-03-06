<?php

	/**
 	* .............................................
 	* UNIVERSIDAD  FRANCISCO  DE  PAULA  SANTANDER
 	*    PROGRAMA  DE  INGENIERIA  DE  SISTEMAS
 	*      ALAN Y EL MISTERIOSO REINO DE ENIAC
 	*             SAN JOSE DE CUCUTA-2014
	* ............................................
 	*/


	require_once "aplicacion/controlador/controlador.php";
	include_once "aplicacion/modelo/usuarioBD.php";
	include_once "aplicacion/modelo/docenteBD.php";
	include_once "aplicacion/modelo/CursoBD.php";
	include_once "aplicacion/vista/assets.php";
	/**
	* 	Clase que realiza las labores del usuario
	* 	@author Gerson Lázaro - Melissa Delgado
	*/
	class Usuario extends controlador
	{

		
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
		*	Método que muestra una alerta si ocurrio un fallo al intentar actualizar la contraseña
		*/
		public function passNoCoinciden()
		{
			$plantilla = $this->verEditarFun();
			$plantilla = $this->alerta($plantilla, "Importante", "Las contraseñas no coinciden o tu contraseña actual es erronea. Intentalo de nuevo");
			$this->mostrarVista($plantilla);
		}


		/**
		*	Método que se encarga de mostrar el Ranking de usuarios
		*/
		public function mostrarRanking()
		{
			$social = $this->leerPerfil($_SESSION["nombre"]);
			$ranking = new Modelo();
			$array = $ranking->obtenerRanking();
			$plantilla = $this->leerPlantilla("aplicacion/vista/index.html");
			$barraIzq = $this->leerPlantilla("aplicacion/vista/lateralIzquierda.html");
			$barraIzq = $this->reemplazar($barraIzq, "{{username}}", $_SESSION["username"]);
			$barraIzq = $this->reemplazar($barraIzq, "{{fotoPerfil}}", $_SESSION["fotoPerfil"]);
			$plantilla = $this->reemplazar($plantilla, "{{lateralIzquierda}}", $barraIzq);
			$barraDer = $this->leerPlantilla("aplicacion/vista/espacioRanking.html");
			$barraDer = $this->reemplazar($barraDer, "{{puntos}}", $social["puntaje"]);
			$barraDer = $this->reemplazar($barraDer, "{{nivel}}", $social["nivel"]);
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
		*	Método que se encarga de personalizar la página con los datos del usuario
		*	@return $plantilla - Retorna la plantilla de edición del usuario
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
		*	Metodo que se encarga de mostrar en pantalla las guias activas e inactivas del usuario
		*	según su nivel actual.
		*/
		public function verGuia()
		{
			$plantilla = $this->leerPlantilla("aplicacion/vista/index.html");
			$barraIzq = $this->leerPlantilla("aplicacion/vista/lateralIzquierda.html");
			$barraIzq = $this->reemplazar($barraIzq, "{{username}}", $_SESSION["username"]);
			$barraIzq = $this->reemplazar($barraIzq, "{{fotoPerfil}}", $_SESSION["fotoPerfil"]);
			$plantilla = $this->reemplazar($plantilla, "{{lateralIzquierda}}", $barraIzq);
			$barraDer = $this->leerPlantilla("aplicacion/vista/espacioGuias.html");
			$superiorDer = $this->leerPlantilla("aplicacion/vista/superiorDerecho.html");
			$itemActivo = $this->leerPlantilla("aplicacion/vista/itemActivo.html");
			$itemInactivo = $this->leerPlantilla("aplicacion/vista/itemInactivo.html");
			$barraDer = $this->reemplazar($barraDer, "{{superior}}", $superiorDer);
			$usuario = new usuarioBD();
			$datos = $usuario->obtenerDatos($_SESSION["nombre"]);
			$lista = "";
			$titulosGuia = array();
			array_push($titulosGuia, "Instrucciones Secuenciales");
			array_push($titulosGuia, "Repetición de Instrucciones");
			array_push($titulosGuia, "Ciclos");
			array_push($titulosGuia, "Tipos de Ciclos");
			array_push($titulosGuia, "Condicionales");
			array_push($titulosGuia, "Tipos de condicionales");

			$textosGuia = array();
			array_push($textosGuia, "Una acción se ejecuta detrás de otra. El flujo del programa coincide con el orden físico en el que se han ido poniendo las instrucciones.");
			array_push($textosGuia, "Una acción puede repetirse muchas veces manualmente. Mas adelante, aprenderemos a realizarlo automaticamente con los ciclos");
			array_push($textosGuia, "Sentencia que realiza repetidamente un conjunto de instrucciones");
			array_push($textosGuia, "Existen varias formas de hacer ciclos: For (hacer para), While (Mientras) y Do while (Hacer mientras)");
			array_push($textosGuia, "Algunas instrucciones se ejecutan solo si se cumple una condición previa");
			array_push($textosGuia, "Existen las condicionales if (si), if-else (si, sino), if-else if-else (si, o si, si no) y switch" );

			$nivelesGuia = array();
			array_push($nivelesGuia, 1);
			array_push($nivelesGuia, 1);
			array_push($nivelesGuia, 2);
			array_push($nivelesGuia, 2);
			array_push($nivelesGuia, 3);
			array_push($nivelesGuia, 3);

			$subnivelesGuia = array();
			array_push($subnivelesGuia, 0);
			array_push($subnivelesGuia, 2);
			array_push($subnivelesGuia, 1);
			array_push($subnivelesGuia, 2);
			array_push($subnivelesGuia, 1);
			array_push($subnivelesGuia, 2);
			for($i=0;$i<sizeof($textosGuia);$i++){
				if($datos["nivel"]>=$nivelesGuia[$i]){
					if($datos["subnivel"]>=$subnivelesGuia[$i]){
						$aux = $itemActivo;
						$aux = $this->reemplazar($aux, "{{item-titulo}}",$titulosGuia[$i]);
						$aux = $this->reemplazar($aux, "{{item-texto}}",$textosGuia[$i]);
					}else{
						$aux = $itemInactivo;
						$aux = $this->reemplazar($aux, "{{item-titulo}}",$titulosGuia[$i]);
						$aux = $this->reemplazar($aux, "{{item-texto}}",$textosGuia[$i]);
					}
				}else{
					$aux = $itemInactivo;
					$aux = $this->reemplazar($aux, "{{item-titulo}}",$titulosGuia[$i]);
					$aux = $this->reemplazar($aux, "{{item-texto}}",$textosGuia[$i]);
				}
				$lista .= $aux;
			}
			$barraDer = $this->reemplazar($barraDer, "{{items}}", $lista);
			$footer = $this->leerPlantilla("aplicacion/vista/footer.html");
			$plantilla = $this->reemplazar($plantilla, "{{lateralDerecha}}", $barraDer);
			$plantilla = $this->reemplazar($plantilla, "{{footer}}", $footer);
			$this->mostrarVista($plantilla);
		}
	}
?>