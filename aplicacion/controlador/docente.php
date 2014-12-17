<?php

	/**
 	* .............................................
 	* UNIVERSIDAD  FRANCISCO  DE  PAULA  SANTANDER
 	*    PROGRAMA  DE  INGENIERIA  DE  SISTEMAS
 	*      ALAN Y EL MISTERIOSO REINO DE ENIAC
 	*             SAN JOSE DE CUCUTA-2014
	 * ............................................
 	*/

	require_once "aplicacion/controlador/controlador.php";
	include_once "aplicacion/modelo/usuarioBD.php";
	include_once "aplicacion/modelo/docenteBD.php";
	include_once "aplicacion/modelo/CursoBD.php";
	include_once "aplicacion/vista/assets.php";


	/**
	* 	Clase que realiza las labores del docente
	* 	@author Gerson Lázaro - Melissa Delgado
	*/
	class Docente extends controlador
	{


		/**
		* Metodo que crea un curso nuevo en la base de datos
		* @param curso - Nombre del curso a agregar
		*/
		public function crearCurso($curso)
		{
			$cursoBD = new cursoBD();
			$cursoBD->registrar($curso, $_SESSION["nombre"]);
		}


		/**
		*	Creacion de nuevos cursos por parte del docente
		*/
		public function crearNuevoCurso()
		{
			$plantilla = $this->leerPlantilla("aplicacion/vista/index.html");
			$barraIzq = $this->leerPlantilla("aplicacion/vista/lateralIzquierdaDocente.html");
			$barraIzq = $this->reemplazar($barraIzq, "{{username}}", $_SESSION["nombre"]);
			$barraIzq = $this->reemplazar($barraIzq, "{{fotoPerfil}}", "docente.png");
			$plantilla = $this->reemplazar($plantilla, "{{lateralIzquierda}}", $barraIzq);
			$superiorDer = $this->leerPlantilla("aplicacion/vista/superiorDerecho.html");
			$barraDer = $this->leerPlantilla("aplicacion/vista/crearCurso.html");
			$barraDer = $this->reemplazar($barraDer, "{{superior}}", $superiorDer);
			$footer = $this->leerPlantilla("aplicacion/vista/footer.html");
			$plantilla = $this->reemplazar($plantilla, "{{lateralDerecha}}", $barraDer);
			$plantilla = $this->reemplazar($plantilla, "{{footer}}", $footer);
			$this->mostrarVista($plantilla);
		}


		/**
		* 	Metodo que permite listar los cursos de un docente, con sus alumnos
		*/
		public function listarCursos()
		{
			$curso = new cursoBD();
			$plantilla = $this->leerPlantilla("aplicacion/vista/index.html");
			$barraIzq = $this->leerPlantilla("aplicacion/vista/lateralIzquierdaDocente.html");
			$barraIzq = $this->reemplazar($barraIzq, "{{username}}", $_SESSION["nombre"]);
			$barraIzq = $this->reemplazar($barraIzq, "{{fotoPerfil}}", "docente.png");
			$plantilla = $this->reemplazar($plantilla, "{{lateralIzquierda}}", $barraIzq);
			$superiorDer = $this->leerPlantilla("aplicacion/vista/superiorDerecho.html");
			$barraDer = $this->leerPlantilla("aplicacion/vista/listaCursos.html");
			$posicion = $this->leerPlantilla("aplicacion/vista/posicionCurso.html");
			$barraDer = $this->reemplazar($barraDer, "{{superior}}", $superiorDer);
			$footer = $this->leerPlantilla("aplicacion/vista/footer.html");
			$cursos = $curso->listarCursos($_SESSION["nombre"]);
			$tablaCurso = $this->leerPlantilla("aplicacion/vista/tablaCurso.html");
			$tablas = "";
			for($i=0;$i<sizeof($cursos);$i++){
				$cursoTemp = $tablaCurso;
				$tabla = "";
				$aux2 = $curso->obtenerAlumnos($cursos[$i]);
				for($j=0;$j<sizeof($aux2);$j++){
					$aux = $posicion;
					$aux = $this->reemplazar($posicion, "{{posicion}}", ($j+1));
					$aux = $this->reemplazar($aux, "{{username}}", $aux2[$j]["nombre"]);
					$aux = $this->reemplazar($aux, "{{nivel}}", $aux2[$j]["nivel"]);
					$aux = $this->reemplazar($aux, "{{subnivel}}", $aux2[$j]["subnivel"]);
					$aux = $this->reemplazar($aux, "{{puntaje}}", $aux2[$j]["puntaje"]);
					$tabla = $aux.$tabla;
				}
				$cursoTemp = $this->reemplazar($cursoTemp, "{{curso-nombre}}", $cursos[$i]);
				$cursoTemp = $this->reemplazar($cursoTemp, "{{tabla}}", $tabla);
				$tablas .= $cursoTemp;
			}
			$barraDer = $this->reemplazar($barraDer, "{{cursos}}", $tablas);
			$plantilla = $this->reemplazar($plantilla, "{{lateralDerecha}}", $barraDer);
			$plantilla = $this->reemplazar($plantilla, "{{footer}}", $footer);
			$this->mostrarVista($plantilla);
		}

		
	}
?>