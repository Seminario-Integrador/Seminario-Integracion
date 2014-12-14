var espacio;
var tablero;
var subnivelActual;
var direccion;
var valores;
var actualx, actualy;
var alanImagenX, alanImagenY, alanTableroX, alanTableroY;
var alanAncho=56;
var alanAlto=85;
var pantalla = "inicio";
var intervaloAvance;
var colaAcciones = [];
var banderaCola= false;
leerJSON('http://localhost/integrador/Seminario-Integracion/index.php?JSON=1');
var fondo= {
	imagenURL: "estatico/img/juego/mapa.png",
	imagenOK: false
};
var castillos= {
	imagenDesactivoURL: "estatico/img/juego/castilloDesactivo75x70.png",
	imagenDesactivoOK: false,
	imagenActivoURL: "estatico/img/juego/castilloActivo75x70.png",
	imagenActivoOK: false,
	imagenAlanURL: "estatico/img/juego/alan.png"
}
var control = {
	imagenInicialURL: "estatico/img/juego/iniciar.png",
	imagenFinalURL:"estatico/img/juego/iniciar2.png"
}

var nivel1 = {
	imagenURL: "estatico/img/juego/nivel1.png",
	imagenOK: false,
	letrero1A: "estatico/img/juego/letreroActivo1.png",
	letrero2A: "estatico/img/juego/letreroActivo2.png",
	letrero3A: "estatico/img/juego/letreroActivo3.png",
	letrero4A: "estatico/img/juego/letreroActivo4.png",
	letrero5A: "estatico/img/juego/letreroActivo5.png",
	letrero1I: "estatico/img/juego/letreroInactivo1.png",
	letrero2I: "estatico/img/juego/letreroInactivo2.png",
	letrero3I: "estatico/img/juego/letreroInactivo3.png",
	letrero4I: "estatico/img/juego/letreroInactivo4.png",
	letrero5I: "estatico/img/juego/letreroInactivo5.png",
	letreros: 0,
	subnivel1URL: "estatico/img/juego/subnivel1-1.png",
	subnivel1OK: false,
	subnivel2URL: "estatico/img/juego/subnivel1-2.png",
	subnivel2OK: false,
	subnivel31URL: "estatico/img/juego/subnivel1-3(1).png",
	subnivel31OK: false,
	subnivel32URL: "estatico/img/juego/subnivel1-3(2).png",
	subnivel32OK: false,
	subnivel33URL: "estatico/img/juego/subnivel1-3(3).png",
	subnivel33OK: false
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
var inicioXNivel1 = [0,292,56,325,656];
var inicioYNivel1 = [0,87,297,330,214];
var finXNivel1 = [191,473,193,490,875];
var finYNivel1 = [158,257,433,489,344];
var espacioNivel1Subnivel1= [
[false, false, true, true, true, false],
[true, false, true, false, true, true],
[true, true, true, true, true, true],
[true, true, true, true, false, true],
[false, true, true, true, false, true]
];
var espacioNivel1Subnivel2= [
[false, true, false, false, false, false, false],
[true, false, true, true, true, true, true],
[true, true, true, true, false, false, false],
[false, false, true, false, true, false, false],
[false, false, true, false, false, false, false]
];
var espacioNivel1Subnivel3= [
[true, true, false, false, false, true, false],
[true, true, false, false, true, true, true],
[true, true, false, true, true, true, true],
[true, true, false, true, true, false, false] 
];
var finales=[ [830, 215],
[850,125], [0,0], [0,0], [0,0]];

function inicio () {
	espacio = document.getElementById("campo");
	tablero = espacio.getContext("2d");
	obtenerCoordenadas();
	fondo.imagen = new Image();
	fondo.imagen.src = fondo.imagenURL;
	fondo.imagen.onload = confirmarFondo;
	castillos.imagenDesactivo = new Image();
	castillos.imagenActivo = new Image();
	castillos.imagenDesactivo.src = castillos.imagenDesactivoURL;
	castillos.imagenAlan = new Image();
	castillos.imagenAlan.src = castillos.imagenAlanURL;
	castillos.imagenActivo.src = castillos.imagenActivoURL;
	castillos.imagenActivo.onload = confirmarCastilloActivo;
	castillos.imagenDesactivo.onload = confirmarCastilloDesactivo;
	nivel1.imagen = new Image();
	nivel1.imagen.src = nivel1.imagenURL;
	nivel1.imagen.onload = confirmarNivel1();
	cargarNivel1();
	clicPrincipal();
}

function obtenerCoordenadas () {
	espacioAux = espacio;
	espacioX=0;
	espacioY=0;
	while (espacioAux.offsetParent) {
	    espacioX += espacioAux.offsetLeft;
		espacioY += espacioAux.offsetTop;
		espacioAux = espacioAux.offsetParent;
	}
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
	if(castillos.imagenDesactivoOK && castillos.imagenActivoOK){
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
function dibujarNivel (i) {
	if(i==1){
		dibujarNivel1();
	}
}

function dibujarSubnivel (i) {
	if(pantalla=="nivel1"){
		if(i==0){
			dibujarNivel1Subnivel1();
		}else if(i==1){
			dibujarNivel1Subnivel2();
		}else if(i==2){
			dibujarNivel1Subnivel3();
		}
	}
}

function dibujarJuego () {
	if(pantalla == "nivel1subnivel1"){
		dibujarJuegoNivel1Subnivel1();
	}else if(pantalla=="nivel1subnivel2"){
		dibujarJuegoNivel1Subnivel2();
	}else if(pantalla=="nivel1subnivel3"){
		dibujarJuegoNivel1Subnivel3();
	}
}

function clicPrincipal(){
	espacio.addEventListener("click",function(e){
		var x = e.clientX-espacioX;
		var y = e.clientY-espacioY+document.body.scrollTop;
		if(pantalla=="inicio"){
			for(i=0;i<8;i++){
				if(x>=posicionXCastillos[i] && x<=posicionXCastillos[i]+75){
					if(y>=posicionYCastillos[i] && y<=posicionYCastillos[i]+70){
						if(i<=valores["nivel"]){
							pantalla = "nivel1";
							dibujarNivel(i);
						}
					}
				}
			}

		}else if(pantalla=="nivel1"){
			for(i=0;i<5;i++){
				if(x>=inicioXNivel1[i] && x<=finXNivel1[i]+75){
					if(y>=inicioYNivel1[i] && y<=finYNivel1[i]+70){
						if(i<=valores["subnivel"]){
							dibujarSubnivel(i);
							if(i==0){
								pantalla = "nivel1subnivel1";
							}else if(i==1){
								pantalla = "nivel1subnivel2";
							}else if(i==2){
								pantalla = "nivel1subnivel3";
							}
						}
					}
				}
			}
			if(x>=926 && x<=1072){
				if(y>=457 && y<=489){
						dibujar();
						pantalla = "inicio";
				}
			}
		}else if(pantalla=="nivel1subnivel1"){	
			if(x>=28 && x<= 218){
				if(y>=431 && y<=486){
					dibujarJuego(i);
					pantalla = "nivel1subnivel1juego";
				}
			}else if(x>=237 && x<= 422){
				if(y>=431 && y<=486){
					dibujarNivel(1);
					pantalla = "nivel1";
				}
			}

		}else if(pantalla=="nivel1subnivel2"){	
			if(x>=28 && x<= 218){
				if(y>=431 && y<=486){
					dibujarJuego(i);
					pantalla = "nivel1subnivel2juego";
				}
			}else if(x>=237 && x<= 422){
				if(y>=431 && y<=486){
					dibujarNivel(1);
					pantalla = "nivel1";
				}
			}
		}else if(pantalla=="nivel1subnivel3"){	
			if(x>=28 && x<= 218){
				if(y>=431 && y<=486){
					dibujarJuego(i);
					pantalla = "nivel1subnivel3juego";
				}
			}else if(x>=237 && x<= 422){
				if(y>=431 && y<=486){
					dibujarNivel(1);
					pantalla = "nivel1";
				}
			}

		}else if(pantalla=="nivel1subnivel1juego" && !banderaCola){			
			if(x>=28 && x<= 218){
				if(y>=431 && y<=486){
					validarCodigo();
				}
			}else if(x>=237 && x<= 422){
				if(y>=431 && y<=486){
					ocultarBlock();
					pantalla = "nivel1";
					dibujarSubnivel(0);
					pantalla = "nivel1subnivel1";
				}
			}
		}else if(pantalla=="nivel1subnivel2juego"){			
			if(x>=28 && x<= 218){
				if(y>=431 && y<=486){
					validarCodigo();
				}
			}else if(x>=237 && x<= 422){
				if(y>=431 && y<=486){
					ocultarBlock();
					pantalla = "nivel1";
					dibujarSubnivel(1);
					pantalla = "nivel1subnivel2";
				}
			}
		}else if(pantalla=="nivel1subnivel3juego"){			
			if(x>=28 && x<= 218){
				if(y>=431 && y<=486){
					validarCodigo();
				}
			}else if(x>=237 && x<= 422){
				if(y>=431 && y<=486){
					ocultarBlock();
					pantalla = "nivel1";
					dibujarSubnivel(2);
					pantalla = "nivel1subnivel3";
				}
			}
		}
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

function validarCodigo() {
    window.LoopTrap = 1000;
    Blockly.JavaScript.INFINITE_LOOP_TRAP =
      'if (--window.LoopTrap == 0) throw "Ciclo Infinito.";\n';
    var code = Blockly.JavaScript.workspaceToCode();
    Blockly.JavaScript.INFINITE_LOOP_TRAP = null;
    try {
        eval(code);
    } catch (e) {
        alert(e);
    }

}



//-----------------------------------------NIVEL 1-----------------------------------

function confirmarNivel1 () {
	nivel1.imagenOK = true;
}

function dibujarNivel1 () {
	tablero.drawImage(nivel1.imagen,0,0);
	if(nivel1.letreros==10){
		if(valores["nivel"]==1){
			if(valores["subnivel"]==0){
				tablero.drawImage(nivel1.letrero1AImagen,160,110);
				tablero.drawImage(nivel1.letrero2IImagen,300,200);
				tablero.drawImage(nivel1.letrero3IImagen,200,310);
				tablero.drawImage(nivel1.letrero4IImagen,400,330);
				tablero.drawImage(nivel1.letrero5IImagen,670,290);
			}else if(valores["subnivel"]==1){
				tablero.drawImage(nivel1.letrero1AImagen,160,110);
				tablero.drawImage(nivel1.letrero2AImagen,300,200);
				tablero.drawImage(nivel1.letrero3IImagen,200,310);
				tablero.drawImage(nivel1.letrero4IImagen,400,330);
				tablero.drawImage(nivel1.letrero5IImagen,670,290);
			}else if(valores["subnivel"]==2){
				tablero.drawImage(nivel1.letrero1AImagen,160,110);
				tablero.drawImage(nivel1.letrero2AImagen,300,200);
				tablero.drawImage(nivel1.letrero3AImagen,200,310);
				tablero.drawImage(nivel1.letrero4IImagen,400,330);
				tablero.drawImage(nivel1.letrero5IImagen,670,290);
			}else if(valores["subnivel"]==3){
				tablero.drawImage(nivel1.letrero1AImagen,160,110);
				tablero.drawImage(nivel1.letrero2AImagen,300,200);
				tablero.drawImage(nivel1.letrero3AImagen,200,310);
				tablero.drawImage(nivel1.letrero4AImagen,400,330);
				tablero.drawImage(nivel1.letrero5IImagen,670,290);
			}else if(valores["subnivel"]==4){
				tablero.drawImage(nivel1.letrero1AImagen,160,110);
				tablero.drawImage(nivel1.letrero2AImagen,300,200);
				tablero.drawImage(nivel1.letrero3AImagen,200,310);
				tablero.drawImage(nivel1.letrero4AImagen,400,330);
				tablero.drawImage(nivel1.letrero5AImagen,670,290);
			}
		}else if(valores["nivel"]>1){
			tablero.drawImage(nivel1.letrero1AImagen,160,110);
			tablero.drawImage(nivel1.letrero2AImagen,300,200);
			tablero.drawImage(nivel1.letrero3AImagen,200,310);
			tablero.drawImage(nivel1.letrero4AImagen,400,330);
			tablero.drawImage(nivel1.letrero5AImagen,670,290);
		}
	}
}

function cargarNivel1 () {
	nivel1.letrero1AImagen = new Image();
	nivel1.letrero1AImagen.src = nivel1.letrero1A;
	nivel1.letrero1AImagen.onload = aumentarLetreros;
	nivel1.letrero2AImagen = new Image();
	nivel1.letrero2AImagen.src = nivel1.letrero2A;
	nivel1.letrero2AImagen.onload = aumentarLetreros;
	nivel1.letrero3AImagen = new Image();
	nivel1.letrero3AImagen.src = nivel1.letrero3A;
	nivel1.letrero3AImagen.onload = aumentarLetreros;
	nivel1.letrero4AImagen = new Image();
	nivel1.letrero4AImagen.src = nivel1.letrero4A;
	nivel1.letrero4AImagen.onload = aumentarLetreros;
	nivel1.letrero5AImagen = new Image();
	nivel1.letrero5AImagen.src = nivel1.letrero5A;
	nivel1.letrero5AImagen.onload = aumentarLetreros;
	nivel1.letrero1IImagen = new Image();
	nivel1.letrero1IImagen.src = nivel1.letrero1I;
	nivel1.letrero1IImagen.onload = aumentarLetreros;
	nivel1.letrero2IImagen = new Image();
	nivel1.letrero2IImagen.src = nivel1.letrero2I;
	nivel1.letrero2IImagen.onload = aumentarLetreros;
	nivel1.letrero3IImagen = new Image();
	nivel1.letrero3IImagen.src = nivel1.letrero3I;
	nivel1.letrero3IImagen.onload = aumentarLetreros;
	nivel1.letrero4IImagen = new Image();
	nivel1.letrero4IImagen.src = nivel1.letrero4I;
	nivel1.letrero4IImagen.onload = aumentarLetreros;
	nivel1.letrero5IImagen = new Image();
	nivel1.letrero5IImagen.src = nivel1.letrero5I;
	nivel1.letrero5IImagen.onload = aumentarLetreros;
	nivel1.subnivel1Imagen = new Image();
	nivel1.subnivel1Imagen.src = nivel1.subnivel1URL;
	nivel1.subnivel2Imagen = new Image();
	nivel1.subnivel2Imagen.src = nivel1.subnivel2URL;
	nivel1.subnivel31Imagen = new Image();
	nivel1.subnivel31Imagen.src = nivel1.subnivel31URL;
	nivel1.subnivel32Imagen = new Image();
	nivel1.subnivel32Imagen.src = nivel1.subnivel32URL;
	nivel1.subnivel33Imagen = new Image();
	nivel1.subnivel33Imagen.src = nivel1.subnivel33URL;
	control.imagenInicial = new Image();
	control.imagenInicial.src = control.imagenInicialURL;
	control.imagenFinal = new Image();
	control.imagenFinal.src = control.imagenFinalURL;
}

function aumentarLetreros () {
	nivel1.letreros++;
}

//-----------------------------------Subnivel 1--------------------------------------
function dibujarNivel1Subnivel1 () {
	subnivelActual=0;
	pintarTablero1();
	alanTableroX = 430;
	alanTableroY = 215;
	alanImagenX = 0;
	alanImagenY = 3;
	tablero.drawImage(castillos.imagenAlan,(alanImagenX)*alanAncho,(alanImagenY)*alanAlto,alanAncho,alanAlto,alanTableroX,alanTableroY,alanAncho, alanAlto);
	avanzarAnimado();
	avanzarAnimado();
}

function dibujarJuegoNivel1Subnivel1 () {
	tablero.drawImage(nivel1.subnivel1Imagen,0,0);
	tablero.drawImage(control.imagenInicial,0,0);
	alanTableroX = 630;
	alanTableroY = 215;
	tablero.fillStyle = '#000';
	tablero.drawImage(castillos.imagenAlan,(alanImagenX)*alanAncho,(alanImagenY)*alanAlto,alanAncho,alanAlto,alanTableroX,alanTableroY,alanAncho, alanAlto);
	tablero.fillText("Enviar",90,460);
	tablero.fillText("Atrás",300,460);
	var toolbox = '<xml>';
	toolbox += '  <block type="avanzar"></block>';
	toolbox += '  <block type="girar"></block>';
	toolbox += '</xml>';
	document.getElementById('blocklyDiv').innerHTML="";
	Blockly.inject(document.getElementById('blocklyDiv'),
	{toolbox: toolbox});
	desplegarBlock();
	Blockly.updateToolbox(toolbox);
	actualx=1;
    actualy=2;
}

function pintarTablero1(){
	tablero.drawImage(nivel1.subnivel1Imagen,0,0);
	tablero.drawImage(control.imagenInicial,0,0);
	tablero.fillStyle = '#000';
	tablero.font = "bold 22px sans-serif";
	tablero.fillText("¡Preparate para la lucha!",100,50);
	tablero.fillText("Después de dos años de exilio",50,80);
	tablero.fillText("por los malvados hechiceros",50,110);
	tablero.fillText("vuelves al reino.",50,140);
	tablero.fillText("Tu reto:",180,200);
	tablero.fillText("Sigue el camino en linea recta",50,230);
	tablero.fillText("para entrar al pueblo.",50,260);
	tablero.fillText("Jugar",90,460);
	tablero.fillText("Atrás",300,460);
}


//----------------------------------------Subnivel 2--------------------
function dibujarNivel1Subnivel2(){
	subnivelActual=1;
	pintarTablero2();
	alanTableroX = 450;
	alanTableroY = 225;
	alanImagenX = 0;
	alanImagenY = 3;
	tablero.drawImage(castillos.imagenAlan,(alanImagenX)*alanAncho,(alanImagenY)*alanAlto,alanAncho,alanAlto,alanTableroX,alanTableroY,alanAncho, alanAlto);
	avanzarAnimado();
	avanzarAnimado();
}

function dibujarJuegoNivel1Subnivel2() {
	tablero.drawImage(nivel1.subnivel2Imagen,0,0);
	tablero.drawImage(control.imagenInicial,0,0);
	alanTableroX = 650;
	alanTableroY = 225;
	tablero.drawImage(castillos.imagenAlan,(alanImagenX)*alanAncho,(alanImagenY)*alanAlto,alanAncho,alanAlto,alanTableroX,alanTableroY,alanAncho, alanAlto);
	tablero.fillStyle = '#000';
	tablero.fillText("Enviar",90,460);
	tablero.fillText("Atrás",300,460);
	var toolbox = '<xml>';
	toolbox += '  <block type="avanzar"></block>';
	toolbox += '  <block type="girar"></block>';
	toolbox += '</xml>';
	desplegarBlock();
	document.getElementById('blocklyDiv').innerHTML="";
	Blockly.inject(document.getElementById('blocklyDiv'),
		{maxBlocks:10,toolbox: toolbox});
	actualx=2;
    actualy=2;
}

function pintarTablero2(){
	tablero.drawImage(nivel1.subnivel2Imagen,0,0);
	tablero.drawImage(control.imagenInicial,0,0);
	tablero.fillStyle = '#000';
	tablero.font = "bold 22px sans-serif";
	tablero.fillText("¡Busca a tu oponente!",100,50);
	tablero.fillText("Algunos hechiceros buscan tener",50,80);
	tablero.fillText("el control de los visitantes",50,110);
	tablero.fillText("¡Allí esta la casa de uno de ellos!",50,140);
	tablero.fillText("Tu reto:",180,200);
	tablero.fillText("Sigue el camino para llegar ",50,230);
	tablero.fillText("a la entrada.",50,260);
	tablero.fillText("Jugar",90,460);
	tablero.fillText("Atrás",300,460);
}

//----------------------------------------Subnivel 3--------------------
function dibujarNivel1Subnivel3(){
	subnivelActual=2;
	pintarTablero3();
	alanTableroX = 350;
	alanTableroY = 245;
	alanImagenX = 0;
	alanImagenY = 3;
	tablero.drawImage(castillos.imagenAlan,(alanImagenX)*alanAncho,(alanImagenY)*alanAlto,alanAncho,alanAlto,alanTableroX,alanTableroY,alanAncho, alanAlto);
	avanzarAnimado();
	avanzarAnimado();
}

function dibujarJuegoNivel1Subnivel3() {
	tablero.drawImage(nivel1.subnivel31Imagen,0,0);
	tablero.drawImage(control.imagenInicial,0,0);
	tablero.fillStyle = '#000';
	tablero.drawImage(castillos.imagenAlan,(alanImagenX)*alanAncho,(alanImagenY)*alanAlto,alanAncho,alanAlto,alanTableroX,alanTableroY,alanAncho, alanAlto);
	tablero.fillText("Enviar",90,460);
	tablero.fillText("Atrás",300,460);
	var toolbox = '<xml>';
	toolbox += '  <block type="avanzar"></block>';
	toolbox += '  <block type="girar"></block>';
	toolbox += '</xml>';
	desplegarBlock();
	document.getElementById('blocklyDiv').innerHTML="";
	Blockly.inject(document.getElementById('blocklyDiv'),
		{maxBlocks:10,toolbox: toolbox});
	actualx=1;
    actualy=2;
}

function pintarTablero3(){
	tablero.drawImage(nivel1.subnivel31Imagen,0,0);
	tablero.drawImage(control.imagenInicial,0,0);
	tablero.font = "bold 22px sans-serif";
	tablero.fillStyle = '#000';
	tablero.fillText("¡Ten Precaución!",100,50);
	tablero.fillText("Reto",180,110);
	tablero.fillText("¿Ves esa puerta? Junto a" ,50,140);
	tablero.fillText("ella está el mecanismo que ",50,170);
	tablero.fillText("la activa. Oprímelo y entra",50,200);
	tablero.fillText("en la habitación. Toma la espada,",50,230);
	tablero.fillText("puede servir más adelante.",50,260);
	tablero.fillText("Jugar",90,460);
	tablero.fillText("Atrás",300,460);
}


//-----------------------------------Mover a Alan ---------------------------------
var intervalo = 0;
var banderaMov = false;
function avanzarIntervalo () {
	if(intervalo<2){
		if(intervalo==0){
			banderaMov=false;
		}
		setTimeout(function(){
			if(subnivelActual==0){
				espacioT = espacioNivel1Subnivel1;
				pintarTablero1();
			}else if(subnivelActual==1){
				espacioT = espacioNivel1Subnivel2;
				pintarTablero2();
			}else if(subnivelActual==2){
				espacioT = espacioNivel1Subnivel3;
				pintarTablero3();
			}
			tablero.fillStyle = '#999';
			tablero.fillText("Jugar",90,460);
			tablero.fillText("Atrás",300,460);
			if(alanImagenY==3){
				if(intervalo==0 && actualx+1<espacioT[actualy].length && espacioT[actualy][actualx+1]){
					actualx++;
					banderaMov=true;
					alanTableroX+=50;
				}
				if(intervalo==1 && banderaMov){
					alanTableroX+=50;
				}
				alanImagenX = (alanImagenX+1)%4;
			}else if(alanImagenY==1){
				if(intervalo==0 && actualx-1>=0 && espacioT[actualy][actualx-1]){
					actualx--;
					banderaMov=true;
					alanTableroX-=50;
				}
				if(intervalo==1 && banderaMov){
					alanTableroX-=50;
				}
				alanImagenX = (alanImagenX+1)%4;
			}else if(alanImagenY==0){
				if(intervalo==0 && actualy+1<espacioT.length && espacioT[actualy+1][actualx]){
					actualy++;
					banderaMov=true;
					alanTableroY+=50;
				}
				if(intervalo==1 && banderaMov){
					alanTableroY+=50;
				}
				alanImagenX = (alanImagenX+1)%4;
			}else if(alanImagenY==2){
				if(intervalo==0 && actualy-1>=0 && espacioT[actualy-1][actualx]){
					actualy--;
					banderaMov=true;
					alanTableroY-=50;
				}
				if(intervalo==1 && banderaMov){
					alanTableroY-=50;
				}
				alanImagenX = (alanImagenX+1)%4;
			}
			tablero.drawImage(castillos.imagenAlan,(alanImagenX)*alanAncho,(alanImagenY)*alanAlto,alanAncho,alanAlto,alanTableroX,alanTableroY,alanAncho, alanAlto);
			intervalo++;
			avanzarIntervalo();
		},250);
	}else{
		intervalo=0;
		if(colaAcciones.length!=0){
			var aux = colaAcciones.shift();
			if(aux=="avanzar"){
				avanzarIntervalo();
			}else if(aux=="derecha" || aux=="izquierda"){
				girarIntervalo(aux);
			}
		}else{
			banderaCola=false;
			validarFinalSubnivel();
		}
	}
}

function avanzarIntervaloAnimado() {
	if(intervalo<2){
		setTimeout(function(){
			if(subnivelActual==0){
				pintarTablero1();
			}else if(subnivelActual==1){
				pintarTablero2();
			}else if(subnivelActual==2){
				pintarTablero3();
			}
			if(alanImagenY==3){
				alanTableroX+=50;
				alanImagenX = (alanImagenX+1)%4;
			}else if(alanImagenY==1){
				alanTableroX-=50;
				alanImagenX = (alanImagenX+1)%4;
			}else if(alanImagenY==0){
				alanTableroY+=50;
				alanImagenX = (alanImagenX+1)%4;
			}else if(alanImagenY==2){
				alanTableroY-=50;
				alanImagenX = (alanImagenX+1)%4;
			}
			tablero.drawImage(castillos.imagenAlan,(alanImagenX)*alanAncho,(alanImagenY)*alanAlto,alanAncho,alanAlto,alanTableroX,alanTableroY,alanAncho, alanAlto);
			intervalo++;
			avanzarIntervaloAnimado();
		},250);
	}else{
		intervalo=0;
		if(colaAcciones.length!=0){
			var aux = colaAcciones.shift();
			if(aux=="avanzar"){
				avanzarIntervaloAnimado();
			}else if(aux=="derecha" || aux=="izquierda"){
				girarIntervalo(aux);
			}
		}else{
			banderaCola=false;
		}
	}
}

function girarIntervalo (direccion) {
	if(intervalo<1){
		setTimeout(function(){
			if(direccion=="derecha"){
				alanImagenY = (alanImagenY+1)%4;
			}else{
				alanImagenY = alanImagenY-1;
				if(alanImagenY==-1){
					alanImagenY=3;
				}
			}
			if(subnivelActual==0){
				tablero.drawImage(nivel1.subnivel1Imagen,0,0);
			}else if(subnivelActual==1){
				tablero.drawImage(nivel1.subnivel2Imagen,0,0);
			}
			tablero.drawImage(control.imagenInicial,0,0);
			tablero.fillStyle = '#999';
			tablero.fillText("Jugar",90,460);
			tablero.fillText("Atrás",300,460);
			tablero.drawImage(castillos.imagenAlan,(alanImagenX)*alanAncho,(alanImagenY)*alanAlto,alanAncho,alanAlto,alanTableroX,alanTableroY,alanAncho, alanAlto);
			intervalo++;
			girarIntervalo(direccion);
		},250);
	}else{
		intervalo=0;
		if(colaAcciones.length!=0){
			var aux = colaAcciones.shift();
			if(aux=="avanzar"){
				avanzarIntervalo();
				
			}else if(aux=="derecha" || aux=="izquierda"){
				girarIntervalo(aux);
			}
		}else{
			banderaCola=false;
			validarFinalSubnivel();
		}
	}
}

function avanzar () {
	if(colaAcciones.length==0 && !banderaCola){
		intervalo=0;
		banderaCola = true;
		avanzarIntervalo();
	}else{
		colaAcciones.push("avanzar");
	}
}

function avanzarAnimado () {
	if(colaAcciones.length==0 && !banderaCola){
		intervalo=0;
		banderaCola = true;
		avanzarIntervaloAnimado();
	}else{
		colaAcciones.push("avanzar");
	}
}
function girar(direccion){
	if(colaAcciones.length==0 && !banderaCola){
		intervalo=0;
		banderaCola = true;
		girarIntervalo(direccion);
	}else{
		colaAcciones.push(direccion);
	}
}


//Modificarlo
function validarFinalSubnivel(){
	posX=finales[subnivelActual][0];
	posY=finales[subnivelActual][1];
			if(alanTableroX==posX && alanTableroY==posY){
				alerta("¡Misión cumplida! Continua recorriendo el Reino de Eniac :)");
				pantalla="nivel1";
				setTimeout(function(){
					document.getElementById("blocklyDiv").innerHTML="";
					document.getElementById("blocklyDiv").style.display='none';
					subnivelActual++;
					actualizarJSON();
					dibujarNivel1();
				}, 1000);
			}else{
				alerta("No completaste la misión :( ¡Vuelve a intentarlo!");
				alanImagenY=3;
				alanImagenX=0;
				setTimeout(function(){
					if(subnivelActual==0){
						dibujarJuegoNivel1Subnivel1();
					}else if(subnivelActual==1){
						dibujarJuegoNivel1Subnivel2();
					}else if(subnivelActual==2){
						dibujarJuegoNivel1Subnivel3();
					}
				}, 1000);
			}
}

function actualizarJSON(){
	if(valores["nivel"]==1){
		valores['puntaje']=parseInt(valores['puntaje'])+((subnivelActual+1)*10);
		valores["subnivel"] = subnivelActual;
	}
	enviarAjax();
}


function alerta () {

	var content = "";
	if(arguments.length >= 2){
		content = "<h3>"+arguments[0]+"</h3><p>"+arguments[1]+"</p>";
	}else{
		content= "<p>"+arguments[0]+"</p>";
	}
	var div = document.createElement("div");
	var claseDiv = "alerta ";
	div.setAttribute("class", claseDiv);
	div.innerHTML = content;
	document.body.appendChild(div);
	setTimeout(function() {
		div.style.opacity = "0";
	}, arguments[2] || 10000);
	setTimeout(function() {
		document.body.removeChild(div);
	}, 3000);
}




















//------------------------------Ajax--------------------------------
function creaObjetoAjax () { 
    var obj;
    if (window.XMLHttpRequest) {
        obj=new XMLHttpRequest();
    }else{
        obj=new ActiveXObject(Microsoft.XMLHTTP);
    }    
    return obj;
}
function enviarAjax() {

	datos = "puntaje="+valores["puntaje"]+"&nivel="+valores["nivel"]+"&subnivel="+valores["subnivel"];
    objetoAjax=creaObjetoAjax();
    objetoAjax.open("POST","index.php",true);
    objetoAjax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    objetoAjax.onreadystatechange=recogeDatos;
	objetoAjax.send(datos);
} 

function recogeDatos() {
    if (objetoAjax.readyState==4 && objetoAjax.status==200) {
        objetoAjax.responseText;
    }
}