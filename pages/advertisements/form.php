<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit; }
require_once '../../helper/db_conn.php';
require_once '../../helper/data/advertisement.php';
require_once '../../helper/data/client.php';

$id = $_GET['id'] ?? '';
$ad = !empty($id) ? getAdvertisementById($conn, $id) : null;
$clients = getAllClients($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Iklan</title>
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
            </div>
        </div>
        <a href="../auth/logout.php" class="block px-4 py-2.5 text-red-600 hover:bg-red-50 rounded-xl text-sm font-medium">Keluar</a>
    </div>
    <div class="flex-1 p-8 max-w-3xl">
        <h1 class="text-2xl font-bold text-slate-900 mb-6">Form Kontrak Iklan</h1>
        <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm">
            <form id="adForm" method="POST" action="../../logic/advertisement_process.php" onsubmit="return validateAd(event)">
                <input type="hidden" name="action" value="<?php echo $ad ? 'update' : 'insert'; ?>">
                <?php if ($ad): ?><input type="hidden" name="id" value="<?php echo $ad['id']; ?>"><?php endif; ?>
                
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Pilih Klien</label>
                    <select name="client_id" id="clientId" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500">
                        <option value="">-- Silakan Pilih Klien --</option>
                        <?php foreach ($clients as $client): ?>
                            <option value="<?php echo $client['id']; ?>" <?php echo ($ad && $ad['client_id'] == $client['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($client['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p id="client_error" class="text-xs text-red-500 mt-1 hidden"></p>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Judul Iklan</label>
                    <input type="text" id="adTitle" name="title" value="<?php echo $ad ? htmlspecialchars($ad['title']) : ''; ?>" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500">
                    <p id="title_error" class="text-xs text-red-500 mt-1 hidden"></p>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Deskripsi</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500"><?php echo $ad ? htmlspecialchars($ad['description']) : ''; ?></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Tanggal Mulai</label>
                        <input type="date" id="startDate" name="start_date" value="<?php echo $ad ? $ad['start_date'] : ''; ?>" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500">
                        <p id="start_error" class="text-xs text-red-500 mt-1 hidden"></p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Tanggal Selesai</label>
                        <input type="date" id="endDate" name="end_date" value="<?php echo $ad ? $ad['end_date'] : ''; ?>" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500">
                        <p id="end_error" class="text-xs text-red-500 mt-1 hidden"></p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500">
                            <option value="belum_tayang" <?php echo ($ad && $ad['status'] === 'belum_tayang') ? 'selected' : ''; ?>>Belum Tayang</option>
                            <option value="aktif" <?php echo ($ad && $ad['status'] === 'aktif') ? 'selected' : ''; ?>>Aktif</option>
                            <option value="selesai" <?php echo ($ad && $ad['status'] === 'selesai') ? 'selected' : ''; ?>>Selesai</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Nilai Kontrak (Rp)</label>
                        <input type="number" id="adPrice" name="price" value="<?php echo $ad ? $ad['price'] : ''; ?>" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500">
                        <p id="price_error" class="text-xs text-red-500 mt-1 hidden"></p>
                    </div>
                </div>

                <div class="flex gap-3 justify-end">
                    <a href="index.php" class="px-4 py-2.5 bg-slate-100 text-slate-600 font-medium rounded-xl text-sm">Batal</a>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 text-white font-semibold rounded-xl text-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <script>
    function validateAd(event) {
        let isValid = true;
        const client = document.getElementById('clientId');
        const title = document.getElementById('adTitle');
        const startDate = document.getElementById('startDate');
        const endDate = document.getElementById('endDate');
        const price = document.getElementById('adPrice');
        
        ['client_error', 'title_error', 'start_error', 'end_error', 'price_error'].forEach(id => {
            document.getElementById(id).classList.add('hidden');
        });
        
        if (client.value === '') {
            document.getElementById('client_error').textContent = 'Klien wajib dipilih.';
            document.getElementById('client_error').classList.remove('hidden');
            isValid = false;
        }
        if (title.value.trim() === '') {
            document.getElementById('title_error').textContent = 'Judul iklan wajib diisi.';
            document.getElementById('title_error').classList.remove('hidden');
            isValid = false;
        }
        if (startDate.value === '') {
            document.getElementById('start_error').textContent = 'Tanggal mulai wajib diisi.';
            document.getElementById('start_error').classList.remove('hidden');
            isValid = false;
        }
        if (endDate.value === '') {
            document.getElementById('end_error').textContent = 'Tanggal selesai wajib diisi.';
            document.getElementById('end_error').classList.remove('hidden');
            isValid = false;
        }
        if (price.value.trim() === '' || parseFloat(price.value) <= 0) {
            document.getElementById('price_error').textContent = 'Harga harus lebih dari 0.';
            document.getElementById('price_error').classList.remove('hidden');
            isValid = false;
        }
        if (!isValid) event.preventDefault();
        return isValid;
    }
    </script>
</body>
</html>