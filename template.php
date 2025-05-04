<?php

#Abrir HTML
function open_html ($title = "ENTIhub", $body_id="")
{
	#Sesion por defecto 
	$session = false;

	#Confirmar si hay sesion
	if (isset($_SESSION["id_user"])){
		$session = true;
	}

	$loginout = "";
	
	#Dar valor al logout	
	if ($session){
		$loginout = "<a href=\"logout.php\">Logout</a>";
	}
	else{
		#Como no hay sesion, el logout va a ser el propio login
		$loginout = "<a href=\"login.php\">Login</a>";
	}

	#Identificar en que pagina nos encontramos
	if ($body_id != "")
		$body_id = " id=\"".$body_id."\"";
	
	echo <<<EOD
<!doctype html>
<html>
<head>
	<title>{$title}</title>
	<link rel="stylesheet" type="text/css" href="entihub_prueba.css">
</head>

<body{$body_id}>
	<header>
		<h1><a href="index.php">ENTIhub</a></h1>
		<nav>
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="profile.php">Perfil</a></li>
				<li><a href="users.php">Usuarios</a></li>
				<li><a href="dashboard.php">Dashboard</a></li>
				<li>{$loginout}</li>
			</ul>
		</nav>
	</header>
	<main>
EOD;
}

function close_html ()
{
	echo <<<EOD
	</main>
	<footer>
		<p>Copyright (c) ENTIhub 2025</p>
	</footer>
</body>
</html>
EOD;
}



?>
