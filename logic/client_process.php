<?php
session_start();
require_once '../helper/db_conn.php';
require_once '../helper/data/client.php';

$action = $_POST['action'] ?? '';

if ($action === 'insert') {
    if (insertClient($conn, $_POST['name'], $_POST['phone'], $_POST['address'])) {
        header("Location: ../pages/clients/index.php");
        exit;
    }
}
?>