<?php

// Mantenemos la sesion activa
session_start();

if (isset($_POST['logout'])) {
    // Destruir todas las variables de sesión
    $_SESSION = array();

    // Borramos también las cookies de sesión.
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // Finalmente, destruir la sesión
    session_destroy();

    // Redirigir al usuario a la página de inicio de sesión
    header('Location: index.php');
    exit;
}

if (isset($_POST['redirect'])) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../library/styles.css" rel="stylesheet" type="text/css">
    <title>Wiki</title>
</head>
<header>
    <h1>Log out</h1><img id="imgTop" src="../imatges/web/Minetest_logo.svg">
</header>

<body>
    <h2>¿Cerramos sesion?</h1>
        <form action="#" method="post">
            <input type="submit" name="logout" value="Si">
            <input type="submit" name="redirect" value="No">
        </form>
</body>

</html>