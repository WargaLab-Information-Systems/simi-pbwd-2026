<?php
session_start();
// user dah login ?
if (!isset($_SESSION['user_id'])) {
    // paksa ke login klo belum
    header("Location: ../auth/login.php");
    exit; 
}

require_once '../../helper/db_conn.php';
require_once '../../helper/data/client.php';
$clients_list = getAllClients($conn);
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
                <!-- ini buat payment -->
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
        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase">Nama Perusahaan</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase">No. Telepon</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-400 uppercase">Alamat Kantor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($clients_list as $client): ?>
                    <tr>
                        <td class="px-6 py-4 text-sm font-semibold text-slate-900"><?php echo htmlspecialchars($client['name']); ?></td>
                        <td class="px-6 py-4 text-sm text-slate-600"><?php echo htmlspecialchars($client['phone']); ?></td>
                        <td class="px-6 py-4 text-sm text-slate-500"><?php echo htmlspecialchars($client['address']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>