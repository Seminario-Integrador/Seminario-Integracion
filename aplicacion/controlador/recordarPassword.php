<?php

$link = "http://localhost/integrador/Seminario-Integracion/index.php?correo=".$_GET["correo"]."&recuperar=".sha1($_GET["enlace"]);
$mensaje = "Hola ".$_GET['user']." ¿Como va todo? <br> Enviamos este mensaje porque has solicitado cambiar tu contraseña en Alan y el misterioso reino 
de Eniac. Si realmente solicitaste este cambio haz clic en el siguiente link o copialo y pegalo en tu navegador. De lo contrario, elimina este mensaje. <br>"
.$link."<br> Att. El equipo De Alan <br> Gerson Lazaro-Melissa Delgado <br> Ingenieria de Sistemas UFPS <br> Seminario Integrador <br> 2014";
			

$headers = "MIME-Version: 1.0\r\n"; 
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 
$headers .= "From: Equipo Alan <GersonLazaro@GersonLazaro.com>\r\n"; 
$headers .= "Reply-To: GersonLazaro@GersonLazaro.com\r\n";

$rta = mail($_GET["correo"], "Recuperar Password - Alan y el misterioso reino de Eniac", $mensaje, $headers);
if($rta){
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=http://localhost/integrador/Seminario-Integracion/index.php?recuperado=si">';
}else{
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=http://localhost/integrador/Seminario-Integracion/index.php?recuperado=no">';
}
?>	