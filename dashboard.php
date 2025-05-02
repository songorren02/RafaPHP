<?php
require_once("func.check_session.php");

$session = check_session();

if (!$session){
	header("Location: login.php");
	exit();
}

require_once("template.php");

open_html("Dashboard");

require_once("func.dashboard_menu.php");

dashboard_menu();

$query = <<<EOD
SELECT *
FROM users
WHERE id_user={$session}
EOD;

require_once("db_conf.php");

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_db);

$resultado = mysqli_query($conn, $query);
if (mysqli_num_rows($resultado) == 0){
    echo "Error 1: Usuario no existe";
	close_html();
    exit();
}

$user = $resultado->fetch_assoc();

echo <<<EOD
<section id="user_data">
	<h2>Datos del usuario</h2>
	<ul>
		<li><strong>Nombre:</strong> {$user["name"]}</li>
		<li><strong>Usuario:</strong> {$user["username"]}</li>
		<li><strong>e-mail:</strong> {$user["email"]}</li>
		<li><strong>Nacimiento:</strong> {$user["birthdate"]}</li>
		<li><strong>Password:</strong> ******</li>
	</ul>
</section>
EOD;

$query = <<<EOD
SELECT *
FROM messages
WHERE id_user={$session}
AND post_time=(
	SELECT MAX(post_time)
	FROM messages
	WHERE id_user={$session}
)
EOD;

$resultado = mysqli_query($conn, $query);

if (mysqli_num_rows($resultado) == 0){
	echo <<<EOD
<section id="dashboard_no_last_message">
	<h2>¡Empieza hoy!</h2>
	<p><a href="index.php">¡Escribe tu primer mensaje!</a></p>
</section>
EOD;
}
else{
	$msg = $resultado->fetch_assoc();

	$status = "";
	$status_class = "";
	if ($msg["status"] == 0){
		$status = "<p class=\"message-status\">Eliminado</p>";
		$status_class = ' class="deleted"';
	}
	else if ($msg["status"] == 2){
		$status = "<p class=\"message-status\">Borrador</p>";
		$status_class = " class=\"draft\"";
	}


	echo <<<EOD
<section id="dashboard_last_message"{$status_class}>
	<h2>Último mensaje</h2>
	<p class="message-text">{$msg["message"]}</p>
	<p class="message-date">{$msg["post_time"]}</p>
	{$status}
</section>
EOD;
}





close_html();
?>
