<?php
require_once __DIR__ . '/../helper/db_conn.php';

if (isset($_POST['action'])) {
    $action           = $_POST['action'];
    $advertisement_id = mysqli_real_escape_string($conn, $_POST['advertisement_id']);
    $amount           = floatval($_POST['amount']);
    $payment_date     = mysqli_real_escape_string($conn, $_POST['payment_date']);
    $notes            = mysqli_real_escape_string($conn, $_POST['notes']);

    $ad_query = mysqli_query($conn, "SELECT price FROM advertisements WHERE id = '$advertisement_id'");
    $ad_data = mysqli_fetch_assoc($ad_query);
    $total_price = floatval($ad_data['price'] ?? 0);

    $exclude_id_condition = "";
    if ($action == 'update' && isset($_POST['id'])) {
        $current_id = mysqli_real_escape_string($conn, $_POST['id']);
        $exclude_id_condition = " AND id != '$current_id'";
    }

    $history_query = mysqli_query($conn, "SELECT SUM(amount) AS total_terbayar FROM payments WHERE advertisement_id = '$advertisement_id' $exclude_id_condition");
    $history_data = mysqli_fetch_assoc($history_query);
    $sudah_dibayar_sebelumnya = floatval($history_data['total_terbayar'] ?? 0);

    $sisa_hutang_riil = $total_price - $sudah_dibayar_sebelumnya;

    if ($amount > $sisa_hutang_riil) {
        $amount = $sisa_hutang_riil;
    }

    $total_akumulasi = $sudah_dibayar_sebelumnya + $amount;

    $payment_status = ($total_akumulasi >= $total_price) ? 'lunas' : 'belum_lunas';

    // tambah
    if ($action == 'insert') {
        $query = "INSERT INTO payments (advertisement_id, amount, payment_date, payment_status, notes) 
                VALUES ('$advertisement_id', '$amount', '$payment_date', '$payment_status', '$notes')";
        $status = mysqli_query($conn, $query) ? 'insert_success' : 'error';

    // update
    } elseif ($action == 'update') {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $query = "UPDATE payments SET 
                    advertisement_id = '$advertisement_id', 
                    amount = '$amount', 
                    payment_date = '$payment_date', 
                    payment_status = '$payment_status', 
                    notes = '$notes' 
                WHERE id = '$id'";
        $status = mysqli_query($conn, $query) ? 'update_success' : 'error';
    }

    header("Location: ../pages/payments/index.php?msg=" . $status);
    exit();
}

// hapus
if (isset($_GET['delete'])) {
    $id_payment = mysqli_real_escape_string($conn, $_GET['delete']);

    // 1. Ambil advertisement_id sebelum datanya dihapus
    $get_ad_query = mysqli_query($conn, "SELECT advertisement_id FROM payments WHERE id = '$id_payment'");
    $payment_data = mysqli_fetch_assoc($get_ad_query);

    if ($payment_data) {
        $advertisement_id = $payment_data['advertisement_id'];

        // 2. Hapus semua pembayaran yang terikat dengan iklan ini
        $query_delete_all = "DELETE FROM payments WHERE advertisement_id = '$advertisement_id'";
        
        if (mysqli_query($conn, $query_delete_all)) {
            
            // Trik Kunci: Menggunakan ob_clean atau langsung mengarahkan dengan kejam agar cache browser mati
            if (!headers_sent()) {
                header("Cache-Control: no-cache, must-revalidate"); // Tolak cache browser
                header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Tanggal usang agar langsung refresh
                header("Location: ../pages/payments/index.php?msg=delete_success");
                exit();
            } else {
                // Jika header telat dikirim, paksa pakai Javascript redirect
                echo "<script>window.location.href='../pages/payments/index.php?msg=delete_success';</script>";
                exit();
            }
            
        } else {
            header("Location: ../pages/payments/index.php?msg=error");
            exit();
        }
    } else {
        // Jika pada klik kedua data sudah hilang dari DB, dia akan langsung lari ke sini
        header("Location: ../pages/payments/index.php?msg=already_deleted");
        exit();
    }
}
