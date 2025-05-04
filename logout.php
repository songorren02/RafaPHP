<?php

require_once("func.check_session.php");

#Comprobar la sesion
$session = check_session();

if(!$session){
	header("Location: login.php");
	exit();
}

#Iniciar sesion
#session_start();

#Finalizar sesion
session_destroy();

//$_COOKIE["logout"] = $name;

#Cmabiar la cookie a logout
setcookie("logout", $session);

#Enviar a index
header("Location: index.php");

exit();

?>
