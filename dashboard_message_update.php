<?php
require_once("func.check_session.php");

#Comprobar sesion
$session = check_session();

if (!$session){
	header("Location: login.php");
	exit();
}

#Redirigir a los mensajes si cancela
if(isset($_POST["cancel"])){
	header("Location: dashboard_message.php");
	exit();
}

#Comprobar si hay mensaje
if (!isset($_POST["id_message"]) || !isset($_POST["message"])){
	header("Location: dashboard_message.php");
	exit();
}

#Comprobar la accion
if (!isset($_POST["draft"]) && !isset($_POST["publish"])){
	header("Location: dashboard_message.php");
	exit();
}

#Obtener/Comprobar id
$id_message = intval($_POST["id_message"]);

if ($id_message <= 0){
	header("Location: dashboard_message.php");
	exit();
}

#Comprobar contenido del mensaje
if(!isset($_POST["message"])){
	die("Error 1: No se ha enviado el mensaje");
}

#Quitar espacios del inicio
$msg = trim($_POST["message"]);

#Comprobar longitudes
if(strlen($msg) <= 0){
	die("Error 2: Mensaje demasiado corto");
}

if(strlen($msg) > 240){
	die("Error 3: Mensaje demasiado largo");
}

#Comprobar escapado de caracteres
$msg = addslashes($msg);

#Cambiar status
$status = 1;
if (isset($_POST["draft"])){
	$status = 2;
}

#Cambiar el mensaje
$query= <<<EOD
UPDATE
	messages
SET
	message='{$msg}',
	status={$status}
WHERE
	id_message={$id_message}
	AND id_user={$session}
EOD;

require_once("db_conf.php");

#Conexion db
$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_db);

$resultado = mysqli_query($conn, $query);

if(!$resultado){
	header("Location: dashboard_message.php");
	exit();
}


header("Location: index.php");
exit();
?>
