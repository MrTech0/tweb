<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
var_dump($_POST);
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

// Then simply pass your credentials when creating the API client object.
$proxmox = new Proxmox($credentials);
$pool_id = "Plantillas"; // Reemplaza con el ID del pool que deseas auditar
$allNodes = $proxmox->get("/pools/$pool_id");

// print_r($allNodes);

/*$Clonacion = array(
    array("newid" => 1, "node" => 1, "vmid" => 1, "full" => true, "name" => "copia", "pool" => "hhyomin")
);*/

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   $nuevaid = $proxmox ->get("/cluster/nextid");
    $action = $_POST['action'];
    $node = $_POST['node'];
    $id = $_POST['id'];
    $vmid = $_POST['vmid'];
    $usuario = 'hhyomin';
    $vmname = $_POST['vmname'];
    $Clonacion = 
        array("newid" => $nuevaid['data'], "node" => $node, "full" => true, "name" => "$vmname", "pool" => "$usuario");
    if ($action == 'Clonar') {
        // realizar la acción de arrancar la máquina con ID $node_id
        var_dump($Clonacion);
        $proxmox->create("/nodes/$node/qemu/$vmid/clone", $Clonacion);
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

<?php // var_dump($allNodes);?>

    <form action="index.php"><button>volver</button></form>

    <div class="vm-container">
        <h3>Plantillas Disponibles</h3>



        <?php foreach ($allNodes['data']['members'] as $node) { ?>
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

        <?php
       // var_dump($allNodes);
        ?>
    </div>




</body>

</html>