<?php
	require "aplicacion/modelo/modelo.php";

	class UsuarioBD extends Modelo{

		public function registrar($nombre, $codigo, $email, $contrasena)
		{
			$this->conectar("localhost", "root", "", "alangame");
			$this->consultar("INSERT INTO usuario VALUES('".$nombre."','".$email."','".$contrasena."','".$codigo."','','0','1','0')");
			$this->desconectar();
		}

		public function login($nombre, $contrasena)
		{
			$this->conectar("localhost", "root", "", "alangame");
			$aux=$this->consulta("SELECT nombre FROM usuario WHERE nombre");

			/*
			nombre varchar(55) NOT NULL,
			correo varchar(60) NOT NULL,
			contrasenaSH1 varchar(40) NOT NULL,
			codigoUsuario varchar(8) NOT NULL,
			descripcion varchar (140),
			puntaje	int NOT NULL,
			nivel int NOT NULL,
			subnivel int NOT NULL,*/
		}
	}

	//retornar array  posicion (nombre) o false
	
?>