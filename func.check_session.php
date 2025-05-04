<?php

#Funcion para comprobar la sesion del usuario
function check_session ()
{
	#Iniciar sesion
	session_start();

	#Comprobar sesion
	if (!isset($_SESSION["id_user"])){
		return false;
	}

	#Obtener el entero del id
	$id_user = intval($_SESSION["id_user"]);

	#Comprobar que el id es posible
	if ($id_user <= 0){
		return false;
	}

	return $id_user;
}
?>
