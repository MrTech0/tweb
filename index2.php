<?php

// Require the autoloader
require_once 'vendor/autoload.php';

// Use the library namespace
use ProxmoxVE\Proxmox;

/*// Create your credentials array
$credentials = [
    'hostname' => 'pve1',  // Also can be an IP
    'username' => 'root',
    'password' => 'H0laMund0*',
];
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
$pool_id = "hhyomin"; // Reemplaza con el ID del pool que deseas auditar
$allNodes = $proxmox->get("/pools/$pool_id");

print_r($allNodes);