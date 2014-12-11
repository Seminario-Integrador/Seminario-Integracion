<?php

	/**
 	* .............................................
 	* UNIVERSIDAD  FRANCISCO  DE  PAULA  SANTANDER
 	*    PROGRAMA  DE  INGENIERIA  DE  SISTEMAS
 	*      ALAN Y EL MISTERIOSO REINO DE ENIAC
 	*             SAN JOSE DE CUCUTA-2014
	 * ............................................
 	*/

 	/**
	* Archivo de instalación de la Base de Datos
	* @author Gerson Yesid Lázaro Carrillo 1150972
	* @author Angie Melissa Delgado León 1150990
	*/

	$conexion = mysqli_connect("localhost","root","") or die(("Error " . mysqli_error($conexion)));
	$crearBase="CREATE DATABASE alangame";
	$result = mysqli_query($conexion, $crearBase);
	if($result){
		echo "La base de Datos ha sido creada exitosamente :3<br>";
	}else{
		echo "Algo ha fallado. Intentalo de nuevo :(<br>";
	}

	mysqli_close($conexion);
	$conexion = mysqli_connect("localhost","root","","alangame") or die(("Error " . mysqli_error($conexion)));

	if(mysqli_query($conexion, "CREATE TABLE docente(
			nombre varchar(55) NOT NULL,
			usernameD varchar(20) NOT NULL,
			correo varchar(60) NOT NULL,
			contrasenaSH1 varchar(40) NOT NULL,
			codigoDocente varchar(8) NOT NULL,
			PRIMARY KEY (usernameD)
		)")){
		echo "Se ha creado la tabla Docente exitosamente<br>";
	}
	if(mysqli_query($conexion, "CREATE TABLE curso(
			nombreCurso varchar(30) NOT NULL,	
			usernameD varchar(20) NOT NULL,
			PRIMARY KEY (nombreCurso),
			FOREIGN KEY(usernameD) REFERENCES docente(usernameD)
			ON UPDATE CASCADE
			ON DELETE CASCADE
		)")){
		echo "Se ha creado la tabla Curso exitosamente<br>";
	}

	if(mysqli_query($conexion, "CREATE TABLE usuario(
			nombre varchar(90) NOT NULL,
			username varchar(20) NOT NULL,
			correo varchar(60) NOT NULL UNIQUE,
			contrasenaSH1 varchar(40) NOT NULL,
			codigoUsuario varchar(8),
			nombreCurso varchar(30),
			descripcion varchar (140),
			puntaje	int NOT NULL,
			nivel int NOT NULL,
			subnivel int NOT NULL,
			fotoPerfil varchar(140) NOT NULL,
			fotoPortada int NOT NULL,
			PRIMARY KEY (username), 
			FOREIGN KEY (nombreCurso) REFERENCES curso(nombreCurso)
			ON UPDATE SET NULL
			ON DELETE SET NULL
		)")){
		echo "Se ha creado la tabla Usuario exitosamente<br>";
	}					 
	if(mysqli_query($conexion, "CREATE TABLE medalla(
			logro varchar(55) NOT NULL,
			tipo varchar(55) NOT NULL,
			PRIMARY KEY (logro)
		)")){
		echo "Se ha creado la tabla Medalla exitosamente<br>";
	}
	if(mysqli_query($conexion, "CREATE TABLE usuarioMedalla(
			username varchar(20) NOT NULL,
			logro varchar(55) NOT NULL,
			PRIMARY KEY (username, logro),
			FOREIGN KEY(username) REFERENCES usuario(username)
			ON UPDATE CASCADE
			ON DELETE CASCADE,
			FOREIGN KEY(logro) REFERENCES medalla(logro)
			ON UPDATE CASCADE
			ON DELETE CASCADE
		)")){
		echo "Se ha creado la tabla Usuario-Medalla exitosamente<br>";
	}	
?>