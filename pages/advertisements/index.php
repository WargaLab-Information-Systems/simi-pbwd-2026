<?php
session_start();
// user dah login ?
if (!isset($_SESSION['user_id'])) {
    // paksa ke login klo belum
    header("Location: ../auth/login.php");
    exit; 
}

if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }
require_once '../../helper/db_conn.php';

$query = "SELECT a.*, c.name AS client_name 
        FROM advertisements a 
        LEFT JOIN clients c ON a.client_id = c.id 
        ORDER BY a.id DESC";
$result = mysqli_query($conn, $query);
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
                <!-- ini buat payment -->
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

        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-xs font-semibold text-slate-500 uppercase track-wider">
                            <th class="px-6 py-4">Klien</th>
                            <th class="px-6 py-4">Judul Iklan</th>
                            <th class="px-6 py-4">Masa Kontrak</th>
                            <th class="px-6 py-4">Nilai Kontrak</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-slate-900">
                                        <?php echo htmlspecialchars($row['client_name'] ?? 'Tidak Diketahui'); ?>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600">
                                        <?php echo htmlspecialchars($row['title']); ?>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 text-xs">
                                        <?php echo date('d M Y', strtotime($row['start_date'])); ?> - 
                                        <?php echo date('d M Y', strtotime($row['end_date'])); ?>
                                    </td>
                                    <td class="px-6 py-4 text-slate-700 font-medium">
                                        Rp <?php echo number_format($row['price'], 0, ',', '.'); ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?php 
                                        if ($row['status'] === 'aktif') {
                                            echo '<span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-semibold">Aktif</span>';
                                        } elseif ($row['status'] === 'belum_tayang') {
                                            echo '<span class="px-2.5 py-1 bg-amber-50 text-amber-700 rounded-full text-xs font-semibold">Belum Tayang</span>';
                                        } else {
                                            echo '<span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-semibold">Selesai</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-3">
                                        <a href="form.php?id=<?php echo $row['id']; ?>" class="text-emerald-600 hover:text-emerald-900 font-medium">Edit</a>
                                        <a href="../../logic/advertisement_process.php?action=delete&id=<?php echo $row['id']; ?>" onclick="return confirm('Yakin ingin menghapus data iklan ini?')" class="text-red-600 hover:text-red-900 font-medium">Hapus</a>
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
</body>
</html>