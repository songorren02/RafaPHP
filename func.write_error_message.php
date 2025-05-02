<?php

function write_error_message ($message, $error_num="")
{
	if ($error_num == ""){
		$message = "Error: ".$message;
	}
	else{
		$message = "Error ".$error_num.":".$message;
	}

	echo "<p class=\"error\">".$message."</p>";
		
}

?>
