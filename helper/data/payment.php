<?php
include __DIR__ . "/../db_conn.php";

if (!function_exists('getAllPayments')) {
    
    function getAllPayments($conn, $search = "") {
        $search = mysqli_real_escape_string($conn, $search);
        
        $query = "SELECT payments.*, advertisements.title AS ad_title, clients.name AS client_name 
                  FROM payments
                  JOIN advertisements ON payments.advertisement_id = advertisements.id
                  JOIN clients ON advertisements.client_id = clients.id";

        if (!empty($search)) {
            $query .= " WHERE clients.name LIKE '%$search%' 
                        OR advertisements.title LIKE '%$search%' 
                        OR payments.id LIKE '%$search%'";
        }

        $query .= " ORDER BY payments.id DESC";
        return mysqli_query($conn, $query);
    }

    function getPaymentById($conn, $id) {
        $id = mysqli_real_escape_string($conn, $id);
        $query = "SELECT * FROM payments WHERE id = '$id'";
        $result = mysqli_query($conn, $query);
        return mysqli_fetch_assoc($result);
    }

    function getPaymentStats($conn) {
        $income = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) AS total FROM payments WHERE payment_status = 'lunas'"))['total'] ?? 0;
        $success = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM payments WHERE payment_status = 'lunas'"))['total'] ?? 0;
        $pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM payments WHERE payment_status = 'belum_lunas'"))['total'] ?? 0;
        
        return [
            'total_income'  => $income,
            'success_count' => $success,
            'pending_count' => $pending
        ];
    }

    // membuat ID Invoice Otomatis
    function generateInvoiceId($conn, $editData = null) {
        if ($editData) {
            return "#SIMI-" . str_pad($editData['id'], 3, '0', STR_PAD_LEFT);
        }
        $row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT MAX(id) AS last_id FROM payments"));
        $next_id = ($row['last_id'] ?? 0) + 1;
        return "#SIMI-" . str_pad($next_id, 3, '0', STR_PAD_LEFT);
    }

    function getPaymentMessage() {
        if (!isset($_GET['msg'])) return null;
        
        $messages = [
            'insert_success' => ['text' => 'Data pembayaran berhasil ditambahkan!', 'type' => 'green'],
            'update_success' => ['text' => 'Data pembayaran berhasil diperbarui!', 'type' => 'blue'],
            'delete_success' => ['text' => 'Data pembayaran berhasil dihapus!', 'type' => 'yellow'],
            'error'          => ['text' => 'Terjadi kesalahan pada sistem database.', 'type' => 'red']
        ];

        return $messages[$_GET['msg']] ?? null;
    }
}
//yang baru
function getTotalRevenue($conn) {
    $query = "SELECT SUM(amount) as total FROM payments WHERE payment_status = 'lunas'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}
?>
