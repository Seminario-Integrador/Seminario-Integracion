	/**
 	* .............................................
 	* UNIVERSIDAD  FRANCISCO  DE  PAULA  SANTANDER
 	*    PROGRAMA  DE  INGENIERIA  DE  SISTEMAS
 	*      ALAN Y EL MISTERIOSO REINO DE ENIAC
 	*             SAN JOSE DE CUCUTA-2014
	 * ............................................
 	*/


 	/**
 	*	Archivo encargado de abrir y cerrar las ventanas modales de registro, login 
	* 	y recuperar contraseña
 	*	@author - Gerson Yesid Lázaro 
 	*	@author - Angie Melissa Delgado
 	*/

var login = document.getElementById('login');
var registro = document.getElementById('registro');
var pass = document.getElementById("recPass");
var divRegistro = document.getElementById("registrar");
var divLogin = document.getElementById('iniciar');
var divPass = document.getElementById('pass');
var cerrarReg = document.getElementById("cerrarReg");
var cerrarLog = document.getElementById("cerrarLog");
var cerrarPass = document.getElementById("cerrarPass");


/**
*	Abre la ventana modal de recuperar contraseña
*/
function abrirPass () {
	cerrarLogin();
	divPass.style.display = "block";
	setTimeout(function() {
		divPass.style.opacity = "1";
	},200);
}


/**
*	Cierra la ventana modal de recuperar contraseña
*/
function cerrarPassword () {
	divPass.style.opacity = "0"
	setTimeout(function () {
		divPass.style.display = "none";
	},360);
}

/**
*	Abre la ventana modal de registro
*/
function abrirRegistro () {
	divRegistro.style.display = "block";
	setTimeout(function() {
		divRegistro.style.opacity = "1";
	}, 200);
}

/**
*	Cierra la ventana modal de registro
*/
function cerrarRegistro () {
	divRegistro.style.opacity = "0";
	setTimeout(function() {
		divRegistro.style.display="none";
	}, 360);
}

/**
*	Abre la ventana modal de login
*/
function abrirLogin () {
	divLogin.style.display = "block";
	setTimeout(function() {
		divLogin.style.opacity = "1";
	}, 200);
}

/**
*	Cierra la ventana modal de login
*/
function cerrarLogin () {
	divLogin.style.opacity = "0";
	setTimeout(function() {
		divLogin.style.display="none";
	}, 360);
}
login.addEventListener("click", abrirLogin);
registro.addEventListener("click", abrirRegistro);
cerrarReg.addEventListener("click", cerrarRegistro);
cerrarLog.addEventListener("click", cerrarLogin);
pass.addEventListener("click", abrirPass);
cerrarPass.addEventListener("click", cerrarPassword);