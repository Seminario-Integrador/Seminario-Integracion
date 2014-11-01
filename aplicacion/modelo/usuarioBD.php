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
			$aux=$this->consultar("SELECT nombre FROM usuario WHERE nombre='".$nombre."' AND contrasenaSH1='".$contraseña."'");
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
	}

	
	
?>