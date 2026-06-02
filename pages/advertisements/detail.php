<?php
session_start();

include __DIR__ . "/../../helper/db_conn.php";
include __DIR__ . "/../../helper/data/advertisement.php"; 
$id_iklan = $_GET['id'] ?? '';

$detail_iklan = getAdvertisementById($conn, $id_iklan);

if (!$detail_iklan) {
    die("Data iklan tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Iklan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">

    <div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold mb-4"><?= htmlspecialchars($detail_iklan['title']); ?></h1>
        
        <div class="space-y-3">
            <p><strong>Harga Kontrak:</strong> Rp <?= number_format($detail_iklan['price'], 0, ',', '.'); ?></p>
            
            <p><strong>Deskripsi:</strong> <?= nl2br(htmlspecialchars($detail_iklan['description'])); ?></p>
            
            <p><strong>Status:</strong> <?= htmlspecialchars($detail_iklan['status']); ?></p>
        </div>

        <div class="mt-6 flex gap-4">
            <a href="index.php" class="bg-gray-500 text-white px-4 py-2 rounded">Kembali</a>
            
            <a href="../../logic/advertisement_process.php?action=delete&id=<?= $detail_iklan['id']; ?>" 
               onclick="return confirm('Yakin hapus?')" 
               class="bg-red-500 text-white px-4 py-2 rounded">
               Hapus Iklan
            </a>
        </div>
    </div>

</body>
</html>