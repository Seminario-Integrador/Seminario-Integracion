var activo = false;
var left = document.getElementById('left');
var right = document.getElementById('right');
var sesion = document.getElementById("cerrarSesion");
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
function cerrarSesion() {
	document.cerrarS.submit();
}
left.addEventListener("mouseover", mostrar);
left.addEventListener("mouseout", ocultar);
left.addEventListener("click", desplegar);
sesion.addEventListener("click", cerrarSesion);


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
		console.log("si");
	}, arguments[2] || 10000);
	setTimeout(function() {
		document.body.removeChild(div);
		console.log("aja");
	}, arguments[2]+1000 ||  11000);
}
var block = document.getElementById("blocklyDiv");
function desplegarBlock () {
	block.style.display = "block";
	setTimeout(function () {
		block.style.opacity = "1";
	},150);
}
function ocultarBlock () {
	block.style.opacity=0;
	setTimeout(function () {
		block.style.display ="none";
	},350);
}