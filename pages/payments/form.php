<?php
include __DIR__ . "/../../helper/db_conn.php";

// 2. Baru panggil helper data payment
include __DIR__ . "/../../helper/data/payment.php";

$editData = (isset($_GET['edit'])) ? getPaymentById($conn, $_GET['edit']) : null;
$invoice_id = generateInvoiceId($conn, $editData);

// Mengambil list untuk isi dropdown pilihan kampanye iklan
$ads_query = mysqli_query($conn, "SELECT advertisements.id, advertisements.title, clients.name AS client_name FROM advertisements JOIN clients ON advertisements.client_id = clients.id ORDER BY advertisements.id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8 text-sm">
    <div class="max-w-xl mx-auto bg-white p-6 rounded-xl border shadow-sm">
        <h2 class="text-xl font-bold mb-1"><?= $editData ? 'Ubah Data Pembayaran' : 'Catat Transaksi Baru' ?></h2>
        <p class="text-gray-400 mb-6 text-xs">Pastikan informasi invoice dan nominal diisi dengan valid.</p>

        <form id="paymentForm" action="../../logic/payment_process.php" method="POST" class="space-y-4">
            <input type="hidden" name="action" value="<?= $editData ? 'update' : 'insert' ?>">
            <?php if ($editData): ?>
                <input type="hidden" name="id" value="<?= $editData['id'] ?>">
            <?php endif; ?>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">ID Invoice</label>
                <input type="text" readonly value="<?= $invoice_id ?>" class="w-full px-3 py-2 border rounded-lg bg-gray-100 font-mono text-gray-500 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Kampanye Iklan *</label>
                <select name="advertisement_id" id="advertisement_id" class="w-full px-3 py-2 border rounded-lg bg-white">
                    <option value="">-- Pilih Iklan Klien --</option>
                    <?php while($ad = mysqli_fetch_assoc($ads_query)): ?>
                        <option value="<?= $ad['id'] ?>" <?= (isset($editData['advertisement_id']) && $editData['advertisement_id'] == $ad['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($ad['client_name']) . " - " . htmlspecialchars($ad['title']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <span id="error_ad" class="text-red-500 text-xs hidden">Wajib memilih salah satu kampanye iklan!</span>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jumlah Pembayaran (Rp) *</label>
                <input type="number" name="amount" id="amount" placeholder="Masukkan angka tanpa titik/koma" value="<?= $editData['amount'] ?? '' ?>" class="w-full px-3 py-2 border rounded-lg bg-white">
                <span id="error_amount" class="text-red-500 text-xs hidden">Nominal pembayaran harus diisi dan harus lebih besar dari 0!</span>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal Bayar *</label>
                    <input type="date" name="payment_date" id="payment_date" value="<?= $editData['payment_date'] ?? date('Y-m-d') ?>" class="w-full px-3 py-2 border rounded-lg bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status Kelayakan *</label>
                    <select name="payment_status" class="w-full px-3 py-2 border rounded-lg bg-white">
                        <option value="belum_lunas" <?= (isset($editData['payment_status']) && $editData['payment_status'] == 'belum_lunas') ? 'selected' : '' ?>>Belum Lunas</option>
                        <option value="lunas" <?= (isset($editData['payment_status']) && $editData['payment_status'] == 'lunas') ? 'selected' : '' ?>>Lunas</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Catatan</label>
                <textarea name="notes" rows="3" class="w-full px-3 py-2 border rounded-lg bg-white"><?= $editData['notes'] ?? '' ?></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <a href="index.php" class="px-4 py-2 bg-gray-100 rounded-lg font-medium hover:bg-gray-200">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            let isValid = true;
            
            const ad = document.getElementById('advertisement_id');
            const amount = document.getElementById('amount');
            
            // Validasi Dropdown Iklan
            if(ad.value === "") {
                document.getElementById('error_ad').classList.remove('hidden');
                ad.classList.add('border-red-500');
                isValid = false;
            } else {
                document.getElementById('error_ad').classList.add('hidden');
                ad.classList.remove('border-red-500');
            }

            // Validasi Nominal Angka
            if(amount.value.trim() === "" || parseInt(amount.value) <= 0) {
                document.getElementById('error_amount').classList.remove('hidden');
                amount.classList.add('border-red-500');
                isValid = false;
            } else {
                document.getElementById('error_amount').classList.add('hidden');
                amount.classList.remove('border-red-500');
            }

            // Jika ada yang tidak valid, batalkan pengiriman form ke PHP
            if(!isValid) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>