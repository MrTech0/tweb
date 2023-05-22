<?php

//Incluimos el archivo de funciones
include_once "./auth/functions.php";
include_once "./auth/authFunctions.php";
//Conexión a la base de datos
$pdo = dbconnect();
$nom = "";
$pass = "";

// Require the autoloader
require_once 'vendor/autoload.php';

// Use the library namespace

use ProxmoxVE\Proxmox;

// Credenciales para identificarse en proxmox
$credentials = [
    'hostname' => 'pve1',
    'username' => 'root',
    'password' => 'H0laMund0*', // e939b4d6-dbf6-4d9b-9da9-e999ae2c5456
    'realm' => 'pam',
    'port' => '8006',
];

// Then simply pass your credentials when creating the API client object.
$proxmox = new Proxmox($credentials);



// guardamos los datos del formualrio en variables
if (isset($_POST["nom"]) and isset($_POST["pass"])) {
   
    $nom = $_POST["nom"];
    $pass = $_POST["pass"];
   
}

//Comprovamos que el usuario no esté vacio y que tenga el formato de un correo
if (is_valid_email($nom) and isset($pass)) {
    $check_insert = addUser($pdo, $nom, $pass);
    $poolname = str_replace("@", "_", $nom);
    $poolData = [
        'poolid' => $poolname
    ];
    // Creamos la pool del usuario registrado
    var_dump($poolData);
    $prueba = $proxmox->createPool($poolData);
    var_dump($prueba);
} elseif ($nom != "") {
    echo "<p class='fallo'> ERROR: Revisa que el usuario cumpla con el formato de correo </p>";
}


?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>

    <h2>Formulario de Registro</h2>

    <?php
    //Comprobamos si se ha ejecutado correctamente la función
    if (isset($check_insert)) {

        if ($check_insert == true) {
            echo "<p class='correcte'> Registrat correctament. Redirigint...</p>";
            echo "<meta http-equiv='refresh' content='3;url=./login.php'>";
        }/* else {
            echo "<p class='fallo'> ERROR: Revisa que el usuario cumpla con el formato de correo y el nombre del avatar </p>";
        }*/
    }
    ?>

    <form action="#" method="post">
        <div class="container">
            <label for="nom"><b>Usuario</b></label>
            <input type="text" placeholder="Usuario ( con formato de correo electrónico )" name="nom" required>

            <label for="pass"><b>Contraseña</b></label>
            <input type="password" placeholder="Contraseña" name="pass" required>
            <button type="submit" name="submit" class="vm-button">Registrar</button>
            <label for="scompte"><b>¿Ya estás registrado?</b></label>
            <p><a href="login.php">Inicia sesión</a></p>
        </div>
    </form>

</body>

</html>