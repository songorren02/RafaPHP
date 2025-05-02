<?php

function write_message ($message_info)
{
	$id_user = 0;

	if (isset($_SESSION["id_user"])){
		$id_user = intval($_SESSION["id_user"]);
	}

	$delete_link = "";

	if ($id_user == $message_info["id_user"]){
		$delete_link = <<<EOD
<p class="message-delete"><a href="message_delete.php?id_message={$message_info["id_message"]}">Eliminar</a></p>
EOD;
	}

	echo <<<EOD
<section class="message">
<h3><a href="profile.php?user={$message_info["username"]}">{$message_info["name"]}</a></h3>
<p class="message-text">{$message_info["message"]}</p>
<p class="message-date">{$message_info["post_time"]}</p>
{$delete_link}
</section>
EOD;
}

?>
