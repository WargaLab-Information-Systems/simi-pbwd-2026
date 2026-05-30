<?php
// Menggunakan require_once untuk keamanan komponen inti backend
require_once __DIR__ . "/../../helper/db_conn.php";
require_once __DIR__ . "/../../helper/data/payment.php";

// Penamaan variabel menggunakan lowercase_underscore
$search      = isset($_GET['search']) ? $_GET['search'] : '';
$result_all  = getAllPayments($conn, $search); // Fungsi camelCase
$total_rows  = mysqli_num_rows($result_all);
$stats       = getPaymentStats($conn);
$sys_message = getPaymentMessage();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="text-gray-800 text-sm">

    <div class="flex min-h-screen bg-gray-50">
        
        <div class="w-64 bg-white border-r border-slate-200 p-6 flex flex-col justify-between h-screen sticky top-0 shrink-0">
            <div>
                <div class="flex items-center gap-2 mb-8">
                    <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center text-white font-bold text-base">S</div>
                    <span class="font-bold text-xl text-slate-900 tracking-wide">SIMI</span>
                </div>
                <div class="space-y-1">
                    <a href="../dashboard/index.php" class="block px-4 py-2.5 text-slate-500 hover:bg-slate-50 rounded-xl font-medium transition-colors">Dashboard</a>
                    <a href="../advertisements/index.php" class="block px-4 py-2.5 text-slate-500 hover:bg-slate-50 rounded-xl font-medium transition-colors">Iklan</a>
                    <a href="../clients/index.php" class="block px-4 py-2.5 text-slate-500 hover:bg-slate-50 rounded-xl font-medium transition-colors">Klien</a>
                    <a href="index.php" class="block px-4 py-2.5 bg-emerald-50 text-emerald-700 rounded-xl font-semibold">Pembayaran</a>
                </div>
            </div>
            <a href="../auth/logout.php" class="block px-4 py-2.5 text-red-600 hover:bg-red-50 rounded-xl font-medium transition-colors">Keluar</a>
        </div>

        <div class="flex-1 p-6 sm:p-10 overflow-x-hidden">
            
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Riwayat Pembayaran</h1>
                </div>
                <a href="form.php" class="bg-blue-600 text-white px-4 py-2.5 rounded-lg font-medium hover:bg-blue-700 shadow-sm transition-colors text-center">
                    Pembayaran
                </a>
            </div>

            <?php if ($sys_message): ?>
                <div id="notification-box" class="mb-6 p-4 bg-<?= $sys_message['type'] ?>-50 border border-<?= $sys_message['type'] ?>-200 text-<?= $sys_message['type'] ?>-800 rounded-lg font-medium transition-all duration-500">
                    <?= $sys_message['text'] ?>
                </div>
            <?php endif; ?>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="p-4 bg-gray-50 border-b border-gray-200">
                    <form action="" method="GET" class="flex gap-2 max-w-sm">
                        <input type="text" name="search" placeholder="Cari klien atau iklan..." value="<?= htmlspecialchars($search) ?>" class="w-full px-3 py-1.5 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <button type="submit" class="bg-gray-200 hover:bg-gray-300 px-4 rounded-lg font-medium transition-colors">Cari</button>
                    </form>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead class="bg-gray-50 text-gray-500 uppercase text-xs font-bold border-b border-gray-200 tracking-wider">
                            <tr>
                                <th class="p-4 w-12">No</th>
                                <th class="p-4">ID Transaksi</th>
                                <th class="p-4">Klien & Iklan</th>
                                <th class="p-4 text-right">Nominal & Kurang Bayar</th>
                                <th class="p-4 text-center">Status</th>
                                <th class="p-4">Keterangan</th>
                                <th class="p-4 text-center w-24">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if ($total_rows > 0): $i = 1; while($row = mysqli_fetch_assoc($result_all)): ?>
                                <?php 
                                    $total_harga = floatval($row['total_price']);
                                    // Mengubah ke 'total_paid' agar membaca hasil akumulasi seluruh cicilan
                                    $yang_dibayar = floatval($row['total_paid']); 
                                    $kurang_bayar = $total_harga - $yang_dibayar;
                                ?>
                                <tr class="hover:bg-gray-50/70 transition-colors">
                                    <td class="p-4 text-gray-500"><?= $i++ ?></td>
                                    <td class="p-4 font-mono font-medium text-gray-900">#SIMI-<?= str_pad($row['id'], 3, '0', STR_PAD_LEFT); ?></td>
                                    <td class="p-4">
                                        <div class="font-semibold text-gray-900"><?= htmlspecialchars($row['client_name']) ?></div>
                                        <div class="text-xs text-gray-400 mt-0.5"><?= htmlspecialchars($row['ad_title']) ?></div>
                                    </td>
                                    
                                    <td class="p-4 text-right">
                                        <div class="font-bold text-gray-900">Rp <?= number_format($yang_dibayar, 0, ',', '.') ?></div>
                                        <?php if ($kurang_bayar > 0): ?>
                                            <div class="text-xs text-rose-600 font-semibold mt-0.5">Kurang: Rp <?= number_format($kurang_bayar, 0, ',', '.') ?></div>
                                        <?php else: ?>
                                            <div class="text-xs text-green-600 font-medium mt-0.5">Lunas (Pas)</div>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td class="p-4 text-center">
                                        <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold <?= $row['payment_status'] == 'lunas' ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-amber-50 text-amber-700 border border-amber-200' ?>">
                                            <?= $row['payment_status'] == 'lunas' ? 'Lunas' : 'Belum Lunas' ?>
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <div class="text-xs text-gray-600 max-w-xs truncate" title="<?= htmlspecialchars($row['notes']) ?>">
                                            <?= htmlspecialchars($row['notes']) ?: '-' ?>
                                        </div>
                                    </td>
                                    <td class="p-4 text-center space-x-2 font-medium">
                                        <?php if ($kurang_bayar > 0): ?>
                                            <a href="form.php?pelunasan=true&ad_id=<?= $row['advertisement_id'] ?>&sisa=<?= $kurang_bayar ?>" class="bg-emerald-600 text-white px-2 py-1 rounded text-xs hover:bg-emerald-700 shadow-sm transition-colors inline-block">
                                                Bayar Sisa
                                            </a>
                                        <?php endif; ?>

                                        <a href="form.php?edit=<?= $row['id'] ?>" class="text-blue-600 hover:text-blue-800 hover:underline text-xs inline-block">Kelola</a>
                                        <a href="/simi-pbwd-2026/logic/payment_process.php?delete=<?= $row['id']; ?>" onclick="return confirm('Beneran nihh mau dihapus? Data tidak bisa dikembalikan!')" class="text-red-600 hover:text-red-800 hover:underline text-xs inline-block">Hapus</a>                            
                                    </td>
                                </tr>
                            <?php endwhile; else: ?>
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-gray-400 font-medium">Data transaksi tidak ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
    const alertBox = document.getElementById('notification-box');
    if (alertBox) {
        setTimeout(() => {
            alertBox.style.opacity = '0';
            alertBox.style.transform = 'translateY(-10px)';

            setTimeout(() => {
                alertBox.remove();
            }, 500);
        }, 3000);
    }
    </script>
</body>
</html>