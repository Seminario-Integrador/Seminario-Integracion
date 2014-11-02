<?php
	require_once "aplicacion/modelo/modelo.php";

	class CursoBD extends Modelo{

		/**
		*	Método que se encarga de crear la consulta para guardar un nuevo curso en la Base de Datos.
		*	@param $nombreCurso - Nombre del curso a registrar
		*	@param $username - Username del docente que dirigiré el curso a registrar
		*/
		public function registrar($nombreCurso, $username)
		{
			$this->conectar("localhost", "root", "", "alangame");
			$this->consultar("INSERT INTO curso VALUES('".$nombreCurso."','".$username."')");
			$this->desconectar();
		}

		
		public function listarCursos()
		{
			$this->conectar("localhost", "root", "", "alangame");
			$aux=$this->consultar("SELECT nombreCurso FROM curso");
			$this->desconectar();
			$cursos=array();
			while($fila=mysqli_fetch_array($aux)){
				array_push($cursos, $fila[0]);
			}

			if(count($cursos)>0){
				return $cursos;
			}else{
				return false;
			}
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