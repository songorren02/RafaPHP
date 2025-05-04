<?php
session_start();

#Comprobar sesion
if (!isset($_SESSION["id_user"])){
	header("Location: login.php");
	exit();
}

#Comprobar si hay mensaje 
if (!isset($_GET["id_message"])){
	die("Error 1: No hay mensaje que borrar");
}

#Obtener valor entero
$id_message = intval($_GET["id_message"]);

#Comprobar si el id es posible
if ($id_message <= 0){
	die("Error 2: Identificador incorrecto");
}

#Obtener el id del usuario
$id_user = intval($_SESSION["id_user"]);

require_once("db_conf.php");

#Conexion con la db
$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_db);

#Obtener el id del mensaje 
$query = <<<EOD
SELECT
	id_message
FROM
	messages
WHERE
	id_message={$id_message}
	AND id_user={$id_user}
EOD;

$result = mysqli_query($conn, $query);

#Comprobar los resultados de la petición
if (!$result) {
	die("Error 3: Error al hacer la petición");
}

if (mysqli_num_rows($result) != 1) {
	die("Error 4: Error al hacer la petición");
}

#Actualizar el status del mensaje status 0 = Eliminado
$query = <<<EOD
UPDATE
	messages
SET
	status=0
WHERE
	id_message={$id_message}
	AND id_user={$id_user}
EOD;

#Comprobar resultados
$result = mysqli_query($conn, $query);

if (!$result) {
	die("Error 5: Error al hacer la petición");
}

header("Location: index.php");
exit();


?>
