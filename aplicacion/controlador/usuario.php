<?php
	require "aplicacion/controlador/controlador.php";
	/**
	* 	Clase que realiza las labores del usuario
	* 	@author Gerson Lázaro - Melissa Delgado
	*/
	class Usuario
	{
		private $codigo;
		private $nombre;
		private $privilegio;
		private $descripcion;
		private $contrasenaSHA;
		private $puntaje;
		private $medalla;


		public function Usuario($codigo, $nombre, $contrasena, $privilegio)
		{
			$this->codigo = $codigo;
			$this->nombre = $nombre;
			$this->privilegio = $privilegio;
			$this->descripcion = "";
			$this->contrasenaSHA = $contrasena;
			$this->puntaje = 0;
			$this->medalla = new array();
		}
	}
?>