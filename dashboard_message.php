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
	}
	else if($msg["status"] == 2){
		$status = "<p class=\"message-status\">Borrador<p>";
		$status_class = "class\"draft\"";
	}
	else if($msg["status"] == 1){
		$status = "<p class=\"message-status\">Publicado<p>";
		$status_class = "class\"draft\"";
	}

	write_message($msg);
	
}

#Cerrar html
close_html();

?>
