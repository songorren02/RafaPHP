<?php

#Iniciar sesion
session_start();

#Comprobar si hay sesion iniciada
if (!isset($_SESSION["id_user"])){
	header("Location: login.php");
	exit();
}

#Comprobar si se ha enviado
if (!isset($_POST["message"])){
	die("Error 1: No se ha enviado el mensaje");
}

#Quitar espacios del inicio
$msg = trim($_POST["message"]);

#Comprobar logitudes
if (strlen($msg) <= 0) {
	die("Error 2: Mensaje demasiado corto");
}

if (strlen($msg) > 240) {
	die("Error 2: Mensaje demasiado largo");
}

#Comprobar escapado de caracteres
$msg = addslashes($msg);

#Obtener id del usuario
$id_user = intval($_SESSION["id_user"]);

#Guardar el mensaje
$query = <<<EOD
INSERT INTO messages (message, post_time, id_user)
VALUES('{$msg}', now(), {$id_user});
EOD;

require_once("db_conf.php");

#Conexion con la db
$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_db);

$resultado = mysqli_query($conn, $query);

#Comprobar si hay resultado
if (!$resultado){
	die("Error 3: Query mal formada");
}

header("Location: index.php");

exit();


?>
