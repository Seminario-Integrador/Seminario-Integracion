<?php
	include "aplicacion/modelo/usuarioBD.php";
	include "aplicacion/modelo/CursoBD.php";
	include "aplicacion/vista/assets.php";
	/**
	* Clase encargada del control principal del juego. Recibe llamados desde el index.php
	* Esta clase será extendida por las demas clases del controlador
	* @author Melissa Delgado - Gerson Lázaro
	*/
	class Controlador{
		/**
		* Metodo que toma el archivo estatico de la pagina inicial y lo carga 
		*/
		public function inicio()
		{
			//REEMPLAZAR
			$inicio = $this->leerPlantilla("aplicacion/vista/splash.html");
			$inicio = $this->reemplazar($inicio, "{{selectCursos}}", $this->leerCursos());
			$this->mostrarVista($inicio);
		}
		/**
		*	Muestra el inicio cuando no el usuario esta logueado
		*/
		public function inicioValidado()
		{
			$plantilla = $this->leerPlantilla("aplicacion/vista/index.html");
			$barraIzq = $this->leerPlantilla("aplicacion/vista/lateralIzquierda.html");
			$barraIzq = $this->reemplazar($barraIzq, "{{username}}", $_SESSION["nombre"]);
			$barraIzq = $this->reemplazar($barraIzq, "{{fotoPerfil}}", $_SESSION["fotoPerfil"]);
			$plantilla = $this->reemplazar($plantilla, "{{lateralIzquierda}}", $barraIzq);
			$barraDer = $this->leerPlantilla("aplicacion/vista/espacioJuego.html");
			$superiorDer = $this->leerPlantilla("aplicacion/vista/superiorDerecho.html");
			$barraDer = $this->reemplazar($barraDer, "{{superior}}", $superiorDer);
			$plantilla = $this->reemplazar($plantilla, "{{lateralDerecha}}", $barraDer);
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
		*	@param $nombre - Nombre del usuario
		* 	@param $codigo - codigo del usuario (El codigo en divisist)
		*	@param $email - Correo electronico del usuario (para validación y cambio de contraseña)
		*	@param $contrasena - Contraseña elegida por el usuario
		*/
		public function registro($nickname, $nombre, $codigo, $email, $contrasena, $curso)
		{
			$contrasenaSH = $this->encriptarContrasena($contrasena);
			$usuarioBD = new usuarioBD();
			$usuarioBD->registrar($nickname, $nombre, $codigo, $email, $contrasenaSH, $curso);
			$this->login($nickname, $contrasena);
		}

		/**
		*	Metodo que inicia sesión y crea una clase del tipo usuario
		*	@param $usuario - Nombre de usuario a verificar
		*	@param $contrasena - Contrasena del usuario a verificar
		*/
		public function login($usuario, $contrasena)
		{
			$contrasenaSH = $this->encriptarContrasena($contrasena);
			$usuarioBD = new usuarioBD();
			$datos = $usuarioBD->login($usuario, $contrasenaSH);
			if($datos!=false){
				$_SESSION["nombre"] = $datos;
				$this->cargarPerfil($datos);
				$this->inicioValidado();
			}else{
				$this->inicio();
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

		public function leerCursos()
		{
			$cursos = new CursoBD();
			$valores = $cursos->listarCursos();
			$select = $this->crearSelect("curso", $valores);
			return $select;
		}
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
		public function cargarPerfil($nombre)
		{
			$usuario = new usuarioBD();
			$datos = $usuario->obtenerDatos($nombre);
			echo $datos[0]." ".$datos[1]." ".$datos[2]." ".$datos[3]." ".$datos[4]." ".$datos[5]." ".$datos[6]." ".$datos[7]." ".$datos[8]." ".$datos[9]." ".$datos[10]." ".$datos[11]." ";
			$_SESSION["fotoPerfil"] = $datos[10];
			$_SESSION["portada"] = $datos[11];
		}
	}
?>