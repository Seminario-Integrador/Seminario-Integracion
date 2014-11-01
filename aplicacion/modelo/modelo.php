<?php
	class Modelo{
		private $conexion;
		public function conectar($host, $usuario, $contrasena, $base)
		{
			$this->conexion = mysqli_connect($host,$usuario,$contrasena,$base) or die(mysql_error($conexion));
		}
		public function desconectar()
		{
			mysqli_close($this->conexion);
		}
		public function consultar($sql)
		{
			return mysqli_query($this->conexion,$sql);
		}
		
	}
?>