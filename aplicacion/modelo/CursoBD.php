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
			$this->consultar("INSERT INTO curso VALUES('".$nombreCurso."','".$username."')");
			$this->desconectar();
		}

		/**
		*	Método que permite listar todos los cursos existentes
		*	@return un array con el nombre de los cursos existentes o un false en caso de que no hallan cursos registrados
		*/
		public function listarCursos(){}
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

		 
		/**
		*	Método que permite actualizar el docente que dirige un curso
		*	@param $username - Username del docente 
		*	@param $nombre - Nombre del curso que se actualizara
		*/
		public function actualizarDatos($username, $nombreCurso)
		{
			$this->conectar("localhost", "root", "", "alangame");
			$aux=$this->consultar("UPDATE curso SET usernameD='".$username."' WHERE nombreCurso='".$nombreCurso."'");
			$this->desconectar();
		}

	}
	
?>