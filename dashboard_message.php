<?php

require_once("func.check_session.php");

#Comprobar sesion
$session = check_session();

if(!$session){
	header("Location: login.php");
	exit();
}

#Obtener los mensajes del usuer
$query = <<<EOD
SELECT 
	users.id_user,
	users.username,
	users.name,
	messages.id_message,
	messages.message,
	messages.post_time,
	messages.status
FROM 
	users
INNER JOIN
	messages
ON
	users.id_user=messages.id_user
WHERE 
	users.id_user={$session}
ORDER BY
	messages.post_time DESC
EOD;

require_once("db_conf.php");

#Conexion con la db
$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_db);

$resultado = mysqli_query($conn, $query);


require_once("template.php");

#Abrir html
open_html("Dashboard: Message", "dashboard-message");

require_once("func.dashboard_menu.php");

#Mostrar el menu del dashboard
dashboard_menu();

#Comprobar resultados 
if (!$resultado) {
	echo "<p class=\"error_msg\">Error al leer el feed de mensajes</p>";
	echo <<<EOD
</section>
EOD;
	close_html();
	exit();
}


require_once("func.write_message.php");

#Mensajes del usuario 
while($msg = $resultado->fetch_assoc()){

	#Comprobar el status
	if($msg["status"] == 0){
		$status = "<p class=\"message-status\">Eliminado</p>";
		$status_class = 'class="deleted"';

		/* BOTON 1 */

		#Comprobar si el usuario logueado es el mismo que el que borra
		if($session == $msg["id_user"]){
			$boton1= <<<EOD
<p class="message-delete"><a href="dashboard_message_recover.php?id_message={$msg["id_message"]}">Recuperar</a></p>
EOD;
		}
			
		/* BOTON 2 */
	
		#Comprobar si el usuario logueado es el mismo que el que borra
		if($session == $msg["id_user"]){
			$boton2= <<<EOD
<p class="message-delete"><a href="dashboard_message_draft.php?id_message={$msg["id_message"]}">Borrador</a></p>
EOD;
		}
		
	}
	else if($msg["status"] == 2){
		$status = "<p class=\"message-status\">Borrador<p>";
		$status_class = "class\"draft\"";
		
		/* BOTON 1 */

		#Comprobar si el usuario logueado es el mismo que el que borra
		if($session == $msg["id_user"]){
			$boton1= <<<EOD
<p class="message-delete"><a href="dashboard_message_delete.php?id_message={$msg["id_message"]}">Eliminar</a></p>
EOD;
		}

		/* BOTON 2 */

		#Comprobar si el usuario logueado es el mismo que el que borra
		if($session == $msg["id_user"]){
			$boton2= <<<EOD
<p class="message-delete"><a href="dashboard_message_edit.php?id_message={$msg["id_message"]}">Editar</a></p>
EOD;
		}

	}
	else if($msg["status"] == 1){
		$status = "<p class=\"message-status\">Publicado<p>";
		$status_class = "class\"draft\"";
		
		/* BOTON 1 */

		#Comprobar si el usuario logueado es el mismo que el que borra
		if($session == $msg["id_user"]){
			$boton1= <<<EOD
<p class="message-delete"><a href="dashboard_message_delete.php?id_message={$msg["id_message"]}">Eliminar</a></p>
EOD;
		}

		/* BOTON 2 */

		#Comprobar si el usuario logueado es el mismo que el que borra
		if($session == $msg["id_user"]){
			$boton2= <<<EOD
<p class="message-delete"><a href="dashboard_message_draft.php?id_message={$msg["id_message"]}">Borrador</a></p>
EOD;
		}
	}

	echo <<<EOD
<section class="message"{$status_class}>
	<p class="message-text">{$msg["message"]}</p>
	<p class="message-date">{$msg["post_time"]}</p>
	{$status}
	{$boton1}
	{$boton2}
</section>
EOD;	
}

#Cerrar html
close_html();

?>
