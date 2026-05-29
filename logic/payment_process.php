<?php
//ubungkan ke koneksi database
include __DIR__ . "/../helper/db_conn.php";


// 2. Ambil data dari form POST
if (isset($_POST['action'])) {
    $action           = $_POST['action'];
    $advertisement_id = mysqli_real_escape_string($conn, $_POST['advertisement_id']);
    $amount           = mysqli_real_escape_string($conn, $_POST['amount']);
    $payment_date     = mysqli_real_escape_string($conn, $_POST['payment_date']);
    $payment_status   = mysqli_real_escape_string($conn, $_POST['payment_status']);
    $notes            = mysqli_real_escape_string($conn, $_POST['notes']);

    // JIKA TAMBAH DATA BARU
    if ($action == 'insert') {
        $query = "INSERT INTO payments (advertisement_id, amount, payment_date, payment_status, notes) 
                  VALUES ('$advertisement_id', '$amount', '$payment_date', '$payment_status', '$notes')";
        
        // Jalankan query ke database
        $status = mysqli_query($conn, $query) ? 'insert_success' : 'error';
        
    // JIKA UPDATE DATA LAMA
    } elseif ($action == 'update') {
        $id = mysqli_real_escape_string($conn, $_POST['id']);
        $query = "UPDATE payments SET 
                    advertisement_id = '$advertisement_id', 
                    amount = '$amount', 
                    payment_date = '$payment_date', 
                    payment_status = '$payment_status', 
                    notes = '$notes' 
                  WHERE id = '$id'";
                  
        // Jalankan query ke database
        $status = mysqli_query($conn, $query) ? 'update_success' : 'error';
    }

    // ================================================================
    // REKAYASA REDIRECT: Di bawah ini yang membuat halaman otomatis 
    // langsung balik ke index.php setelah data sukses tersimpan!
    // ================================================================
    header("Location: ../pages/payments/index.php?msg=" . $status);
    exit(); // Menghentikan baris kode di bawah agar tidak ikut tereksekusi
}

// 3. Proses hapus data (jika tombol hapus diklik)
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $query = "DELETE FROM payments WHERE id = '$id'";
    
    $status = mysqli_query($conn, $query) ? 'delete_success' : 'error';
    
    // Otomatis balik ke index.php setelah hapus data
    header("Location: ../pages/payments/index.php?msg=" . $status);
    exit();
}