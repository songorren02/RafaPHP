<?php
require_once("func.check_session.php");

$session = check_session();

if (!$session){
	header("Location: login.php");
	exit();
}

if(isset($_POST["cancel"])){
	header("Location: dashboard_messages.php");
	exit();
}




if (!isset($_POST["id_message"]) || !isset($_POST["message"])){
	header("Location: dashboard_messages.php");
	exit();
}


if (!isset($_POST["draft"]) && !isset($_POST["publish"])){
	header("Location: dashboard_messages.php");
	exit();
}


$id_message = intval($_POST["id_message"]);
if ($id_message <= 0){
	header("Location: dashboard_messages.php");
	exit();
}


/* TODO: COMPROBAR QUE EL MENSAJE ES CORRECTO */

$message = $_POST["message"];

$status = 1;
if (isset($_POST["draft"])){
	$status = 2;
}

$query = <<<EOD
UPDATE
	messages
SET
	message='{$message}',
	status={$status}
WHERE
	id_message={$id_message}
	AND user={$session}
EOD;

echo $query;


?>
