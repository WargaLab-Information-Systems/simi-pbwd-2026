<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../pages/auth/login.php"); exit; }

require_once __DIR__ . '/../helper/db_conn.php';
require_once __DIR__ . '/../helper/data/advertisement.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'insert') {
    $result = insertAdvertisement($conn, $_POST['client_id'], $_POST['title'], $_POST['description'], $_POST['start_date'], $_POST['end_date'], $_POST['status'], $_POST['price']);
    if ($result) {
        header("Location: ../pages/advertisements/index.php");
        exit;
    } else {
        die("Gagal menambah data iklan: " . mysqli_error($conn));
    }
} elseif ($action === 'update') {
    $result = updateAdvertisement($conn, $_POST['id'], $_POST['client_id'], $_POST['title'], $_POST['description'], $_POST['start_date'], $_POST['end_date'], $_POST['status'], $_POST['price']);
    if ($result) {
        header("Location: ../pages/advertisements/index.php");
        exit;
    } else {
        die("Gagal mengubah data iklan: " . mysqli_error($conn));
    }
} elseif ($action === 'delete') {
    if (!empty($_GET['id'])) {
        $result = deleteAdvertisement($conn, $_GET['id']);
        if ($result === 'blocked') {
            header("Location: ../pages/advertisements/index.php?msg=delete_blocked");
            exit;
        } elseif ($result) {
            header("Location: ../pages/advertisements/index.php");
            exit;
        } else {
            die("Gagal menghapus data iklan: " . mysqli_error($conn));
        }
    }
} else {
    header("Location: ../pages/advertisements/index.php");
    exit;
}
