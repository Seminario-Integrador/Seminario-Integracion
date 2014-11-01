<?php
	require "aplicacion/modelo/modelo.php";

	class UsuarioBD extends Modelo{

		public function registrar($nombre, $codigo, $email, $contraseña)
		{
			$this->conectar("localhost", "root", "", "alangame");
			$this->consulta("INSERT INTO usuario VALUES('".$usuario."','".$email."','".$contraseña."','".$codigo."','','0','1','0')");
			$this->desconectar();
		}

		public function login($value='')
		{
			# code...
		}
	}

	//retornar array  posicion (nombre) o false
	
?>