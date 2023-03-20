<!DOCTYPE html>

<?php
//Incluimos el archivo de funciones
include_once "./auth/functions.php";
include_once "./auth/authFunctions.php";

// Iniciamos la sesion

session_start();

if (empty($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

$pdo = dbconnect();

$usuario = $_SESSION["username"];

?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Principal</title>
</head>

<body>
    <header>



        <div id=Usuario>
            <h2>Bienvenido <?php echo $usuario ?><h2>
                    <button id=boton><a href=logout.php>Cerrar sesión</a></button>
        </div>
    </header>
    <div id=Maquinas>


        <?php
        $maquinascant = contarMaquinas($pdo, $usuario);
        echo "<h2>Máquinas creadas: $maquinascant </h2>";
        echo "<ul>";
        for ($i = 0; $i < contarMaquinas($pdo, $usuario); $i++) {
            $maquinaid = maquinaID($pdo, $usuario);
            echo "<li>Máquina $i ( ID $maquinaid )";
            echo "<button>Encender</button>";
            echo "<button>Borrar</button>";
        };
        echo "</ul>"
        ?>

        <button id=NuevaMaquina><a href=formulariocrear.php>Crear nueva máquina</a></button>

    </div>
    <?php


    contarMaquinas($pdo, $usuario);

    ?>

</body>

</html>