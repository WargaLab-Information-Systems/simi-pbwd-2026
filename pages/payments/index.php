<?php
include __DIR__ . "/../../helper/db_conn.php";
include __DIR__ . "/../../helper/data/payment.php";


$search = isset($_GET['search']) ? $_GET['search'] : '';
$result_all  = getAllPayments($conn, $search);
$total_rows  = mysqli_num_rows($result_all);
$stats       = getPaymentStats($conn);
$sysMessage  = getPaymentMessage();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 p-6">
    <div class="max-w-7xl mx-auto">
        
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Riwayat Pembayaran</h1>
                <p class="text-sm text-gray-500">Daftar log masuk transaksi klien.</p>
            </div>
            <a href="form.php" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">Tambah Transaksi</a>
        </div>

        <?php if ($sysMessage): ?>
            <div id="notification-box" class="mb-6 p-4 bg-<?= $sysMessage['type'] ?>-50 border border-<?= $sysMessage['type'] ?>-200 text-<?= $sysMessage['type'] ?>-800 rounded-lg text-sm font-medium transition-all duration-500">
            <?= $sysMessage['text'] ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                <span class="text-xs text-gray-400 font-bold uppercase">Total Lunas</span>
                <p class="text-xl font-bold text-gray-900 mt-1">Rp <?= number_format($stats['total_income'], 0, ',', '.'); ?></p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                <span class="text-xs text-gray-400 font-bold uppercase">Invoice Sukses</span>
                <p class="text-xl font-bold text-green-600 mt-1"><?= $stats['success_count']; ?> Transaksi</p>
            </div>
            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                <span class="text-xs text-gray-400 font-bold uppercase">Pending</span>
                <p class="text-xl font-bold text-amber-600 mt-1"><?= $stats['pending_count']; ?> Berkas</p>
            </div>
        </div>

        <div class="bg-white border rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 bg-gray-50 border-b">
                <form action="" method="GET" class="flex gap-2 max-w-sm">
                    <input type="text" name="search" placeholder="Cari data..." value="<?= htmlspecialchars($search) ?>" class="w-full px-3 py-1.5 border rounded-lg text-sm bg-white">
                    <button type="submit" class="bg-gray-200 px-3 rounded-lg text-sm font-medium">Cari</button>
                </form>
            </div>
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-500 uppercase text-xs border-b">
                    <tr>
                        <th class="p-4">No</th>
                        <th class="p-4">ID Transaksi</th>
                        <th class="p-4">Klien & Iklan</th>
                        <th class="p-4 text-right">Nominal</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4">Keterangan</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php if ($total_rows > 0): $i = 1; while($row = mysqli_fetch_assoc($result_all)): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="p-4"><?= $i++ ?></td>
                            <td class="p-4 font-mono text-gray-900">#SIMI-<?= str_pad($row['id'], 3, '0', STR_PAD_LEFT); ?></td>
                            <td class="p-4">
                                <div class="font-semibold"><?= htmlspecialchars($row['client_name']) ?></div>
                                <div class="text-xs text-gray-400"><?= htmlspecialchars($row['ad_title']) ?></div>
                            </td>
                            <td class="p-4 text-right font-bold text-gray-900">Rp <?= number_format($row['amount'], 0, ',', '.') ?>
                            </td>
                            <td class="p-4 text-center">
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold <?= $row['payment_status'] == 'lunas' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' ?>">
                                    <?= $row['payment_status'] == 'lunas' ? 'Lunas' : 'Belum Lunas' ?>
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="text-xs text-gray-600"><?= htmlspecialchars($row['notes']) ?></div>
                            </td>
                            <td class="p-4 text-center space-x-2">
                                <a href="form.php?edit=<?= $row['id'] ?>" class="text-blue-600 hover:underline">Kelola</a>
                                <a href="/simi-pbwd-2026/logic/payment_process.php?delete=<?= $row['id']; ?>" onclick="return confirm('Beneran nihh mau di hapuss?')" class="text-red-600 hover:underline">Hapus</a>                            
                            </td>
                        </tr>
                    <?php endwhile; else: ?>
                        <tr><td colspan="6" class="p-8 text-center text-gray-400">Data tidak ditemukan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
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