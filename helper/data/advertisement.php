<?php
function getAdvertisementCountByStatus($conn, $status) {
    $status = mysqli_real_escape_string($conn, $status);
    $query = "SELECT COUNT(*) as total FROM advertisements WHERE status = '$status'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}

function getAdvertisementById($conn, $id) {
    $id = mysqli_real_escape_string($conn, $id);
    $query = "SELECT * FROM advertisements WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

function deleteAdvertisement($conn, $id) {
    $id = mysqli_real_escape_string($conn, $id);
    $query = "DELETE FROM advertisements WHERE id = '$id'";
    return mysqli_query($conn, $query);
}

function updateAdvertisement($conn, $id, $client_id, $title, $description, $start_date, $end_date, $status, $price) {
    $id = mysqli_real_escape_string($conn, $id);
    $client_id = mysqli_real_escape_string($conn, $client_id);
    $title = mysqli_real_escape_string($conn, $title);
    $description = mysqli_real_escape_string($conn, $description);
    $start_date = mysqli_real_escape_string($conn, $start_date);
    $end_date = mysqli_real_escape_string($conn, $end_date);
    $status = mysqli_real_escape_string($conn, $status);
    $price = mysqli_real_escape_string($conn, $price);
    $query = "UPDATE advertisements SET client_id = '$client_id', title = '$title', description = '$description', start_date = '$start_date', end_date = '$end_date', status = '$status', price = '$price' WHERE id = '$id'";
    return mysqli_query($conn, $query);
}

function insertAdvertisement($conn, $client_id, $title, $description, $start_date, $end_date, $status, $price) {
    $client_id = mysqli_real_escape_string($conn, $client_id);
    $title = mysqli_real_escape_string($conn, $title);
    $description = mysqli_real_escape_string($conn, $description);
    $start_date = mysqli_real_escape_string($conn, $start_date);
    $end_date = mysqli_real_escape_string($conn, $end_date);
    $status = mysqli_real_escape_string($conn, $status);
    $price = mysqli_real_escape_string($conn, $price);
    $query = "INSERT INTO advertisements (client_id, title, description, start_date, end_date, status, price) VALUES ('$client_id', '$title', '$description', '$start_date', '$end_date', '$status', '$price')";
    return mysqli_query($conn, $query);
}

function getAllAdvertisements($conn) {
    $query = "SELECT * FROM advertisements ORDER BY id DESC";
    $result = mysqli_query($conn, $query);
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    return $data;
}
?>