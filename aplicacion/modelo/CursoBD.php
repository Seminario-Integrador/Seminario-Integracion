<?php
	require "aplicacion/modelo/modelo.php";

	class CursoBD extends Modelo{

		/**
		*	Método que se encarga de crear la consulta para guardar un nuevo curso en la Base de Datos.
		*	@param $nombreCurso - Nombre del curso a registrar
		*	@param $username - Username del docente que dirigiré el curso a registrar
		*/
		public function registrar($nombreCurso, $username)
		{
			$this->conectar("localhost", "root", "", "alangame");
			$this->consultar("INSERT INTO curso VALUES('".$nombreCurso."','".$username."'");
			$this->desconectar();

			/*nombreCurso varchar(30) NOT NULL,	
			username varchar(20) NOT NULL,*/
		}

		//ARREGLAR ESTO!!!! 
		/**
		*	Método que se encarga de actualizar los datos de un docente
		*	@param $username - Username del docente 
		*	@param $nombre - Nuevo nombre de docente
		*	@param $contraseña - Nueva contraseña del docente
		*/
		public function actualizarDatos($username, $nombre, $contrasena)
		{
			$this->conectar("localhost", "root", "", "alangame");
			$aux=$this->consultar("UPDATE docente SET nombre='".$nombre."', contrasenaSH1='".$contrasena."' WHERE username='".$username."'");
			$this->desconectar();
		}

	}
	
?>