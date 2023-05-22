<?php
session_start();
if (!isset($_SESSION['nom'])) {
    header('Location: login.php');
    exit();
}

// Require the autoloader
require_once 'vendor/autoload.php';

// Use the library namespace
use ProxmoxVE\Proxmox;

/*// Create your credentials array

*/
// realm and port defaults to 'pam' and '8006' but you can specify them like so
$credentials = [
    'hostname' => 'pve1',
    'username' => 'root',
    'password' => 'H0laMund0*', // e939b4d6-dbf6-4d9b-9da9-e999ae2c5456
    'realm' => 'pam',
    'port' => '8006',
];

// Conexión a Proxmox para obtener el contenido del Pool plantillas
$proxmox = new Proxmox($credentials);
$pool_id = "Plantillas";
$allNodes = $proxmox->get("/pools/$pool_id");


// Recibe por post la información necesaria para clonar la máquina virtual
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nuevaid = $proxmox->get("/cluster/nextid");
    $action = $_POST['action'];
    $node = $_POST['node'];
    $id = $_POST['id'];
    $vmid = $_POST['vmid'];
    $usuario = str_replace("@", "_", $_SESSION['nom']);
    $vmname = $_POST['vmname'];
    $Clonacion =
        array("newid" => $nuevaid['data'], "node" => $node, "full" => true, "name" => "$vmname", "pool" => "$usuario");
    if ($action == 'Clonar') {
        // realizar la acción de arrancar la máquina con ID $node_id

        $proxmox->create("/nodes/$node/qemu/$vmid/clone", $Clonacion);
        header('Location: index.php');
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Taster - Página Principal</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.vm-info').hide();
            $('.vm-title').click(function() {
                $(this).next('.vm-info').toggle();
            });
            $('.vm-button').click(function() {
                if ($(this).text() == "Arrancar") {
                    $(this).text("Parar");
                    $(this).addClass("vm-stop");
                } else {
                    $(this).text("Arrancar");
                    $(this).removeClass("vm-stop");
                }
            });
        });
    </script>
</head>

<body>
    <header>
        <h1>Taster - Clonación</h1>

        <form method="post" action="close.php">
            <button type="submit" class="logout-button">Cerrar sesión</button>
        </form>
    </header>
    <form action="index.php">
        <button class="vm-button ">volver</button>
    </form>

    <div class="vm-container">
        <h3>Plantillas Disponibles</h3>

        <?php
        // Por cada máquina dentro del pool se hace un echo con la información correspondiente y los botones para interactuar
        foreach ($allNodes['data']['members'] as $node) {
        ?>
            <div class="vm-box">
                <div class="vm-title">
                    <h4><?php echo $node['name']; ?></h4>
                </div>
                <div class="vm-info">
                    <p><strong>Nombre:</strong> <?php echo $node['name']; ?></p>
                    <p><strong>ID:</strong> <?php echo str_replace("qemu/", "", $node['id']); ?></p>
                    <p><strong>Ram:</strong> <?php echo round($node['maxmem'] / 1073741824, 2); ?>GB</p>
                    <p><strong>Disco Duro:</strong> <?php echo $node['maxdisk'] / (1024 * 1024 * 1024); ?>GB</p>

                    <form method="POST" action="">
                        <input type="hidden" name="action" value="Clonar">
                        <input type="hidden" name="node" value="<?php echo $node['node']; ?>">
                        <input type="hidden" name="id" value="<?php echo $node['name']; ?>">
                        <input type="hidden" name="vmid" value="<?php echo str_replace("qemu/", "", $node['id']); ?>">
                        <input type="text" placeholder="Nombre de la nueva máquina" name="vmname"></input>
                        <button class="vm-button" type="submit">Clonar</button>
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>
</body>

</html>