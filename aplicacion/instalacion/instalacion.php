<?php
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
			contrasenaSH1 varchar(40) NOT NULL,
			codigoDocente varchar(8) NOT NULL,
			PRIMARY KEY (codigoDocente)
		)")){
		echo "Se ha creado la tabla Docente exitosamente<br>";
	}

	if(mysqli_query($conexion, "CREATE TABLE usuario(
			nombre varchar(90) NOT NULL,
			username varchar(20) NOT NULL,
			correo varchar(60) NOT NULL,
			contrasenaSH1 varchar(40) NOT NULL,
			codigoUsuario varchar(8),
			descripcion varchar (140),
			puntaje	int NOT NULL,
			nivel int NOT NULL,
			subnivel int NOT NULL,
			fotoPerfil varchar(140) NOT NULL,
			fotoPortada int, NOT NULL,
			PRIMARY KEY (username)
		)")){
		echo "Se ha creado la tabla Usuario exitosamente<br>";
	}

	if(mysqli_query($conexion, "CREATE TABLE medalla(
			logro varchar(55) NOT NULL,
			tipo varchar(55) NOT NULL,
			username varchar(20) NOT NULL,
			PRIMARY KEY (logro),
			FOREIGN KEY(username) REFERENCES usuario(username)
			ON UPDATE CASCADE
			ON DELETE CASCADE
		)")){
		echo "Se ha creado la tabla Medalla exitosamente<br>";
	}

	if(mysqli_query($conexion, "CREATE TABLE curso(
			nombreCurso varchar(30) NOT NULL,	
			codigoDocente varchar(8),
			username varchar(20) NOT NULL,
			PRIMARY KEY (nombreCurso, username),
			FOREIGN KEY(username) REFERENCES usuario(username)
			ON UPDATE CASCADE
			ON DELETE CASCADE,
			FOREIGN KEY(codigoDocente) REFERENCES docente(codigoDocente)
			ON UPDATE CASCADE
			ON DELETE CASCADE
		)")){
		echo "Se ha creado la tabla Curso exitosamente<br>";
	}
?>