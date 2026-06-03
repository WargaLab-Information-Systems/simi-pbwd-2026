<?php

session_start();
require_once __DIR__ . '/../helper/db_conn.php';
require_once __DIR__ . '/../helper/data/client.php';

// tambah
if (isset($_POST['action']) && $_POST['action'] == 'insert') {
    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $phone   = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    if (!empty($name)) {
        $query = "INSERT INTO clients (name, phone, address) VALUES ('$name', '$phone', '$address')";
        if (mysqli_query($conn, $query)) {
            header("Location: ../pages/clients/index.php?msg=insert_success");
            exit();
        } else {
            header("Location: ../pages/clients/index.php?msg=error&detail=" . urlencode(mysqli_error($conn)));
            exit();
        }
    } else {
        header("Location: ../pages/clients/index.php?msg=name_empty");
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
            header("Location: ../pages/clients/index.php?msg=update_success&id=" . $id);
            exit();
        } else {
            header("Location: ../pages/clients/index.php?msg=error&detail=" . urlencode(mysqli_error($conn)));
            exit();
        }
    } else {
        header("Location: ../pages/clients/index.php?msg=name_empty");
        exit();
    }
}

// hapus
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);

    // Cek apakah klien masih punya iklan terkait
    $cek_iklan = mysqli_query($conn, "SELECT COUNT(*) as total FROM advertisements WHERE client_id = '$id'");
    $row_cek   = mysqli_fetch_assoc($cek_iklan);
    $jumlah_iklan = intval($row_cek['total']);

    if ($jumlah_iklan > 0) {
        // Klien masih dipakai, tidak boleh dihapus
        header("Location: ../pages/clients/index.php?msg=delete_blocked&jumlah=" . $jumlah_iklan);
        exit();
    }

    // Aman dihapus
    $query = "DELETE FROM clients WHERE id = '$id'";
    if (mysqli_query($conn, $query)) {
        header("Location: ../pages/clients/index.php?msg=delete_success&id=" . $id);
        exit();
    } else {
        header("Location: ../pages/clients/index.php?msg=delete_error&detail=" . urlencode(mysqli_error($conn)));
        exit();
    }
}
