<?php
	require "aplicacion/modelo/modelo.php";

	class UsuarioBD extends Modelo{

		public function registrar($nombre, $codigo, $email, $contrasena)
		{
			$this->conectar("localhost", "root", "", "alangame");
			$this->consultar("INSERT INTO usuario VALUES('".$usuario."','".$email."','".$contrasena."','".$codigo."','','0','1','0')");
			$this->desconectar();
		}

		public function login($nombre, $contrasena)
		{
			$this->conectar("localhost", "root", "", "alangame");
			$aux=$this->consulta("SELECT ");
		}
	}

	//retornar array  posicion (nombre) o false
	
?>