<?php
session_start();

// Proteksi Halaman: Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit; 
}

include __DIR__ . "/../../helper/db_conn.php";
include __DIR__ . "/../../helper/data/advertisement.php"; 

$id_iklan = $_GET['id'] ?? '';
$detail_iklan = getAdvertisementById($conn, $id_iklan);

if (!$detail_iklan) {
    die("Data iklan tidak ditemukan.");
}

// Menentukan warna badge berdasarkan status iklan secara dinamis
$status = strtolower($detail_iklan['status'] ?? '');
if ($status === 'aktif' || $status === 'active') {
    $status_classes = 'bg-green-50 text-green-700 border-green-200';
} elseif ($status === 'selesai' || $status === 'completed') {
    $status_classes = 'bg-blue-50 text-blue-700 border-blue-200';
} else {
    $status_classes = 'bg-amber-50 text-amber-700 border-amber-200'; // Untuk status draft/pending
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Iklan - <?= htmlspecialchars($detail_iklan['title']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-800 flex min-h-screen">

    <div class="w-64 bg-white border-r border-slate-100 p-6 h-screen sticky top-0 flex flex-col justify-between shrink-0">
        <div>
            <div class="flex items-center gap-2 mb-8">
                <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center text-white font-bold">S</div>
                <span class="font-bold text-xl text-slate-900">SIMI</span>
            </div>
            <div class="space-y-1">
                <a href="../dashboard/index.php" class="block px-4 py-2.5 text-slate-500 hover:bg-slate-50 rounded-xl text-sm font-medium">Dashboard</a>
                <a href="index.php" class="block px-4 py-2.5 bg-emerald-50 text-emerald-700 rounded-xl text-sm font-medium">Iklan</a>
                <a href="../clients/index.php" class="block px-4 py-2.5 text-slate-500 hover:bg-slate-50 rounded-xl text-sm font-medium">Klien</a>
                <a href="../payments/index.php" class="block px-4 py-2.5 text-slate-500 hover:bg-slate-50 rounded-xl text-sm font-medium">Payment</a>
            </div>
        </div>
        <a href="../auth/logout.php" class="block px-4 py-2.5 text-red-600 hover:bg-red-50 rounded-xl text-sm font-medium">Keluar</a>
    </div>

    <div class="flex-1 p-8 md:p-12">
        
        <div class="max-w-3xl bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mx-auto">
            
            <div class="p-6 md:p-8 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-1">Kampanye Iklan</span>
                    <h1 class="text-2xl font-bold text-slate-900"><?= htmlspecialchars($detail_iklan['title']); ?></h1>
                </div>
                <span class="px-3 py-1 text-xs font-semibold rounded-full border <?= $status_classes; ?>">
                    <?= ucfirst(htmlspecialchars($detail_iklan['status'])); ?>
                </span>
            </div>
            
            <div class="p-6 md:p-8 space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <div>
                        <span class="block text-xs font-semibold text-slate-400 uppercase mb-1">Harga Kontrak</span>
                        <span class="text-xl font-bold text-emerald-600">
                            Rp <?= number_format($detail_iklan['price'], 0, ',', '.'); ?>
                        </span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-slate-400 uppercase mb-1">ID Referensi</span>
                        <span class="text-sm font-mono text-slate-600 bg-white px-2 py-0.5 rounded border border-slate-200 inline-block">
                            #REF-<?= str_pad($detail_iklan['id'], 4, '0', STR_PAD_LEFT); ?>
                        </span>
                    </div>
                </div>

                <div class="space-y-2">
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Deskripsi Kampanye</span>
                    <div class="text-sm text-slate-600 leading-relaxed bg-white border border-slate-100 rounded-xl p-4 shadow-inner min-h-[100px]">
                        <?= nl2br(htmlspecialchars($detail_iklan['description'])); ?>
                    </div>
                </div>
                
            </div>

            <div class="px-6 md:p-8 py-4 bg-slate-50/50 border-t border-slate-100 flex justify-between items-center">
                <a href="index.php" class="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-600 hover:text-slate-800 font-medium rounded-xl text-sm transition shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Kembali
                </a>
            </div>

        </div>
    </div>

</body>
</html>