<?php
	require "aplicacion/modelo/modelo.php";

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
			$this->consultar("INSERT INTO usuario VALUES('".$nombre."','".$username."','".$email."','".$contrasena."','".$codigo."',".$curso."','','0','1','0','perfil.jpg', '1')");
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
			$aux=$this->consultar("SELECT * FROM usuario WHERE $username='".$username."'");
			$this->desconectar();
			$cont=0;
			$datos=array();

			while($fila=mysqli_fetch_array($aux)){
				foreach($fila as $dato){
					array_push($datos, $dato);
				}
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
		*	@param $descripción - Nueva descripción del usuario 
		*	@param $rutaImagen - Nueva imagen de perfil
		*	@param $portada - Nuevo idntificador de portada
		*/
		public function actualizarDatos($username, $nombre, $contrasena, $descripcion, $rutaImagen, $portada)
		{
			$this->conectar("localhost", "root", "", "alangame");
			$aux=$this->consultar("UPDATE usuario SET nombre='".$nombre."', contrasenaSH1='".$contrasena."', descripcion='".$descripcion."', fotoPerfil='".$rutaimagen."', fotoPortada='".$portada."' WHERE username='".$username."'");
			$this->desconectar();
		}

		
	}
	
?>