var login = document.getElementById('login');
var registro = document.getElementById('registro');
var divRegistro = document.getElementById("registrar");
var divLogin = document.getElementById('iniciar');
var cerrarReg = document.getElementById("cerrarReg");
var cerrarLog = document.getElementById("cerrarLog");


function abrirRegistro () {
	divRegistro.style.display = "block";
	setTimeout(function() {
		divRegistro.style.opacity = "1";
	}, 200);
}
function cerrarRegistro () {
	divRegistro.style.opacity = "0";
	setTimeout(function() {
		divRegistro.style.display="none";
	}, 360);
}
function abrirLogin () {
	divLogin.style.display = "block";
	setTimeout(function() {
		divLogin.style.opacity = "1";
	}, 200);
}
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