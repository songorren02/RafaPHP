<?php
require_once("func.check_session.php");

$session = check_session();

if (!$session){
	header("Location: login.php");
	exit();
}

$query = <<<EOD
SELECT *
FROM users
WHERE id_user={$session}
EOD;

require_once("db_conf.php");

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_db);

$resultado = mysqli_query($conn, $query);

if (!$resultado) {
	header("Location: login.php");
	exit();
}

if (mysqli_num_rows($resultado) != 1){
	header("Location: login.php");
	exit();
}


require_once("template.php");

open_html("Dashboard: Profile", "dashboard-profile");


require_once("func.dashboard_menu.php");

dashboard_menu();


$user = $resultado->fetch_assoc();

echo <<<EOD
<form method="POST" action="dashboard_profile_check.php">

<h2>Actualizar datos de usuario</h2>
<p><label for="username-profile-update">Usuario:</label>
<input type="text" id="username-profile-update" name="username" value="{$user["username"]}" /></p>

<p><label for="name-profile-update">Nombre:</label>
<input type="text" id="name-profile-update" name="name" value="{$user["name"]}" /></p>

<p><label for="date-profile-update">Nacicimiento:</label>
<input type="date" id="date-profile-update" name="date" value="{$user["birthdate"]}" /></p>

<p><label for="email-profile-update">e-mail:</label>
<input type="email" id="email-profile-update" name="email" value="{$user["email"]}" /></p>

<p><input type="submit" value="Actualizar" /></p>

</form>
EOD;

close_html();
?>
