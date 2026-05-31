<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Klien</title>
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
            </div>
        </div>
        <a href="../auth/logout.php" class="block px-4 py-2.5 text-red-600 hover:bg-red-50 rounded-xl text-sm font-medium">Keluar</a>
    </div>
    <div class="flex-1 p-8 max-w-2xl">
        <h1 class="text-2xl font-bold text-slate-900 mb-6">Tambah Klien Baru</h1>
        <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm">
            <form id="clientForm" method="POST" action="../../logic/client_process.php">
                <input type="hidden" name="action" value="insert">
                <div class="mb-5">
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Nama Klien</label>
                    <input type="text" name="name" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500" required>
                </div>
                <div class="mb-5">
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">No. Telepon</label>
                    <input type="text" name="phone" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500">
                </div>
                <div class="mb-6">
                    <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Alamat Kantor</label>
                    <textarea name="address" rows="4" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500"></textarea>
                </div>
                <div class="flex gap-3 justify-end">
                    <a href="index.php" class="px-4 py-2.5 bg-slate-100 text-slate-600 font-medium rounded-xl text-sm">Batal</a>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 text-white font-semibold rounded-xl text-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>