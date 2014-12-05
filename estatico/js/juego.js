var espacio;
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
var posicionCastillos= {
	castillo1X: 230,
	castillo1Y: 77,
	castillo2X: 150,
	castillo2Y: 250,
	castillo3X: 210,
	castillo3Y: 430,
	castillo4X: 650,
	castillo4Y: 75,
	castillo5X: 600,
	castillo5Y: 200,
	castillo6X: 730,
	castillo6Y: 320,
	castillo7X: 1000,
	castillo7Y: 150,
}
var posicionXCastillos = [0, 230, 150, 210, 650, 600, 730, 1000];
var posicionYCastillos = [0, 77, 250, 430, 75, 200, 320, 150]

function inicio () {
	espacio = document.getElementById("campo");
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
	clicPrincipal();
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

function dibujar(){
	if(fondo.imagenOK){
		tablero.drawImage(fondo.imagen, 0, 0);	
	}
	if(castillos.imagenDesactivoOK){
		if(valores["nivel"]==1){
			tablero.drawImage(castillos.imagenActivo,  posicionCastillos.castillo1X, posicionCastillos.castillo1Y);
			tablero.drawImage(castillos.imagenDesactivo,  posicionCastillos.castillo2X, posicionCastillos.castillo2Y);
			tablero.drawImage(castillos.imagenDesactivo,  posicionCastillos.castillo3X, posicionCastillos.castillo3Y);
			tablero.drawImage(castillos.imagenDesactivo,  posicionCastillos.castillo4X, posicionCastillos.castillo4Y);
			tablero.drawImage(castillos.imagenDesactivo,  posicionCastillos.castillo5X, posicionCastillos.castillo5Y);
			tablero.drawImage(castillos.imagenDesactivo,  posicionCastillos.castillo6X, posicionCastillos.castillo6Y);
			tablero.drawImage(castillos.imagenDesactivo,  posicionCastillos.castillo7X, posicionCastillos.castillo7Y);
		}else if(valores["nivel"]==2){
			tablero.drawImage(castillos.imagenActivo,  posicionCastillos.castillo1X, posicionCastillos.castillo1Y);
			tablero.drawImage(castillos.imagenActivo,  posicionCastillos.castillo2X, posicionCastillos.castillo2Y);
			tablero.drawImage(castillos.imagenDesactivo,  posicionCastillos.castillo3X, posicionCastillos.castillo3Y);
			tablero.drawImage(castillos.imagenDesactivo,  posicionCastillos.castillo4X, posicionCastillos.castillo4Y);
			tablero.drawImage(castillos.imagenDesactivo,  posicionCastillos.castillo5X, posicionCastillos.castillo5Y);
			tablero.drawImage(castillos.imagenDesactivo,  posicionCastillos.castillo6X, posicionCastillos.castillo6Y);
			tablero.drawImage(castillos.imagenDesactivo,  posicionCastillos.castillo7X, posicionCastillos.castillo7Y);
		}else if(valores["nivel"]==3){
			tablero.drawImage(castillos.imagenActivo, posicionCastillos.castillo1X, posicionCastillos.castillo1Y);
			tablero.drawImage(castillos.imagenActivo, posicionCastillos.castillo2X, posicionCastillos.castillo2Y);
			tablero.drawImage(castillos.imagenActivo, posicionCastillos.castillo3X, posicionCastillos.castillo3Y);
			tablero.drawImage(castillos.imagenDesactivo,posicionCastillos.castillo4X, posicionCastillos.castillo4Y);
			tablero.drawImage(castillos.imagenDesactivo, posicionCastillos.castillo5X, posicionCastillos.castillo5Y);
			tablero.drawImage(castillos.imagenDesactivo, posicionCastillos.castillo6X, posicionCastillos.castillo6Y);
			tablero.drawImage(castillos.imagenDesactivo, posicionCastillos.castillo7X, posicionCastillos.castillo7Y);
		}else if(valores["nivel"]==4){
			tablero.drawImage(castillos.imagenActivo, posicionCastillos.castillo1X, posicionCastillos.castillo1Y);
			tablero.drawImage(castillos.imagenActivo, posicionCastillos.castillo2X, posicionCastillos.castillo2Y);
			tablero.drawImage(castillos.imagenActivo, posicionCastillos.castillo3X, posicionCastillos.castillo3Y);
			tablero.drawImage(castillos.imagenActivo,posicionCastillos.castillo4X, posicionCastillos.castillo4Y);
			tablero.drawImage(castillos.imagenDesactivo, posicionCastillos.castillo5X, posicionCastillos.castillo5Y);
			tablero.drawImage(castillos.imagenDesactivo, posicionCastillos.castillo6X, posicionCastillos.castillo6Y);
			tablero.drawImage(castillos.imagenDesactivo, posicionCastillos.castillo7X, posicionCastillos.castillo7Y);
		}else if(valores["nivel"]==5){
			tablero.drawImage(castillos.imagenActivo, posicionCastillos.castillo1X, posicionCastillos.castillo1Y);
			tablero.drawImage(castillos.imagenActivo, posicionCastillos.castillo2X, posicionCastillos.castillo2Y);
			tablero.drawImage(castillos.imagenActivo, posicionCastillos.castillo3X, posicionCastillos.castillo3Y);
			tablero.drawImage(castillos.imagenActivo,posicionCastillos.castillo4X, posicionCastillos.castillo4Y);
			tablero.drawImage(castillos.imagenActivo, posicionCastillos.castillo5X, posicionCastillos.castillo5Y);
			tablero.drawImage(castillos.imagenDesactivo, posicionCastillos.castillo6X, posicionCastillos.castillo6Y);
			tablero.drawImage(castillos.imagenDesactivo, posicionCastillos.castillo7X, posicionCastillos.castillo7Y);
		}else if(valores["nivel"]==6){
			tablero.drawImage(castillos.imagenActivo, posicionCastillos.castillo1X, posicionCastillos.castillo1Y);
			tablero.drawImage(castillos.imagenActivo, posicionCastillos.castillo2X, posicionCastillos.castillo1Y);
			tablero.drawImage(castillos.imagenActivo, posicionCastillos.castillo3X, posicionCastillos.castillo1Y);
			tablero.drawImage(castillos.imagenActivo,posicionCastillos.castillo4X, posicionCastillos.castillo1Y);
			tablero.drawImage(castillos.imagenActivo, posicionCastillos.castillo5X, posicionCastillos.castillo1Y);
			tablero.drawImage(castillos.imagenActivo, posicionCastillos.castillo6X, posicionCastillos.castillo1Y);
			tablero.drawImage(castillos.imagenDesactivo, posicionCastillos.castillo7X, posicionCastillos.castillo1Y);
		}else if(valores["nivel"]==7){
			tablero.drawImage(castillos.imagenActivo, posicionCastillos.castillo1X, posicionCastillos.castillo1Y);
			tablero.drawImage(castillos.imagenActivo, posicionCastillos.castillo2X, posicionCastillos.castillo2Y);
			tablero.drawImage(castillos.imagenActivo, posicionCastillos.castillo3X, posicionCastillos.castillo3Y);
			tablero.drawImage(castillos.imagenActivo,posicionCastillos.castillo4X, posicionCastillos.castillo4Y);
			tablero.drawImage(castillos.imagenActivo, posicionCastillos.castillo5X, posicionCastillos.castillo5Y);
			tablero.drawImage(castillos.imagenActivo, posicionCastillos.castillo6X, posicionCastillos.castillo6Y);
			tablero.drawImage(castillos.imagenActivo, posicionCastillos.castillo7X, posicionCastillos.castillo7Y);
		}
	}	
}
function clicPrincipal(){
	espacio.addEventListener("click",function(e){
		console.log(e.clientX-espacio.offsetLeft);
		
		
	});
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
	
	setTimeout(inicio, 200);
}

