<?php
function getTotalRevenue($conn) {
    $query = "SELECT SUM(amount) as total FROM payments WHERE payment_status = 'lunas'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}
?>