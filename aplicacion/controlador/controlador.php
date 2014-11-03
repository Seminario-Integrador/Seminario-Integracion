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
				header('Location: index.php');
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
			$_SESSION["username"] = $datos[1];
			$_SESSION["fotoPerfil"] = $datos[10];
		}
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
		public function mostrarPerfil($nombre)
		{
			$array = $this->leerPerfil($nombre);
			$plantilla = $this->leerPlantilla("aplicacion/vista/index.html");
			$barraIzq = $this->leerPlantilla("aplicacion/vista/lateralIzquierda.html");
			$barraIzq = $this->reemplazar($barraIzq, "{{username}}", $_SESSION["nombre"]);
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
				$aux = $this->reemplazar($medalla,{{}})
				$medallas .= $this->reemplazar($medalla,)
			}
			$footer = $this->leerPlantilla("aplicacion/vista/footer.html");
			$plantilla = $this->reemplazar($plantilla, "{{lateralDerecha}}", $barraDer);
			$plantilla = $this->reemplazar($plantilla, "{{footer}}", $footer);
			$this->mostrarVista($plantilla);
		}
	}
?>