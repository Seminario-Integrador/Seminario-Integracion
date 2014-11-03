<?php
	class Modelo{

		private $conexion;

		/**
		*	Método que se encarga de realizar la conexión a la Base de Datos
		*	@param $host - Nombre del servidor de Base de Datos
		*	@param $usuario - Nombre del usuario root
		*	@param $contrasena - Contraseña del usuario root
		*	@param $base - Nombre de la base de datos
		*/
		public function conectar($host, $usuario, $contrasena, $base)
		{
			$this->conexion = mysqli_connect($host,$usuario,$contrasena,$base) or die(mysql_error($conexion));
		}

		/**
		*	Método que se encarga de cerrar la conexión con la Base de Datos.
		*/
		public function desconectar()
		{
			mysqli_close($this->conexion);
		}

		/**
		*	Método que se encarga de realizar una operación en alguna tabla de la Base de Datos(Inserción, Borrado, Eliminación, Actualización).
		*	@param $sql - Revise un String con la operación a Realizar
		*	@return un fetch_array o un boolean dependiendo de la consulta realizada
		*/
		public function consultar($sql)
		{
			return mysqli_query($this->conexion,$sql);
		}

		public function obtenerRanking()
		{
			$this->conectar("localhost", "root", "", "alangame");
			$aux=$this->consultar("SELECT username, nivel, puntaje FROM usuario ORDER BY puntaje DESC LIMIT 10");
			$this->desconectar();

			$datos=array();
			while($fila=mysqli_fetch_array($aux)){
				array_push($datos, $fila);
			}

			return $datos;
		}
		
	}
?>