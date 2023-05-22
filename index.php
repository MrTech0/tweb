<?php
session_start();
if (!isset($_SESSION['nom'])) {
    header('Location: login.php');
    exit();
}

// datos de la solicitud
$username = 'root@pam';
$password = 'H0laMund0*';
$url = 'https://pve1:8006/api2/json/access/ticket';

// configurar la solicitud
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'username' => $username,
    'password' => $password,
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// enviar la solicitud y obtener la respuesta
$response = curl_exec($ch);
curl_close($ch);

// procesar la respuesta
$data = json_decode($response, true);


$CSRFPreventionToken = $data['data']['CSRFPreventionToken'];
$ticket2 = $data['data']['ticket'];
setcookie("PVEAuthCookie", $ticket2, time() + (86400 * 30), "/", "taster.local");

$user = $_SESSION['nom'];

?>

<!DOCTYPE html>
<html>

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
        <h1>Taster</h1>

        <form method="post" action="close.php">
            <h2 class="black">Bienvenido, <?php echo $user; ?></h2>
            <button type="submit" class="logout-button">Cerrar sesión</button>
        </form>
    </header>

    <section>

        <form action="clonar.php">
            <h2 class="black">Crea una nueva máquina virtual</h2>
            <button class="vm-button">Crear</button>
        </form>


        <?php

        // Require the autoloader
        require_once 'vendor/autoload.php';

        // Use the library namespace

        use ProxmoxVE\Proxmox;

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
        $poolname = str_replace("@", "_", $user);
        $pool_id = $poolname; // Reemplaza con el ID del pool que deseas auditar
        $allNodes = $proxmox->get("/pools/$pool_id");


        ?>

        <div class="vm-container">
            <h3>Tus máquinas virtuales</h3>

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
                        <?php if ($node['status'] == 'stopped') { ?>
                            <form method="POST" action="">
                                <input type="hidden" name="action" value="Arrancar">
                                <input type="hidden" name="node" value="<?php echo $node['node']; ?>">
                                <input type="hidden" name="id" value="<?php echo $node['name']; ?>">
                                <input type="hidden" name="vmid" value="<?php echo str_replace("qemu/", "", $node['id']); ?>">
                                <button class="vm-button" type="submit">Arrancar</button>
                            </form>
                        <?php } else { ?>
                            <form method="POST" action="">
                                <input type="hidden" name="action" value="Apagar">
                                <input type="hidden" name="node" value="<?php echo $node['node']; ?>">
                                <input type="hidden" name="id" value="<?php echo $node['name']; ?>">
                                <input type="hidden" name="vmid" value="<?php echo str_replace("qemu/", "", $node['id']); ?>">
                                <button class="vm-button vm-stop" type="submit">Apagar</button>

                            </form>
                        <?php } ?>
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="Eliminar">
                            <input type="hidden" name="node" value="<?php echo $node['node']; ?>">
                            <input type="hidden" name="id" value="<?php echo $node['name']; ?>">
                            <input type="hidden" name="vmid" value="<?php echo str_replace("qemu/", "", $node['id']); ?>">
                            <button class="vm-button vm-stop" type="submit">Eliminar</button>

                        </form>




                        <?php
                        // Generar la URL del iframe con los parámetros necesarios
                        $iframe_url = "https://pve1.taster.local:8006/?console=kvm&novnc=1&vmid=" . str_replace("qemu/", "", $node['id']) . "&vmname=" . $node['name'] . "&node=" . $node['node'] . "&resize=off&cmd=";
                        ?>
                        <button onclick="window.open('<?php echo $iframe_url; ?>', '_blank')" class="vm-button">Abrir en nueva pestaña</button>

                    </div>

                </div>
            <?php } ?>



            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $action = $_POST['action'];
                $node = $_POST['node'];
                $vmid = $_POST['vmid'];
                $nombre = $_POST['id'];

                if ($action == 'Arrancar') {
                    $ticket = array(
                        'username' => 'root',
                        'password' => 'H0laMund0*',
                        'realm' => 'pam',
                    );

                    // realizar la acción de arrancar la máquina con ID $node_id
                    $proxmox->create("/nodes/$node/qemu/$vmid/status/start");

                    // Generar la URL del iframe con los parámetros necesarios
                    $iframe_url = "https://pve1.taster.local:8006/?console=kvm&novnc=1&vmid=$vmid&vmname=$nombre&node=$node&resize=off&cmd=";

                    unset($_POST['action']);
                } else if ($action == 'Apagar') {
                    // realizar la acción de apagar la máquina con ID $node_id
                    $proxmox->create("/nodes/$node/qemu/$vmid/status/stop");
                    unset($_POST['action']);
                } else if ($action == 'Eliminar') {
                    $proxmox->delete("/nodes/$node/qemu/$vmid");
                    unset($_POST['action']);
                }
            };
            ?>

        </div>

    </section>
</body>

</html>