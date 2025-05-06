<?php

require_once("func.check_session.php");

#Comprobar la sesion
$session = check_session();

if(!$session){
	header("Location: login.php");
	exit();
}

#Obtener el valor del entero
$id_message = intval($_GET["id_message"]);

#Comprobar si el id es posible
if($id_message <= 0){
	die("Error 2: Identificador incorrecto");
}

#Insertar el like
$query = <<<EOD
INSERT INTO
	likes(id_user, id_message, like_count)
VALUES
	({$session}, {$id_message}, 1)
EOD;

require_once("db_conf.php");

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_db);

#Comprobar resultados
$result = mysqli_query($conn, $query);

if(!$result){
	die("Error 5: Error al hacer la peticion");
}

header("Location: index.php");
exit();

?>
