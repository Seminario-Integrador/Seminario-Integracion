<?php

	/**
 	* .............................................
 	* UNIVERSIDAD  FRANCISCO  DE  PAULA  SANTANDER
 	*    PROGRAMA  DE  INGENIERIA  DE  SISTEMAS
 	*      ALAN Y EL MISTERIOSO REINO DE ENIAC
 	*             SAN JOSE DE CUCUTA-2014
	 * ............................................
 	*/

	require_once "aplicacion/modelo/modelo.php";

	/**
	* Clase encargada del manejo de los cursos en la base de datos. Recibe llamados desde el controlador.php
	* Esta clase extiende de la clase modelo
	* @author Gerson Yesid Lázaro Carrillo 1150972
	* @author Angie Melissa Delgado León 1150990
	*/
	class CursoBD extends Modelo{


		/**
		*	Método que se encarga de crear la consulta para guardar un nuevo curso en la Base de Datos.
		*	@param $nombreCurso - Nombre del curso a registrar
		*	@param $username - Username del docente que dirigiré el curso a registrar
		*/
		public function registrar($nombreCurso, $username)
		{
			$this->conectar();
			$this->consultar("INSERT INTO curso VALUES('".$nombreCurso."','".$username."')");
			$this->desconectar();
		}

		 
		/**
		*	Método que permite actualizar el docente que dirige un curso
		*	@param $username - Username del docente 
		*	@param $nombre - Nombre del curso que se actualizara
		*/
		public function actualizarDatos($username, $nombreCurso)
		{
			$this->conectar();
			$aux=$this->consultar("UPDATE curso SET usernameD='".$username."' WHERE nombreCurso='".$nombreCurso."'");
			$this->desconectar();
		}


		/**
		* Método que se encarga de listar los cursos que el docente ha creado
		* @param username - nombre del docente del que se listan los cursos
		* @return vector de cursos
		*/
		public function listarCursos($username)
		{
			$this->conectar();
			$aux = $this->consultar("SELECT nombreCurso FROM curso WHERE usernameD='".$username."'");
			$retorno = array();
			while($fila=mysqli_fetch_array($aux)){
				array_push($retorno, $fila["nombreCurso"]);
			}
			$this->desconectar();
			return $retorno;
		}


		/**
		* 	Método que obtiene todos los alumnos en un curso
		*	@param $curso - Curso del cual se verán los alumnos
		*	@return Un array con los datos de los alumnos del curso. Este contiene su nombre, nivel, subnivel y puntaje
		*/
		public function obtenerAlumnos($curso)
		{
			$this->conectar();
			$aux = $this->consultar("SELECT u.nombre, u.nivel, u.subnivel, u.puntaje FROM usuario u, curso c WHERE c.nombreCurso='".$curso."' AND c.nombreCurso=u.nombreCurso");
			$retorno = array();
			while($fila=mysqli_fetch_array($aux)){
				array_push($retorno, $fila);
			}
			$this->desconectar();
			return $retorno;
		}


		/**
		*	Lista todos los cursos existentes en la plataforma, para usarse en el registro
		*	@return Un array con los cursos
		*/

		public function listaDeCursos()
		{
			$this->conectar();
			$aux=$this->consultar("SELECT nombreCurso FROM curso");
			$cursos=array();
			while($fila=mysqli_fetch_array($aux)){
				array_push($cursos, $fila[0]);
			}
			$this->desconectar();
			if(count($cursos)>0){
				return $cursos;
			}else{
				return false;
			}
		}
	}
	
?>