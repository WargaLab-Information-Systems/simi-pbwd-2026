<?php
include __DIR__ . "/../helper/db_conn.php";


if (isset($_POST['action'])) {
    $action           = $_POST['action'];
    $advertisement_id = mysqli_real_escape_string($conn, $_POST['advertisement_id']);
    $amount           = mysqli_real_escape_string($conn, $_POST['amount']);
    $payment_date     = mysqli_real_escape_string($conn, $_POST['payment_date']);
    $payment_status   = mysqli_real_escape_string($conn, $_POST['payment_status']);
    $notes            = mysqli_real_escape_string($conn, $_POST['notes']);

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

if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $query = "DELETE FROM payments WHERE id = '$id'";
    
    $status = mysqli_query($conn, $query) ? 'delete_success' : 'error';
    
    header("Location: ../pages/payments/index.php?msg=" . $status);
    exit();
}