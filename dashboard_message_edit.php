<?php

require_once("func.check_session.php");

#Iniciar/comprobar sesion
$session = check_session();

#Sesion no iniciada
if (!$session){
	header("Location: login.php");
	exit();
}

#No hay mensaje
if (!isset($_GET["id_message"])){
	header("Location: dashboard_messages.php");
	exit();
}

#Guardar valor númerico (ID) - Comprovación 
$id_message = intval($_GET["id_message"]);

#Comprobar si el id es posible
if ($id_message <= 0){
	header("Location: dashboard_message.php");
	exit();
}

#Obtener el mensaje
$query = <<<EOD
SELECT
	*
FROM
	messages
WHERE
	id_message={$id_message}
	AND id_user={$session}
EOD;

require_once("db_conf.php");

#Funcionalidades / Conexiones mysqli
$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_db);

$resultado = mysqli_query($conn, $query);

if (mysqli_num_rows($resultado) == 0){
	header("Location: dashboard_message.php");
	exit();
}

require_once("template.php");

#Abrir header html
open_html("Dashboard");

#Obtenemos la fila
$msg = $resultado->fetch_assoc();

#HTML editar mensaje
echo <<<EOD
<form method="POST" action="dashboard_message_update.php">
<h2>Edita el mensaje</h2>

<input type="hidden" name="id_message" value="{$id_message}" />

<p><label for="dashboard-update-message">Mensaje:</label>
<textarea id="dashboard-update-message" name="message">
{$msg["message"]}
</textarea></p>

<p><input type="submit" name="draft" value="Guarda como borrador" /></p>
<p><input type="submit" name="publish" value="Publica mensaje" /></p>
<p><input type="submit" name="cancel" value="Cancela la edición" /></p>
</form>
EOD;

#Cerrar HTML
close_html();
?>
