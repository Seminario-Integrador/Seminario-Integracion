var activo = false;
var left = document.getElementById('left');
var right = document.getElementById('right');
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
	activo = !activo;
}

left.addEventListener("click", desplegar);


function cerrarSesion () {
	document.cerrarS.submit();
}