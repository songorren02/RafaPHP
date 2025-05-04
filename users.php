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
<p id="logout_msg" class="notice"><strong>Session del usuario <em>{$user_prev}</em> cerrada</strong></p>
EOD;
}

#Conexion db
require_once("db_conf.php");

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_db);

#Obtener usuarios

$query = <<<EOD
SELECT
	username
FROM
	users
EOD;

$resultado = mysqli_query($conn, $query);

#Comprobar si hay mensajes
if(!$resultado){
	echo "<p class=\"error_msg\">Error al leer los usuarios</p>";
	echo <<<EOD
</section>
EOD;
	close_html();
	exit();
}


echo <<<EOD
<ul class="users">
<h2>Usuarios</h2>
EOD;

#Mostrar los usuarios
while($user = $resultado->fetch_assoc()){
	echo <<<EOD
	<li><a href="profile.php?user={$user["username"]}">{$user["username"]}</a></li>
EOD;
}

#Cierres de html
echo <<<EOD
</section>
EOD;

close_html();


?>
