<?php
session_start();

if (!isset($_SESSION["id_user"])){
	header("Location: login.php");
	exit();
}

if (!isset($_POST["message"])){
	die("Error 1: No se ha enviado el mensaje");
}

$msg = trim($_POST["message"]);

if (strlen($msg) <= 0) {
	die("Error 2: Mensaje demasiado corto");
}

if (strlen($msg) > 240) {
	die("Error 2: Mensaje demasiado corto");
}

$msg = addslashes($msg);

$id_user = intval($_SESSION["id_user"]);

$query = <<<EOD
INSERT INTO messages (message, post_time, id_user)
VALUES('{$msg}', now(), {$id_user});
EOD;

require_once("db_conf.php");

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_db);

$resultado = mysqli_query($conn, $query);

if (!$resultado){
	die("Error 3: Query mal formada");
}

header("Location: index.php");

exit();


?>
