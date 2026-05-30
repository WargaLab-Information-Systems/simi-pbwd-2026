<?php
include __DIR__ . "/../db_conn.php";

if (!function_exists('getAllPayments')) {
    
    function getAllPayments($conn, $search = "") {
        $search = mysqli_real_escape_string($conn, $search);
        
        $query = "SELECT p1.*, 
                         advertisements.title AS ad_title, 
                         advertisements.price AS total_price, 
                         clients.name AS client_name,
                         (SELECT SUM(amount) FROM payments WHERE advertisement_id = p1.advertisement_id) AS total_paid
                  FROM payments p1
                  JOIN advertisements ON p1.advertisement_id = advertisements.id
                  JOIN clients ON advertisements.client_id = clients.id
                  WHERE p1.id IN (
                      SELECT MAX(id) FROM payments GROUP BY advertisement_id
                  )";

        if (!empty($search)) {
            $query .= " AND (clients.name LIKE '%$search%' 
                        OR advertisements.title LIKE '%$search%' 
                        OR p1.id LIKE '%$search%')";
        }

        $query .= " ORDER BY p1.id DESC";
        return mysqli_query($conn, $query);
    }

    function getPaymentById($conn, $id) {
        $id = mysqli_real_escape_string($conn, $id);
        $query = "SELECT * FROM payments WHERE id = '$id'";
        $result = mysqli_query($conn, $query);
        return mysqli_fetch_assoc($result);
    }

    function getPaymentStats($conn) {
        $income = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) AS total FROM payments"))['total'] ?? 0;
        
        $success = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM payments WHERE id IN (SELECT MAX(id) FROM payments GROUP BY advertisement_id) AND payment_status = 'lunas'"))['total'] ?? 0;
        
        $pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM payments WHERE id IN (SELECT MAX(id) FROM payments GROUP BY advertisement_id) AND payment_status = 'belum_lunas'"))['total'] ?? 0;
        
        return [
            'total_income'  => $income,
            'success_count' => $success,
            'pending_count' => $pending
        ];
    }

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