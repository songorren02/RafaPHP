<?php
require_once("func.check_session.php");

#Comprobar sesion
$session = check_session();

require_once("template.php");

#Abrir html
open_html();

#Comprobar cierre de sesion
if (isset($_COOKIE["logout"]) && $_COOKIE["logout"] != ""){
	$user_prev = addslashes($_COOKIE["logout"]);
	setcookie("logout", "");
	echo <<<EOD
<p id="logout_msg" class="notice"><strong>Sessión del usuario <em>{$user_prev}</em> cerrada</strong></p>
EOD;
}

#Mostrar pantalla inicial
if ($session) {
	echo <<<EOD
<aside id="message_form">
	<h2>¿Qué está ocurriendo?</h2>
	<form method="POST" action="message_check.php">
		<p><textarea placeholder="Introduce tu mensaje" name="message"></textarea></p>
		<p><input type="submit" value="Envia mensaje" /></p>
	</form>
</aside>
EOD;
}
else{
	echo <<<EOD
<aside id="message_login">
	<p><a href="login.php">Identifícate o regístrate para interactuar</a></p>
</aside>
EOD;

}


echo <<<EOD
<section id="message-block">
	<h2>Lo que dice la gente...</h2>
EOD;

require_once("db_conf.php");

#Conexion con la db
$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_db);

#Obtener mensajes
$query = <<<EOD
SELECT
	users.id_user,
	users.username,
	users.name,
	messages.id_message,
	messages.message,
	messages.post_time
FROM
	users
INNER JOIN
	messages
ON
	users.id_user=messages.id_user
WHERE
	messages.status=1
ORDER BY
	messages.post_time DESC
EOD;

$resultado = mysqli_query($conn, $query);

#Comprobar si hay mensajes
if (!$resultado) {
	echo "<p class=\"error_msg\">Error al leer el feed de mensajes</p>";
	echo <<<EOD
</section>
EOD;
	close_html();
	exit();
}

require_once("func.write_message.php");

#Escribir los mensajes
while ($msg = $resultado->fetch_assoc()){
	write_message($msg);
}

#Cierres del html
echo <<<EOD
</section>
EOD;

close_html();

?>
