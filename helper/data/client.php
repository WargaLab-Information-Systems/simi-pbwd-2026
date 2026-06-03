<?php
function getAllClients($conn) {
    $query = "SELECT c.*, COUNT(a.id) as jumlah_iklan 
            FROM clients c 
            LEFT JOIN advertisements a ON c.id = a.client_id 
            GROUP BY c.id 
            ORDER BY c.id DESC";
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
