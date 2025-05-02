<?php

session_start();

session_destroy();

$name = "manolo";

//$_COOKIE["logout"] = $name;

setcookie("logout", $name);

header("Location: index.php");

exit();

?>
