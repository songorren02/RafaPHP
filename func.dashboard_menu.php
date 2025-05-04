<?php

#Mostrar el menu del dashboard
function dashboard_menu ()
{
	echo <<<EOD
<aside id="dashboard_sections">
	<nav id="dashboard_nav">
		<h2>Navegación del Dashboard</h2>
		<menu>
			<li><a href="dashboard_profile.php">Perfil</a></li>
			<li><a href="dashboard_message.php">Mensajes</a></li>
		</menu>
	</nav>
</aside>
EOD;
}
?>
