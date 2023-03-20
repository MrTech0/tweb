<?php

/**
 * Función para conectarse a la base de datos
 * @return PDO conexión a la base de datos
 */
function dbconnect()
{
    $servername = "localhost";
    $username = "root_taster";
    $password = "taster_pass1";
    $dbname = "taster";

    try {
        // Se utiliza el modo de excepción para manejar errores de conexión
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    } catch (PDOException $e) {
        echo "Error al conectar a la base de datos: " . $e->getMessage();
    }

    return $conn;
}

/**
 * Función para obtener los datos de la tabla dtItems
 * @param PDO $pdo conexión a la base de datos
 * @return PDOStatement resultado de la consulta
 */
function data($pdo)
{
    $sql = "SELECT * FROM dtItems";
    $statement = $pdo->prepare($sql);
    $statement->execute();

    return $statement;
}

/**
 * Función para obtener un registro específico de la tabla dtItems
 * @param PDO $pdo conexión a la base de datos
 * @param int $id id del registro a obtener
 * @return array resultado de la consulta
 */
function oneData($pdo, $id)
{
    $sql = "SELECT Nom, Descripcio FROM dtItems WHERE id = ?";
    $statement = $pdo->prepare($sql);
    $statement->execute([$id]);
    $resultat = $statement->fetchAll();

    return $resultat;
}

/**
 * Busca la id de la máquina
 */
function maquinaID($pdo, $usuariobuscar)
{
    $sql = "SELECT idMaquina1 FROM usuarios WHERE nombre = ?";
    $statement = $pdo->prepare($sql);
    $statement->execute([$usuariobuscar]);
    $resultat = $statement->fetchAll();
    $final = $resultat[0][0];
    return $final;
}
/**
 * Cuenta las máquinas de un usuario
 * Actualizar a COUNT cuando haya base de datos definitiva
 */
function contarMaquinas($pdo, $usuariobuscar)
{
    $sql = "SELECT Cantmaquinas FROM usuarios WHERE nombre = ?";
    $statement = $pdo->prepare($sql);
    $statement->execute([$usuariobuscar]);
    $resultat = $statement->fetchAll();
    $final = $resultat[0][0];
    return $final;
    
}

/**
 * Función para obtener todos los registros de la tabla dtItems
 * @param PDO $pdo conexión a la base de datos
 * @return array resultado de la consulta
 */
function allData($pdo)
{
    $sql = "SELECT * FROM dtItems";
    $statement = $pdo->prepare($sql);
    $statement->execute();
    $resultat = $statement->fetchAll();

    return $resultat;
}

/**
 * Función para eliminar un registro de la tabla dtItems
 * @param PDO $pdo conexión a la base de datos
 * @param int $id id del registro a eliminar
 * @return bool resultado de la operación
 */
function deleteEntry($pdo, $id)
{
    // Primero se crea una consulta SQL para eliminar el registro con el id especificado
    $consultaSQL = "DELETE FROM dtItems WHERE id = ?";

    // Luego se prepara la consulta utilizando el objeto PDO
    $sentencia = $pdo->prepare($consultaSQL);

    // Se ejecuta la consulta, pasando el id como parámetro
    $sentencia->execute([$id]);

    // Se devuelve si se eliminó al menos un registro o no
    return ($sentencia->rowCount() == 1);
}

/**
 * Función para agregar un registro a la tabla dtItems
 * @param PDO $pdo conexión a la base de datos
 * @param string $nom nombre del item
 * @param string $descripcio descripción del item
 * @param string $imatge nombre del archivo de imagen
 * @return bool resultado de la operación
 */
function addEntry($pdo, $nom, $descripcio, $imatge)
{
    // Primero se crea una consulta SQL para insertar el registro con los datos especificados
    $consultaSQL = "INSERT INTO dtItems(Nom, Descripcio, ImageFile) values(?,?,?)";

    // Luego se prepara la consulta utilizando el objeto PDO
    $sentencia = $pdo->prepare($consultaSQL);

    // Se ejecuta la consulta, pasando los datos como parámetros
    $sentencia->execute([$nom, $descripcio, $imatge]);

    // Se devuelve si se agregó al menos un registro o no
    return ($sentencia->rowCount() == 1);
}

/**
 * Función para actualizar un registro de la tabla dtItems
 * @param PDO $pdo conexión a la base de datos
 * @param int $id id del registro a actualizar
 * @param string $nom nuevo nombre del item
 * @param string $descripcio nueva descripción del item
 * @return bool resultado de la operación
 */
function updateEntry($pdo, $id, $nom, $descripcio)
{
    $consultaSQL = "update dtItems set Nom = ?, Descripcio = ? where id = ? ";

    $sentencia = $pdo->prepare($consultaSQL);

    $sentencia->execute([$nom, $descripcio, $id]);

    // Se devuelve si se actualizó al menos un registro o no
    return ($sentencia->rowCount() == 1);
}
