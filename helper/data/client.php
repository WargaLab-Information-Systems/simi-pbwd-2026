<?php

include __DIR__ . "/../../helper/db_conn.php";

// tambah
if (isset($_POST['action']) && $_POST['action'] == 'insert') {
    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $phone   = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

    if (!empty($name)) {
        $query = "INSERT INTO clients (name, phone, address) VALUES ('$name', '$phone', '$address')";
        if (mysqli_query($conn, $query)) {
            $message = "Klien baru berhasil didaftarkan!";
            $messageType = "green";
        } else {
            $message = "Gagal menambah klien: " . mysqli_error($conn);
            $messageType = "red";
        }
    } else {
        $message = "Nama klien wajib diisi yaaaaaa!";
        $messageType = "red";
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
            $message = "Data klien ID #$id berhasil diperbarui!";
            $messageType = "blue";
        } else {
            $message = "Gagal memperbarui data: " . mysqli_error($conn);
            $messageType = "red";
        }
    } else {
        $message = "Nama tidak boleh kosong!";
        $messageType = "red";
    }
}

// hapus
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    
    $query = "DELETE FROM clients WHERE id = '$id'";
    
    if (mysqli_query($conn, $query)) {
        $message = "Data klien ID #$id berhasil dihapus dari sistem.";
        $messageType = "yellow";
    } else {
        $message = "Gagal menghapus klien. Pastikan klien tidak memiliki iklan aktif terikat: " . mysqli_error($conn);
        $messageType = "red";
    }
}

//membaca data berdasarkan id untuk edit
$editData = null;
if (isset($_GET['edit'])) {
    $edit_id = mysqli_real_escape_string($conn, $_GET['edit']);
    $query_edit = "SELECT * FROM clients WHERE id = '$edit_id'";
    $res_edit = mysqli_query($conn, $query_edit);
    $editData = mysqli_fetch_assoc($res_edit);
}
// omegattttt
// membaca data
$query_all = "SELECT * FROM clients ORDER BY id DESC";
$result_all = mysqli_query($conn, $query_all);

function getAllClients($conn) {
    $query = "SELECT * FROM clients ORDER BY id DESC";
    $result = mysqli_query($conn, $query);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}

function insertClient($conn, $name, $phone, $address) {
    $name = mysqli_real_escape_string($conn, $name);
    $phone = mysqli_real_escape_string($conn, $phone);
    $address = mysqli_real_escape_string($conn, $address);
    $query = "INSERT INTO clients (name, phone, address) VALUES ('$name', '$phone', '$address')";
    return mysqli_query($conn, $query);
}
?>