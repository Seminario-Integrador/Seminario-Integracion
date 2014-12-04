
var tablero;
var direccion;
var valores;
leerJSON('http://localhost/integrador/Seminario-Integracion/index.php?JSON=1');
var fondo= {
	imagenURL: "estatico/img/juego/mapa.png",
	imagenOK: false
};
var castillos= {
	imagenDesactivoURL: "estatico/img/juego/castilloDesactivo75x70.png",
	imagenDesactivoOK: false,
	imagenActivoURL: "estatico/img/juego/castilloActivo75x70.png",
	imagenActivoOK: false

}

function inicio () {
	var espacio = document.getElementById("campo");
	tablero = espacio.getContext("2d");
	fondo.imagen = new Image();
	fondo.imagen.src = fondo.imagenURL;
	fondo.imagen.onload = confirmarFondo;
	castillos.imagenDesactivo = new Image();
	castillos.imagenActivo = new Image();
	castillos.imagenDesactivo.src = castillos.imagenDesactivoURL;
	castillos.imagenActivo.src = castillos.imagenActivoURL;
	castillos.imagenActivo.onload = confirmarCastilloActivo;
	castillos.imagenDesactivo.onload = confirmarCastilloDesactivo; 
}

function confirmarFondo(){
	fondo.imagenOK=true;
	dibujar();
}
function confirmarCastilloActivo () {
	castillos.imagenActivoOK = true;
	dibujar();
}
function confirmarCastilloDesactivo () {
	castillos.imagenDesactivoOK = true;
	dibujar();
}

inicio();

function dibujar(){
	if(fondo.imagenOK){
		tablero.drawImage(fondo.imagen, 0, 0);	
	}
	if(castillos.imagenDesactivoOK){
		if(valores["nivel"]==1){
			tablero.drawImage(castillos.imagenActivo, 24,33);
			tablero.drawImage(castillos.imagenDesactivo, 57,57);
			tablero.drawImage(castillos.imagenDesactivo, 24,33);
			tablero.drawImage(castillos.imagenDesactivo, 24,33);
			tablero.drawImage(castillos.imagenDesactivo, 24,33);
			tablero.drawImage(castillos.imagenDesactivo, 24,33);
			tablero.drawImage(castillos.imagenDesactivo, 24,33);
		}
	}	
}

function leerJSON (url) {
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
	    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
	        valores = JSON.parse(xmlhttp.responseText)[0];
	    }
	}
	xmlhttp.open("GET", url, true);
	xmlhttp.send();
}

