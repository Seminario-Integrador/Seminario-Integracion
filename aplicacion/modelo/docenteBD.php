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
	* Clase encargada del manejo de los docentes en la base de datos. Recibe llamados desde el controlador.php
	* Esta clase extiende de la clase modelo
	* @author Gerson Yesid Lázaro Carrillo 1150972
	* @author Angie Melissa Delgado León 1150990
	*/
	class DocenteBD extends Modelo{

		/**
		*	Método que se encarga de crear la consulta para guardar un nuevo docente en la Base de Datos.
		*	@param $username - Username del docente a registrar
		*	@param $nombre - Nombre del docente a registrar
		*	@param $codigo - Codigo del docente a registrar
		*	@param $email - Email del docente a registrar
		*	@param $contraseña - Contraseña del docente a registrar
		*/
		public function registrar($username, $nombre, $codigo, $email, $contrasena)
		{
			$this->conectar();
			$this->consultar("INSERT INTO docente VALUES('".$nombre."','".$username."','".$email."','".$contrasena."','".$codigo."')");
			$this->desconectar();
		}

		/**
		*	Método que se encarga de crear la consulta para loguear un docente en la Aplicación
		*	@param $username - Username del docente a loguear
		*	@param $contrasena -Contraseña encriptada del docente a loguear
		*	@return El nombre del docente si este se conecto de manera correcta, de lo contrario retorna false
		*/
		public function login($username, $contrasena)
		{
			$this->conectar();
			$aux=$this->consultar("SELECT usernameD FROM docente WHERE usernameD='".$username."' AND contrasenaSH1='".$contrasena."'");
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
		*	Método que se encarga de consultar los datos de un docente
		*	@param $username - Username del docente 
		*	@return Un array con todos los datos del docente si la consulta se realizo de manera correcta, de lo contrario un boolean
		*/
		public function obtenerDatos($username)
		{
			$this->conectar();
			$aux=$this->consultar("SELECT * FROM docente WHERE $username='".$username."'");
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
		*	Método que se encarga de actualizar los datos de un docente
		*	@param $username - Username del docente 
		*	@param $nombre - Nuevo nombre de docente
		*	@param $contraseña - Nueva contraseña del docente
		*/
		public function actualizarDatos($username, $nombre, $contrasena)
		{
			$this->conectar();
			$aux=$this->consultar("UPDATE docente SET nombre='".$nombre."', contrasenaSH1='".$contrasena."' WHERE username='".$username."'");
			$this->desconectar();
		}


		/**
		*	Método que se encarga de listar el codigo, nivel y puntaje de todos los estudiantes que pertenezcan al grupo que dirige el docente
		*	@param $username - Username del docente
		*	@return Una matriz con los datos de los estudiantes, o un boolean en caso de que no halla estudiantes.
		*/
		public function listarEstudiantes($username)
		{
			$this->conectar();
			$aux=$this->consultar("SELECT codigoUsario e, nivel e, subnivel e, puntaje e FROM usuario e, curso c WHERE c.username='".$username."' AND e.nombreCurso=c.nombreCurso");
			$this->desconectar();
			$cont=0;
			$datos=array();

			while($fila=mysqli_fetch_array($aux)){
				array_push($datos, $fila);
				$cont++;
			}

			if($cont>0){
				return $datos;
			}else{
				return false;
			}
		}
	}
	
?>