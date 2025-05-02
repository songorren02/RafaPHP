<?php

function open_html ($title = "ENTIhub", $body_id="")
{
	$session = false;

	if (isset($_SESSION["id_user"])){
		$session = true;
	}

	
	$loginout = "";
	if ($session){
		$loginout = "<a href=\"/logout.php\">Logout</a>";
	}
	else{
		$loginout = "<a href=\"/login.php\">Login</a>";
	}

	if ($body_id != "")
		$body_id = " id=\"".$body_id."\"";
	
	echo <<<EOD
<!doctype html>
<html>
<head>
	<title>{$title}</title>
	<link rel="stylesheet" type="text/css" href="entihub.css">
</head>

<body{$body_id}>
	<header>
		<h1><a href="/index.php">ENTIhub</a></h1>
		<nav>
			<ul>
				<li><a href="/index.php">Home</a></li>
				<li><a href="/profile.php">Perfil</a></li>
				<li><a href="/users.php">Usuarios</a></li>
				<li><a href="/dashboard.php">Dashboard</a></li>
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
