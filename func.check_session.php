<?php
function check_session ()
{
	session_start();

	if (!isset($_SESSION["id_user"])){
		return false;
	}

	$id_user = intval($_SESSION["id_user"]);
	
	if ($id_user <= 0){
		return false;
	}

	return $id_user;
}
?>
