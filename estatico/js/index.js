var login = document.getElementById('login');
var registro = document.getElementById('registro');
var pass = document.getElementById("recPass");
var divRegistro = document.getElementById("registrar");
var divLogin = document.getElementById('iniciar');
var divPass = document.getElementById('pass');
var cerrarReg = document.getElementById("cerrarReg");
var cerrarLog = document.getElementById("cerrarLog");
var cerrarPass = document.getElementById("cerrarPass");

function abrirPass () {
	cerrarLogin();
	divPass.style.display = "block";
	setTimeout(function() {
		divPass.style.opacity = "1";
	},200);
}
function cerrarPass () {
	divPass.style.opacity = "0"
	setTimeout(function () {
		divPass.style.display = "none";
	},360);
}
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
pass.addEventListener("click", abrirPass);
cerrarPass.addEventListener("click", cerrarPass);