	/**
 	* .............................................
 	* UNIVERSIDAD  FRANCISCO  DE  PAULA  SANTANDER
 	*    PROGRAMA  DE  INGENIERIA  DE  SISTEMAS
 	*      ALAN Y EL MISTERIOSO REINO DE ENIAC
 	*             SAN JOSE DE CUCUTA-2014
	 * ............................................
 	*/


 	/**
 	*	Archivo encargado de controlar el desplazamiento del menu, su ocultamiento,
	* 	Las ventanas modales, y otras disposiciones del diseño web
 	*	@author - Gerson Yesid Lázaro 
 	*	@author - Angie Melissa Delgado
 	*/

var activo = false;
var left = document.getElementById('left');
var right = document.getElementById('right');
var sesion = document.getElementById("cerrarSesion");


/**
*	Controla el movimiento del menu. Si esta oculto lo despliega en pantalla y viceversa
*/
function desplegar () {
	if(activo){
		if(window.innerWidth>=1200){
			right.style.width = "110rem";
			left.style.width = "7rem";	
		}else if(window.innerWidth>=992 && window.innerWidth<1200){
			right.style.width = "90rem";
			left.style.width = "7rem";	
		}else if(window.innerWidth>=768 && window.innerWidth<992){
			right.style.width = "69rem";
			left.style.width = "6rem";	
		}else{
			left.style.height = "7rem";
		}
		
	}else{
		if(window.innerWidth>=768){
			left.style.width = "20%";
			right.style.width = "80%";	
		}else{
			left.style.height = "100%";
		}
		
	}
	setTimeout(obtenerCoordenadas,300);
	activo = !activo;
}

/**
*	Muestra el menú si esta oculto
*	Realiza ajuste en pantallas pequeñas
*/
function mostrar () {
	if(!activo){
		if(window.innerWidth>=768){
			left.style.width = "20%";
			right.style.width = "80%";	
		}else{
			left.style.height = "100%";
		}
	}
}

/**
*	Oculta el menú si esta activo
*	Realiza ajuste en pantallas pequeñas
*/
function ocultar () {
	if(!activo){
		if(window.innerWidth>=1200){
			right.style.width = "110rem";
			left.style.width = "7rem";	
		}else if(window.innerWidth>=992 && window.innerWidth<1200){
			right.style.width = "90rem";
			left.style.width = "7rem";	
		}else if(window.innerWidth>=768 && window.innerWidth<992){
			right.style.width = "69rem";
			left.style.width = "6rem";	
		}else{
			left.style.height = "7rem";
		}
	}
}

/**
*	Activa el formulario cerrarSesion desde el boton
*/
function cerrarSesion() {
	document.cerrarS.submit();
}
left.addEventListener("mouseover", mostrar);
left.addEventListener("mouseout", ocultar);
left.addEventListener("click", desplegar);
sesion.addEventListener("click", cerrarSesion);

/**
*	Genera alertas modales
*/
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
	}, arguments[2]+1000 ||  11000);
}
var block = document.getElementById("blocklyDiv");

/**
*	Muestra el bloque del API Blockly
*/
function desplegarBlock () {
	block.style.display = "block";
	setTimeout(function () {
		block.style.opacity = "1";
	},150);
}

/**
*	Oculta el bloque del API Blockly
*/
function ocultarBlock () {
	block.style.opacity=0;
	setTimeout(function () {
		block.style.display ="none";
	},350);
}