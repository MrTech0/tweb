<?php

//Incluimos el archivo de funciones
include_once "./auth/functions.php";
include_once "./auth/authFunctions.php";

// Iniciamos la sesion
session_regenerate_id();
session_start();

// Declaramos las variables a utilizar
$uname = "";
$pass = "";

//Conexión a la base de datos
$pdo = dbconnect();

// Verificamos si el usuario existe realmente

if (isset($_POST["uname"]) and isset($_POST["pass"])) {
    $uname = $_POST["uname"];
    $pass = $_POST["pass"];

    $check = userVerify($pdo, $uname, $pass);
}

// Variable para guardar si el login del usuario a sido correcto o no
$check;

// Si el login a sido correcto guardamos los datos en la sesión
if ($check == true) {

    $_SESSION['username'] = $uname;
    $_SESSION['userId'] = userId($pdo, $uname);
    //$_SESSION['avatar'] = userImg($pdo, $uname);
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="./authStyle.css" rel="stylesheet" type="text/css">
</head>

<body>

    <h2>Formulari de login</h2>

    <?php
    //Comprobamos si se ha podido iniciar sesión
    if (isset($check)) {
        if ($check == true) {
            echo "<p class='correcte'> Estas loguejat correctament. Redirigin...</p>";
            echo "<meta http-equiv='refresh' content='3;url=../index.php'>";
        } else {
            echo "<p class='fallo'> ERROR: Revisa l'usuari/contraseña. Recarregant...</p>";
            echo "<meta http-equiv='refresh' content='3;url=login.php'>";
        }
    }
    ?>

    <form action="#" method="post">
        <div class="container">
            <label for="uname"><b>Usuari</b></label>
            <input type="text" placeholder="Posa el Usuari" name="uname" required>

            <label for="pass"><b>Contrasenya</b></label>
            <input type="password" placeholder="Posa la Contrasenya" name="pass" required>

            <button type="submit">Login</button>
            <label for="ncompte"><b>No tens compte?</b></label>
            <p><a href="./register.php">Crea'n un aquí</a></p>
        </div>
    </form>

</body>

</html>