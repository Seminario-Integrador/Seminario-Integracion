<?php
	require_once "aplicacion/modelo/modelo.php";

	class UsuarioBD extends Modelo{

		/**
		*	Método que se encarga de crear la consulta para guardar un nuevo usuario en la Base de Datos.
		*	@param $username - Username del usuario a registrar
		*	@param $nombre - Nombre del usuario a registrar
		*	@param $codigo - Codigo del usuario a registrar
		*	@param $email - Email del usuario a registrar
		*	@param $contraseña - Contraseña del usuario a registrar
		*	@param $curso - Curso al que pertenece el usuario a registrar
		*/
		public function registrar($username, $nombre, $codigo, $email, $contrasena, $curso)
		{
			$this->conectar("localhost", "root", "", "alangame");
			if($curso==""){
				$this->consultar("INSERT INTO usuario VALUES('".$nombre."','".$username."','".$email."','".$contrasena."','".$codigo."',NULL,'','0','1','0','perfil.jpg', '1')");		
			}else{
				$this->consultar("INSERT INTO usuario VALUES('".$nombre."','".$username."','".$email."','".$contrasena."','".$codigo."','".$curso."','','0','1','0','perfil.jpg', '1')");	
			}
			$this->desconectar();
		}

		/**
		*	Método que se encarga de crear la consulta para loguear un usuario en la Aplicación
		*	@param $username - Username del usuario a loguear
		*	@param $contrasena -Contraseña encriptada del usuario a loguear
		*	@return El nombre del usuario si este se conecto de manera correcta, de lo contrario retorna false
		*/
		public function login($username, $contrasena)
		{
			$this->conectar("localhost", "root", "", "alangame");
			$aux=$this->consultar("SELECT username FROM usuario WHERE username='".$username."' AND contrasenaSH1='".$contrasena."'");
			$this->desconectar();
			$cont=0;
			$nombre="";

			while($fila=mysqli_fetch_array($aux)){
				$nombre=$fila[0];
				$cont++;
			}

			if($cont==1){
				return $nombre;
			}else{
				return false;
			}
		}

		/**
		*	Método que se encarga de consultar los datos de un usuario
		*	@param $username - Username del usuario 
		*	@return Un array con todos los datos del usuario si la consulta se realizo de manera correcta, de lo contrario un boolean
		*/
		public function obtenerDatos($username)
		{
			$this->conectar("localhost", "root", "", "alangame");
			$aux=$this->consultar("SELECT * FROM usuario WHERE username='".$username."'");
			$this->desconectar();
			$cont=0;
			$datos=array();

			while($fila=mysqli_fetch_array($aux)){
				$datos = $fila;
				$cont++;
			}

			if($cont==1){
				return $datos;
			}else{
				return false;
			}
		}

		/**
		*	Método que se encarga de actualizar los datos de un usuario
		*	@param $username - Username del usuario 
		*	@param $nombre - Nuevo nombre de usuario
		*	@param $contraseña - Nueva contraseña del usuario
		*	@param $curso - Nuevo curso al que pertenece el usuario
		*	@param $descripción - Nueva descripción del usuario 
		*	@param $rutaImagen - Nueva imagen de perfil
		*	@param $portada - Nuevo idntificador de portada
		*/
		public function actualizarDatos($username, $nombre, $contrasena, $curso, $descripcion, $rutaImagen, $portada)
		{
			$this->conectar("localhost", "root", "", "alangame");
			$aux=$this->consultar("UPDATE usuario SET nombre='".$nombre."', contrasenaSH1='".$contrasena."', nombreCurso='".$curso."' descripcion='".$descripcion."', fotoPerfil='".$rutaimagen."', fotoPortada='".$portada."' WHERE username='".$username."'");
			$this->desconectar();
		}

		/**
		*	Método que se encarga de registrar las medallas que halla ganado un usuario
		*	@param $username - Username del usuario 
		*	@param $logro - Logro de la nueva medalla que gano el usuario
		*/
		public function registrarMedalla($username, $logro)
		{
			$this->conectar("localhost", "root", "", "alangame");
			$this->consultar("INSERT INTO usuario-medalla VALUES('".$username."','".$logro."')");
			$this->desconectar();
		}

		/**
		*	Método que lista todas las medallas que puede ganar un usuario
		*	@return Una matriz donde cada fila es una medalla(logro, tipo)
		*/
		public function listarMedallas()
		{
			$this->conectar("localhost", "root", "", "alangame");
			$aux=$this->consultar("SELECT * FROM medalla");
			$this->desconectar();
			$datos=array();

			while($fila=mysqli_fetch_array($aux)){
				array_push($datos, $fila);
			}

			return $datos;
		}

		/**
		*	Método que lista todas las medallas que tiene un usuario
		*	@param $username - Nombre del usuario
		*	@return Un array donde cada posición es una medalla obtenida por el usuario, en caso de no haber medallas retorna false
		*/
		public function listarMedallasUsuario($username)
		{
			$this->conectar("localhost", "root", "", "alangame");
			$aux=$this->consultar("SELECT logro FROM usuario-medalla WHERE username='".$username."'");
			$this->desconectar();
			$datos=array();

			while($fila=mysqli_fetch_array($aux)){
				array_push($datos, $fila[0]);
			}

			if(count($datos)>0){
				return $datos;
			}else{
				return false;
			}
		}

		
	}
	
?>