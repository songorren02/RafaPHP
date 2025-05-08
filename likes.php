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

#Comprobar si le ha dado like
require_once("db_conf.php");
	
$query = <<<EOD
SELECT 
	like_count
FROM
	likes
WHERE
	id_user = {$session}
	AND id_message = {$id_message}
	AND like_count = 1
EOD;

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_db);

$result = mysqli_query($conn, $query);

if($result = 1){
	#El usuario ya le ha dado like
	#Cambiar el estado del like
	$query = <<<EOD
	UPDATE
		likes
	SET
		like_count = false
	WHERE
		id_user = {$session}
		AND id_message = {$id_message}
	
EOD;	

	$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_db);

	$result = mysqli_query($conn, $query);

	if(!$result){
		die("Error al ejecutar la peticion");
	}
}else if($result = 0){
	#Like existe pero lo ha quitado

	$query = <<<EOD
	UPDATE
		likes
	SET
		like_count = true
	WHERE
		id_user = {$session}
		AND id_message = {$id_message}
EOD;
	$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_db);

	$result = mysqli_query($conn, $query);

	if(!$result){
		die("Error al ejecutar la peticion");
	}
}else if(!$result){
	#No le ha dado like
	
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
}

header("Location: index.php");
exit();

?>
