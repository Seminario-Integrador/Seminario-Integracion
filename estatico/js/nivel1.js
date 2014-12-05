var espacio;
var tablero;
leerJSON('http://localhost/integrador/Seminario-Integracion/index.php?JSON=1');
var fondo= {
	imagenURL: "estatico/img/juego/nivel1.png",
	imagenOK: false
};

var letreros={
	imagenL1DesactivoURL: "estatico/img/juego/letreroInactivo1.png",
	imagenL1DesactivoOK: false,
	imagenL1ActivoURL: "estatico/img/juego/letreroActivo1.png",
	imagenL1ActivoOK: false,
	imagenL2DesactivoURL: "estatico/img/juego/letreroInactivo2.png",
	imagenL2DesactivoOK: false,
	imagenL2ActivoURL: "estatico/img/juego/letreroActivo2.png",
	imagenL2ActivoOK: false,
	imagenL3DesactivoURL: "estatico/img/juego/letreroInactivo3.png",
	imagenL3DesactivoOK: false,
	imagenL3ActivoURL: "estatico/img/juego/letreroActivo3.png",
	imagenL3ActivoOK: false,
	imagenL4DesactivoURL: "estatico/img/juego/letreroInactivo4.png",
	imagenL4DesactivoOK: false,
	imagenL4ActivoURL: "estatico/img/juego/letreroActivo4.png",
	imagenL4ActivoOK: false,
	imagenL5DesactivoURL: "estatico/img/juego/letreroInactivo5.png",
	imagenL5DesactivoOK: false,
	imagenL5ActivoURL: "estatico/img/juego/letreroActivo5.png",
	imagenL5ActivoOK: false
};

//CAMBIAR LOS CONFIRMAR OJO
function inicio () {
	espacio = document.getElementById("campo");
	tablero = espacio.getContext("2d");
	obtenerCoordenadas();
	fondo.imagen = new Image();
	fondo.imagen.src = fondo.imagenURL;
	fondo.imagen.onload = confirmarFondo;
	letreros.imagenL1Desactivo = new Image();
	letreros.imagenL1Activo = new Image();
	letreros.imagenL1Desactivo.src = letreros.imagenL1DesactivoURL;
	letreros.imagenL1Activo.src = letreros.imagenL1ActivoURL;
	letreros.imagenL1Activo.onload = confirmarCastilloActivo;
	letreros.imagenL1Desactivo.onload = confirmarCastilloDesactivo;

	letreros.imagenL2Desactivo = new Image();
	letreros.imagenL2Activo = new Image();
	letreros.imagenL2Desactivo.src = letreros.imagenL2DesactivoURL;
	letreros.imagenL2Activo.src = letreros.imagenL2ActivoURL;
	letreros.imagenL2Activo.onload = confirmarCastilloActivo;
	letreros.imagenL2Desactivo.onload = confirmarCastilloDesactivo;
	
	letreros.imagenL3Desactivo = new Image();
	letreros.imagenL3Activo = new Image();
	letreros.imagenL3Desactivo.src = letreros.imagenL3DesactivoURL;
	letreros.imagenL3Activo.src = letreros.imagenL3ActivoURL;
	letreros.imagenL3Activo.onload = confirmarCastilloActivo;
	letreros.imagenL3Desactivo.onload = confirmarCastilloDesactivo;
	
	letreros.imagenL4Desactivo = new Image();
	letreros.imagenL4Activo = new Image();
	letreros.imagenL4Desactivo.src = letreros.imagenL4DesactivoURL;
	letreros.imagenL4Activo.src = letreros.imagenL4ActivoURL;
	letreros.imagenL4Activo.onload = confirmarCastilloActivo;
	letreros.imagenL4Desactivo.onload = confirmarCastilloDesactivo;
	
	letreros.imagenL5Desactivo = new Image();
	letreros.imagenL5Activo = new Image();
	letreros.imagenL5Desactivo.src = letreros.imagenL5DesactivoURL;
	letreros.imagenL5Activo.src = letreros.imagenL5ActivoURL;
	letreros.imagenL5Activo.onload = confirmarCastilloActivo;
	letreros.imagenL5Desactivo.onload = confirmarCastilloDesactivo;
	
	clicPrincipal();
}

