<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
require_once __DIR__ . '/../../helper/db_conn.php';
require_once __DIR__ . '/../../helper/data/client.php';

$clients_list = getAllClients($conn);

// Pesan notifikasi
$message = '';
$messageType = '';
if (isset($_GET['msg'])) {
    $id = $_GET['id'] ?? '';
    switch ($_GET['msg']) {
        case 'insert_success':
            $message = "Klien baru berhasil didaftarkan!";
            $messageType = "green";
            break;
        case 'update_success':
            $message = "Data klien ID #$id berhasil diperbarui!";
            $messageType = "blue";
            break;
        case 'delete_success':
            $message = "Data klien ID #$id berhasil dihapus dari sistem.";
            $messageType = "yellow";
            break;
        case 'delete_blocked':
            $jumlah = intval($_GET['jumlah'] ?? 0);
            $message = "Klien tidak bisa dihapus karena masih memiliki jumlah data iklan yang terkait. Hapus iklan terkait terlebih dahulu.";
            $messageType = "red";
            break;
        case 'name_empty':
            $message = "Nama klien wajib diisi!";
            $messageType = "red";
            break;
        case 'error':
            $message = "Terjadi kesalahan: " . htmlspecialchars($_GET['detail'] ?? '');
            $messageType = "red";
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Klien</title>
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
                <a href="../advertisements/index.php" class="block px-4 py-2.5 text-slate-500 hover:bg-slate-50 rounded-xl text-sm font-medium">Iklan</a>
                <a href="index.php" class="block px-4 py-2.5 bg-emerald-50 text-emerald-700 rounded-xl text-sm font-medium">Klien</a>
                <a href="../payments/index.php" class="block px-4 py-2.5 text-slate-500 hover:bg-slate-50 rounded-xl text-sm font-medium">Payment</a>
            </div>
        </div>
        <a href="../auth/logout.php" class="block px-4 py-2.5 text-red-600 hover:bg-red-50 rounded-xl text-sm font-medium">Keluar</a>
    </div>
    <div class="flex-1 p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Daftar Klien</h1>
            <a href="form.php" class="px-4 py-2 bg-emerald-600 text-white font-semibold rounded-xl text-sm shadow-sm">Tambah Klien</a>
        </div>

        <?php if ($message): ?>
            <div id="notification-box" class="mb-6 p-4 bg-<?= $messageType ?>-50 border border-<?= $messageType ?>-200 text-<?= $messageType ?>-800 rounded-lg font-medium">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase">Nama Perusahaan</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase">No. Telepon</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase">Alamat Kantor</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (empty($clients_list)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-slate-400">Belum ada data klien.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clients_list as $client): ?>
                        <tr>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900"><?php echo htmlspecialchars($client['name']); ?></td>
                            <td class="px-6 py-4 text-sm text-slate-600"><?php echo htmlspecialchars($client['phone']); ?></td>
                            <td class="px-6 py-4 text-sm text-slate-500"><?php echo htmlspecialchars($client['address']); ?></td>

                            <td class="px-6 py-4 text-center text-sm space-x-3">
                                <a href="form.php?id=<?php echo $client['id']; ?>" class="text-emerald-600 font-semibold">Edit</a>
                                <?php if ($client['jumlah_iklan'] > 0): ?>
                                    <span class="text-slate-300 font-semibold cursor-not-allowed" title="Klien masih memiliki <?= $client['jumlah_iklan'] ?> iklan terkait, tidak bisa dihapus">Hapus</span>
                                <?php else: ?>
                                    <a href="../../logic/client_process.php?delete=<?php echo $client['id']; ?>" onclick="return confirm('Yakin ingin menghapus klien ini? Klien tidak memiliki iklan terkait.')" class="text-red-500 font-semibold">Hapus</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
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
            alertBox.style.transition = 'opacity 0.5s';
            setTimeout(() => alertBox.remove(), 500);
        }, 3000);
    }
    </script>
</body>
</html>
