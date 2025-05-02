<?php
session_start();

require_once("template.php");

$session = false;

if (isset($_SESSION["id_user"])){
	$session = true;
}
else{
	header("Location: login.php");
	exit();
}

open_html();


$id_user = intval($_SESSION["id_user"]);

$query = "SELECT * FROM users WHERE id_user='".$id_user."'";

require_once("func.write_error_message.php");

if (isset($_GET["user"])) {
	$username = trim($_GET["user"]);

	if (strlen($username) <= 2){
		write_error_message("Nombre de ususario muy corto", 1);
		close_html();
		exit();
	}

	$username = str_replace(" ", "", $username);

	if (strlen($username) > 16){
		write_error_message("Nombre de ususario muy largo", 2);
		close_html();
		exit();
	}

	$username_tmp = addslashes($username);

	if ($username_tmp != $username){
		write_error_message("Nombre con caracteres no válidos", 3);
		close_html();
		exit();
	}

	$username = $username_tmp;

	$query = "SELECT * FROM users WHERE username='".$username."'";
}

require_once("db_conf.php");

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_db);

$resultado = mysqli_query($conn, $query);

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


echo <<<EOD
<section id="userbio-block">
	<h2>Biografía de {$user_data["name"]}</h2>
	<ul>
		<li>Nacimiento: {$user_data["birthdate"]}</li>
	</ul>
</section>
EOD;

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


while ($msg = $resultado->fetch_assoc()){
	write_message($msg);
}


echo <<<EOD
</section>
EOD;

close_html();


?>
