<?php

#Iniciar sesion
session_start();

require_once("template.php");

#Sesion por defecto 
$session = false;

#Comprobar sesion
if (isset($_SESSION["id_user"])){
	$session = true;
}
else{
	header("Location: login.php");
	exit();
}

#Abrir html
open_html();

#Obtener valor entero del id del user
$id_user = intval($_SESSION["id_user"]);

#Obtener info del user
$query = "SELECT * FROM users WHERE id_user='".$id_user."'";

require_once("func.write_error_message.php");

#Comprobar el usuario en GET
if (isset($_GET["user"])) {

	/* ---- Comprobaciones de seguridad ---- */

	#Eliminar espacios en blanco delante
	$username = trim($_GET["user"]);
	
	#Comprobar logitudes
	if (strlen($username) <= 2){
		write_error_message("Nombre de ususario muy corto", 1);
		close_html();
		exit();
	}

	#Quitar espacios en blanco en medio de la string
	$username = str_replace(" ", "", $username);

	#Compriobar longitud
	if (strlen($username) > 16){
		write_error_message("Nombre de ususario muy largo", 2);
		close_html();
		exit();
	}

	#Comprobar escapado de caracteres
	$username_tmp = addslashes($username);
	
	if ($username_tmp != $username){
		write_error_message("Nombre con caracteres no válidos", 3);
		close_html();
		exit();
	}
	
	$username = $username_tmp;

	#Obtener info del usuario
	$query = "SELECT * FROM users WHERE username='".$username."'";
}

require_once("db_conf.php");

#Conexion con la db
$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_db);

$resultado = mysqli_query($conn, $query);

#Comprobar resultado
if (!$resultado) {
	write_error_message("Error al buscar el nombre", 4);
	close_html();
	exit();
}

if (mysqli_num_rows($resultado) != 1){
	write_error_message("Error al buscar el nombre", 5);
	close_html();
	exit();
}

$user_data = $resultado->fetch_assoc();

#Mostrar BIO
echo <<<EOD
<section id="userbio-block">
	<h2>Biografía de {$user_data["name"]}</h2>
	<ul>
		<li>Nacimiento: {$user_data["birthdate"]}</li>
	</ul>
</section>
EOD;

#Obtener datos / mensajes de BIO del usuario
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
	AND users.id_user={$user_data["id_user"]}
ORDER BY
	messages.post_time DESC
EOD;

$resultado = mysqli_query($conn, $query);

#Comprobar resultados
if (!$resultado) {
	echo "<p class=\"error_msg\">Error al leer el feed de mensajes</p>";
	write_error_message("Error al leer el feed de mensajes", 6);
	echo <<<EOD
</section>
EOD;
	close_html();
	exit();
}

require_once("func.write_message.php");

#Mostrar los mensajes obtenidos
while ($msg = $resultado->fetch_assoc()){
	write_message($msg);
}

#Cierres de html
echo <<<EOD
</section>
EOD;

close_html();


?>
