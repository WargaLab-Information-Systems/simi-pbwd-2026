<?php

session_start();
require_once '../helper/db_conn.php';
require_once '../helper/data/client.php';

// tambah
if (isset($_POST['action']) && $_POST['action'] == 'insert') {
    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $phone   = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    if (!empty($name)) {
        $query = "INSERT INTO clients (name, phone, address) VALUES ('$name', '$phone', '$address')";
        if (mysqli_query($conn, $query)) {
            header("Location: ../../pages/clients/index.php?msg=insert_success");
            exit();
        } else {
            header("Location: ../../pages/clients/index.php?msg=error&detail=" . urlencode(mysqli_error($conn)));
            exit();
        }
    } else {
        header("Location: ../../pages/clients/index.php?msg=name_empty");
        exit();
    }
}

// edit
if (isset($_POST['action']) && $_POST['action'] == 'update') {
    $id      = mysqli_real_escape_string($conn, $_POST['id']);
    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $phone   = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    if (!empty($name)) {
        $query = "UPDATE clients SET name = '$name', phone = '$phone', address = '$address' WHERE id = '$id'";
        if (mysqli_query($conn, $query)) {
            header("Location: ../../pages/clients/index.php?msg=update_success&id=" . $id);
            exit();
        } else {
            header("Location: ../../pages/clients/index.php?msg=error&detail=" . urlencode(mysqli_error($conn)));
            exit();
        }
    } else {
        header("Location: ../../pages/clients/index.php?msg=name_empty");
        exit();
    }
}

// hapus
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    
    $query = "DELETE FROM clients WHERE id = '$id'";
    if (mysqli_query($conn, $query)) {
        header("Location: index.php?msg=delete_success&id=" . $id);
        exit();
    } else {
        header("Location: index.php?msg=delete_error&detail=" . urlencode(mysqli_error($conn)));
        exit();
    }
}
// echo "masyaallah";

$action = $_POST['action'] ?? '';

if ($action === 'insert') {
    if (insertClient($conn, $_POST['name'], $_POST['phone'], $_POST['address'])) {
        header("Location: ../pages/clients/index.php");
        exit;
    }
}
?>