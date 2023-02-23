<?php

/**
 * Función para comprobar que no exista un usuario con el mismo nombre
 * @param PDO $pdo conexión a la base de datos
 * @param string $usuari nombre del usuario
 * @return bool resultado de la operación
 */
function userExist($pdo, $usuari)
{
    $sql = "SELECT id FROM logins WHERE Nom = ?";
    $statement = $pdo->prepare($sql);
    $statement->execute([trim($usuari)]);
    $resultat = $statement->fetch();

    // Si existeix un usuari a la BD 
    // Poso > 0 perquè quan fas proves poden haver-hi més d'un registre amb el mateix email 
    //return ($statement->rowCount() > 0);

    return ($statement->rowCount() == 1);
}

/**
 * Función para recuperar el id del usuario
 * @param PDO $pdo conexión a la base de datos
 * @param string $usuari nombre del usuario
 * @return int resultado de la operación
 */
function userId($pdo, $usuari)
{
    $sql = "SELECT id FROM logins WHERE Nom = ?";
    $statement = $pdo->prepare($sql);
    $statement->execute([trim($usuari)]);
    return $statement->fetchColumn();
}

/**
 * Función para recuperar el nombre del archivo del avatar del usuario
 * @param PDO $pdo conexión a la base de datos
 * @param string $usuari nombre del usuario
 * @return string resultado de la operación
 */
function userImg($pdo, $usuari)
{
    $sql = "SELECT Avatar FROM logins WHERE Nom = ?";
    $statement = $pdo->prepare($sql);
    $statement->execute([trim($usuari)]);
    return $statement->fetchColumn();
}

/**
 * Función para verificar que el String tiene el formato de un correo electronico, aun que no exista
 * @param string $str string a verificar
 * @return bool Devuelve true en el caso de que sea un correo. Devulve false si no lo es
 */
function is_valid_email($str)
{
    // return (false !== filter_var($str, FILTER_VALIDATE_EMAIL));
    return filter_var($str, FILTER_VALIDATE_EMAIL);
}

/**
 * Función para añadir un usuario
 * @param PDO $pdo conexión a la base de datos
 * @param string $nom nombre del usuario
 * @param string $descripcio descripción del usuario
 * @param string $imatge nombre del archivo del Avatar
 * @return bool resultado de la operación
 */
function addUser($pdo, $nom, $descripcio, $pass, $avatar)
{

    if (userExist($pdo, $nom)) {
        return false;
    };
    // Primero se crea una consulta SQL para insertar el registro con los datos especificados
    $consultaSQL = "INSERT INTO logins(Nom, Descripcio, Pass, Avatar) values(?,?,?,?)";

    // Luego se prepara la consulta utilizando el objeto PDO
    $sentencia = $pdo->prepare($consultaSQL);

    // Se ejecuta la consulta, pasando los datos como parámetros
    $sentencia->execute([trim($nom), $descripcio, password_hash($pass, PASSWORD_DEFAULT), $avatar]);

    // Se devuelve true si se agregó y false en el caso que y/o existe previamente

    return ($sentencia->rowCount() == 1);
}

/**
 * Función para verificar las credenciales del login
 * @param PDO $pdo conexión a la base de datos
 * @param string $nom nombre del usuario
 * @return bool resultado de la operación
 */

function userVerify($pdo, $nom, $passCheck)
{
    if (!userExist($pdo, $nom)) {
        return false;
    } else {
        $sql = "SELECT Pass FROM logins WHERE Nom = ?";
        $statement = $pdo->prepare($sql);
        $statement->execute([trim($nom)]);
        $hashPass = $statement->fetchColumn();

        return password_verify($passCheck, $hashPass);
    }
};
