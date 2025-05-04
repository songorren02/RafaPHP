<?php
require_once("func.check_session.php");

#Comprobar sesion
$session = check_session();

if (!$session){
	header("Location: login.php");
	exit();
}


#Comprobar si existen los campos
if (!isset($_POST["username"]) || !isset($_POST["email"]) || !isset($_POST["name"]) || !isset($_POST["date"]) ){
    echo "Error 1: Formulario no enviado";
    exit();
}

//Username Checks

$username = trim($_POST["username"]);

if (strlen($username) <= 2){
    echo "Error 2a: Nombre de usuario muy corto";
    exit();
}

$username = str_replace(" ","",$username);

if (strlen($username) > 16){
    echo "Error 2b: Nombre de usuario muy largo";
    exit();
}


$username_tmp = addslashes($username);

if ($username_tmp !== $username){
    echo "Error 2c: Nombre con caracteres no váldos";
    exit();
}

$username = $username_tmp;


# Check name

$name = trim($_POST["name"]);

if (strlen($name) <= 2){
    echo "Error 3a: Nombre muy corto";
    exit();
}

$name = str_replace("  ","",$name);

if (strlen($name) > 32){
    echo "Error 3b: Nombre muy largo";
    exit();
}


$name_tmp = addslashes($name);

if ($name_tmp != $name){
    echo "Error 3c: Nombre no váldos";
    exit();
}



// Remove all illegal characters from email https://www.w3schools.com/Php/filter_validate_email.asp 

$email = trim($_POST["email"]);

if (strlen($email) > 48){
    echo "Error 4a: Mail muy largo";
    exit();
}

$email = addslashes(filter_var($email, FILTER_SANITIZE_EMAIL));

// Validar email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "4b Formato mail incorrecto";
    exit();
}


$date = ($_POST["date"]);
// Separar por mes, año y dia
list($any, $mes, $dia) = explode("-", $date);

// Convertir a enteros
$any = intval($any);
$mes = (int) $mes;
$dia = (int) $dia;

    // Verificar la fecha
if (!checkdate($mes, $dia, $any)) {
    echo "La data $date no és vàlida.";
    exit();
}


require_once("db_conf.php");

#Conexion con la db
$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_db);

#Actualizar campos del user
$query = <<<EOD
UPDATE
	users
SET
	name='{$name}',
	username='{$username}',
	email='{$email}',
	birthdate='{$date}'
WHERE
	id_user={$session};
EOD;


$resultado = mysqli_query($conn, $query);

if (!$resultado){
    echo "Error 4: Peticion incorrecta";
    exit();
}

header("Location: dashboard.php");
exit();

?>
