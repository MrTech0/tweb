<?php

//Incluimos el archivo de funciones
include_once "auth/functions.php";
include_once "auth/authFunctions.php";

//Conexión a la base de datos
$pdo = dbconnect();
$uname = "";
$desc = "";
$pass = "";
$avatar = "";

// guardamos los datos del formualrio en variables
if (isset($_POST["uname"]) and isset($_POST["desc"]) and isset($_POST["pass"])) {
    // and isset($_POST["avatar"])
    $uname = $_POST["uname"];
    $desc = $_POST["desc"];
    $pass = $_POST["pass"];
    //$avatar = $_POST["avatar"];
}

//Comprovamos que el usuario no esté vacio y que tenga el formato de un correo
//if (is_valid_email(isset($uname) and isset($pass)) {
if (is_valid_email($uname) and isset($pass)) {
    $check_insert = addUser($pdo, $uname, $desc, $pass, $avatar);
} elseif ($uname != "") {
    echo "<p class='fallo'> ERROR: Revisa que el usuario cumpla con el formato de correo y el nombre del avatar </p>";
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="/authStyle.css" rel="stylesheet" type="text/css">
</head>

<body>

    <h2>Formulari de Registre</h2>

    <?php
    //Comprobamos si se ha ejecutado correctamente la función
    if (isset($check_insert)) {

        if ($check_insert == true) {
            echo "<p class='correcte'> Registrat correctament. Redirigint...</p>";
            echo "<meta http-equiv='refresh' content='3;url=/login.php'>";
        }/* else {
            echo "<p class='fallo'> ERROR: Revisa que el usuario cumpla con el formato de correo y el nombre del avatar </p>";
        }*/
    }
    ?>

    <form action="#" method="post">
        <div class="container">
            <label for="uname"><b>Usuari</b></label>
            <input type="text" placeholder="Posa l'usuari (Ha de ser un correu electronic)" name="uname" required>

            <label for="pass"><b>Contrasenya</b></label>
            <input type="password" placeholder="Posa la Contrasenya" name="pass" required>

            <label for="desc"><b>Descripció</b></label>
            <input type="textarea" placeholder="Posa una breu descripció" name="desc" required>

            <label for="avatar"><b>Avatar</b></label>
            <input type="text" placeholder="Posa el nom del arxiu" name="avatar" required>

            <button type="submit" name="submit">Register</button>
            <label for="scompte"><b>Ja tens compte?</b></label>
            <p><a href="login.php">Logina't</a></p>
        </div>
    </form>

</body>

</html>