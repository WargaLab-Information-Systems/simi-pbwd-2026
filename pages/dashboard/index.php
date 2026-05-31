<?php
session_start();
// user dah login ?
if (!isset($_SESSION['user_id'])) {
    // paksa ke login klo belum
    header("Location: ../auth/login.php");
    exit; 
}

if (!isset($_SESSION['user_id'])) { 
    header("Location: ../auth/login.php"); 
    exit; 
}
require_once '../../helper/db_conn.php';
require_once '../../helper/data/advertisement.php';
require_once '../../helper/data/payment.php';

$total_revenue = getTotalRevenue($conn);
$count_aktif = getAdvertisementCountByStatus($conn, 'aktif');
$count_belum_tayang = getAdvertisementCountByStatus($conn, 'belum_tayang');
$count_selesai = getAdvertisementCountByStatus($conn, 'selesai');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-slate-50 text-slate-800 flex">
    <div class="w-64 bg-white border-r border-slate-100 p-6 h-screen sticky top-0 flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-2 mb-8">
                <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center text-white font-bold">S</div>
                <span class="font-bold text-xl text-slate-900">SIMI</span>
            </div>
            <div class="space-y-1">
                <a href="index.php" class="block px-4 py-2.5 bg-emerald-50 text-emerald-700 rounded-xl text-sm font-medium">Dashboard</a>
                <a href="../advertisements/index.php" class="block px-4 py-2.5 text-slate-500 hover:bg-slate-50 rounded-xl text-sm font-medium">Iklan</a>
                <a href="../clients/index.php" class="block px-4 py-2.5 text-slate-500 hover:bg-slate-50 rounded-xl text-sm font-medium">Klien</a>
                <!-- ini buat payment -->
                <a href="../payments/index.php" class="block px-4 py-2.5 text-slate-500 hover:bg-slate-50 rounded-xl text-sm font-medium">Payment</a>
            </div>
        </div>
        <a href="../auth/logout.php" class="block px-4 py-2.5 text-red-600 hover:bg-red-50 rounded-xl text-sm font-medium">Keluar</a>
    </div>
    <div class="flex-1 p-8">
        <h1 class="text-2xl font-bold text-slate-900 mb-6">Dashboard Overview</h1>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Total Pendapatan</p>
                <p class="text-xl font-bold text-slate-900">Rp <?php echo number_format($total_revenue, 0, ',', '.'); ?></p>
            </div>
            <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Iklan Aktif</p>
                <p class="text-2xl font-bold text-emerald-600"><?php echo $count_aktif; ?></p>
            </div>
            <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Belum Tayang</p>
                <p class="text-2xl font-bold text-blue-600"><?php echo $count_belum_tayang; ?></p>
            </div>
            <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Iklan Selesai</p>
                <p class="text-2xl font-bold text-amber-600"><?php echo $count_selesai; ?></p>
            </div>
        </div>
    </div>
</body>
</html>