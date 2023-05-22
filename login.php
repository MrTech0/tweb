<?php
session_regenerate_id();
session_start();

//Incluimos el archivo de funciones
include_once "./auth/functions.php";
include_once "./auth/authFunctions.php";

//Conexión a la base de datos
$pdo = dbconnect();
$nom = "";
$pass = "";

// if (isset($_POST['submit'])) {
//   $username = $_POST['username'];
//   $password = $_POST['password'];

  
//   // Comprobar que el usuario y la contraseña son correctos
//   if ($username == 'usuario' && $password == '123') {
//     // Iniciar sesión y redirigir al usuario a la página principal
//     $_SESSION['username'] = $username;
//     header('Location: index.php');
//     exit();
//   } else {
//     // Si las credenciales no son correctas, mostrar un mensaje de error
//     $error = 'Usuario o contraseña incorrectos';
//   }
// }


// Iniciamos la sesion



// Declaramos las variables a utilizar


// Verificamos si el usuario existe realmente

if (isset($_POST["nom"]) and isset($_POST["pass"])) {
    
  
  $nom = $_POST["nom"];
    $pass = $_POST["pass"];
  
    $check = userVerify($pdo, $nom, $pass);
    
}

// Variable para guardar si el login del usuario a sido correcto o no
$check;

// Si el login a sido correcto guardamos los datos en la sesión
if ($check == true) {
  echo "step4";
    $_SESSION['nom'] = $nom;
    echo "step5";
    $_SESSION['userid'] = userId($pdo, $nom);
    echo "step6";
    header('Location: index.php');
    //$_SESSION['avatar'] = userImg($pdo, $nom);
};



?>

<!DOCTYPE html>
<html>

<head>
  <title>Iniciar sesión</title>
  <link rel="stylesheet" href="style.css">
</head>


 
<body>
  <div class="container">
    <h1>Iniciar sesión</h1>
    <?php if (isset($error)) {
      echo '<p class="error">' . $error . '</p>';
    } ?>
    <form method="post" action="">
      <div class="form-group">
        <label for="nom">Usuario:</label>
        <input type="text" id="nom" name="nom">
      </div>
      <div class="form-group">
        <label for="pass">Contraseña:</label>
        <input type="password" id="pass" name="pass">
      </div>
      <div class="form-group">
        <input type="submit" name="submit" value="Iniciar sesión">
      </div>
    </form>
    <form method="post" action="register.php">
      <div class="form-group">
        <input type="submit" name="submit" value="Registrarse">
      </div>
    </form>
  </div>
</body>

</html>