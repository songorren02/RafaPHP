<?php

if (!isset($_POST["username"]) || !isset($_POST["password"]) || !isset($_POST["repassword"]) || !isset($_POST["email"]) || !isset($_POST["name"]) || !isset($_POST["date"]) ){
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

$name = str_replace(" ","",$name);

if (strlen($name) > 32){
    echo "Error 3b: Nombre muy largo";
    exit();
}


$name_tmp = addslashes($name);

if ($name_tmp != $name){
    echo "Error 3c: Nombre no váldos";
    exit();
}



#Password Checks

$password = trim($_POST["password"]);
$password_check = trim($_POST["repassword"]);

if ($password_check !== $password){
    echo "Error 3c: Passwords distintos";
    exit();
}

if (strlen($password) < 4){
    echo "Error 4b: Password de usuario muy corto";
    exit();
}

$password = str_replace(" ","",$password);

if (strlen($password) > 26){
    echo "Error 4c: Password de usuario muy largo";
    exit();
}


$password_tmp = addslashes($password);

if ($password_tmp !== $password){
    echo "Error 4d: Password con caracteres no váldos";
    exit();
}

$password = $password_tmp;


#Password Check Checks

if (strlen($password_check) < 4){
    echo "Error 3a: Password de usuario muy corto";
    exit();
}

$password_check= str_replace(" ","",$password_check);

if (strlen($password_check) > 26){
    echo "Error 3b: Password de usuario muy largo";
    exit();
}


$password_check_tmp = addslashes($password_check);

if ($password_check_tmp != $password_check){
    echo "Error 3c: Password con caracteres no váldos";
    exit();
}


$password_check = $password_check_tmp;

if($password != $password_check){
    echo "Error 4a: Password no concuerdan";
    exit();

}



// Remove all illegal characters from email https://www.w3schools.com/Php/filter_validate_email.asp 

$email = trim($_POST["email"]);

if (strlen($email) > 48){
    echo "Error 4a: Mail muy largo";
    exit();
}

$email = addslashes(filter_var($email, FILTER_SANITIZE_EMAIL));

// Validate e-mail
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "4b Formato mail incorrecto";
    exit();
}




$date = ($_POST["date"]);
// Separem l'any, mes i dia
list($any, $mes, $dia) = explode("-", $date);

// Convertim a enters
$any = intval($any);
$mes = (int) $mes;
$dia = (int) $dia;

    // Verifiquem la data
if (!checkdate($mes, $dia, $any)) {
    echo "La data $date no és vàlida.";
    exit();
}


require_once("db_conf.php");

$conn = mysqli_connect($db_server, $db_user, $db_pass, $db_db);

$query = <<<EOD
SELECT id_user
FROM users
WHERE username='{$username}'
EOD;
$resultado = mysqli_query($conn, $query);
if (mysqli_num_rows($resultado) != 0){
    echo "Error 2d: Usuario existente";
    exit();
}


$query = <<<EOD
SELECT id_user FROM users
WHERE email='{$email}'
EOD;

$resultado = mysqli_query($conn, $query);

if (mysqli_num_rows($resultado) != 0){
    echo "Error 4c: Email existente";
    exit();
}



$password = md5($password);


$query = <<<EOD
INSERT INTO users (`name`, username, email, birthdate, `password`)
VALUES ('{$name}', '{$username}', '{$email}', '{$date}', '{$password}');
EOD;

// echo $query;
// Nos connectamos a la base de datos y hacemos la petición


$resultado = mysqli_query($conn, $query);

if (!$resultado){
    echo "Error 4: Peticion incorrecta";
    exit();
}

header("Location: login.php");
exit();

?>
