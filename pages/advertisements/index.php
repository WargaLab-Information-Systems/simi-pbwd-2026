<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . '/../../helper/db_conn.php';

$query = "SELECT a.*, c.name AS client_name,
          COUNT(p.id) as jumlah_bayar
          FROM advertisements a 
          LEFT JOIN clients c ON a.client_id = c.id
          LEFT JOIN payments p ON a.id = p.advertisement_id
          GROUP BY a.id
          ORDER BY a.id DESC";
$result = mysqli_query($conn, $query);

$message = '';
$messageType = '';
if (isset($_GET['msg']) && $_GET['msg'] === 'delete_blocked') {
    $message = "Iklan tidak bisa dihapus karena masih memiliki data pembayaran terkait. Hapus pembayaran terlebih dahulu.";
    $messageType = "red";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Iklan - SIMI</title>
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
                <a href="../dashboard/index.php" class="block px-4 py-2.5 text-slate-500 hover:bg-slate-50 rounded-xl text-sm font-medium">Dashboard</a>
                <a href="index.php" class="block px-4 py-2.5 bg-emerald-50 text-emerald-700 rounded-xl text-sm font-medium">Iklan</a>
                <a href="../clients/index.php" class="block px-4 py-2.5 text-slate-500 hover:bg-slate-50 rounded-xl text-sm font-medium">Klien</a>
                <a href="../payments/index.php" class="block px-4 py-2.5 text-slate-500 hover:bg-slate-50 rounded-xl text-sm font-medium">Payment</a>
            </div>
        </div>
        <a href="../auth/logout.php" class="block px-4 py-2.5 text-red-600 hover:bg-red-50 rounded-xl text-sm font-medium">Keluar</a>
    </div>

    <div class="flex-1 p-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Daftar Kontrak Iklan</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola seluruh data kontrak iklan dan status penayangan.</p>
            </div>
            <a href="form.php" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl text-sm transition-colors shadow-sm">
                + Tambah Iklan
            </a>
        </div>

        <?php if ($message): ?>
            <div id="notification-box" class="mb-6 p-4 bg-<?= $messageType ?>-50 border border-<?= $messageType ?>-200 text-<?= $messageType ?>-800 rounded-xl font-medium">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4 whitespace-nowrap">Klien</th>
                            <th class="px-6 py-4 min-w-[200px]">Judul Iklan</th>
                            <th class="px-6 py-4 whitespace-nowrap">Masa Kontrak</th>
                            <th class="px-6 py-4 whitespace-nowrap">Nilai Kontrak</th>
                            <th class="px-6 py-4 whitespace-nowrap text-center">Status</th>
                            <th class="px-6 py-4 whitespace-nowrap text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-slate-900 align-middle">
                                        <?php echo htmlspecialchars($row['client_name'] ?? 'Tidak Diketahui'); ?>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 align-middle">
                                        <?php echo htmlspecialchars($row['title']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 text-xs whitespace-nowrap align-middle">
                                        <?php echo date('d M Y', strtotime($row['start_date'])); ?> - 
                                        <?php echo date('d M Y', strtotime($row['end_date'])); ?>
                                    </td>
                                    <td class="px-6 py-4 text-slate-700 font-medium whitespace-nowrap align-middle">
                                        Rp <?php echo number_format($row['price'], 0, ',', '.'); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center align-middle">
                                        <?php
                                        // Perbaikan di bagian class span ini
                                        if ($row['status'] === 'aktif') {
                                            echo '<span class="inline-block px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-semibold whitespace-nowrap">Aktif</span>';
                                        } elseif ($row['status'] === 'belum_tayang') {
                                            echo '<span class="inline-block px-3 py-1 bg-amber-50 text-amber-700 rounded-full text-xs font-semibold whitespace-nowrap">Belum Tayang</span>';
                                        } else {
                                            echo '<span class="inline-block px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-semibold whitespace-nowrap">Selesai</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="flex flex-col px-6 py-4 text-center whitespace-nowrap align-middle space-x-3">
                                        <a href="detail.php?id=<?php echo $row['id']; ?>" class="inline-block text-yellow-600 hover:text-yellow-900 font-medium">Detail</a>
                                        <a href="form.php?id=<?php echo $row['id']; ?>" class="inline-block text-emerald-600 hover:text-emerald-900 font-medium">Edit</a>
                                        <?php if ($row['jumlah_bayar'] > 0): ?>
                                            <span class="inline-block text-slate-300 font-medium cursor-not-allowed" title="Iklan masih memiliki <?= $row['jumlah_bayar'] ?> transaksi pembayaran">Hapus</span>
                                        <?php else: ?>
                                            <a href="../../logic/advertisement_process.php?action=delete&id=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus data iklan ini?')" class="inline-block text-red-600 hover:text-red-900 font-medium">Hapus</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-slate-400 bg-slate-50/20">
                                    Belum ada data kontrak iklan yang tersimpan.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
    const alertBox = document.getElementById('notification-box');
    if (alertBox) {
        setTimeout(() => {
            alertBox.style.opacity = '0';
            alertBox.style.transition = 'opacity 0.5s';
            setTimeout(() => alertBox.remove(), 500);
        }, 4000);
    }
    </script>
</body>
</html>