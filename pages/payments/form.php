<?php
include __DIR__ . "/../../helper/db_conn.php";
include __DIR__ . "/../../helper/data/payment.php";

$editData = (isset($_GET['edit'])) ? getPaymentById($conn, $_GET['edit']) : null;
$invoice_id = generateInvoiceId($conn, $editData);

$is_pelunasan  = isset($_GET['pelunasan']) ? true : false;
$default_ad_id = isset($_GET['ad_id']) ? $_GET['ad_id'] : '';
$default_sisa  = isset($_GET['sisa']) ? $_GET['sisa'] : '';

$ads_query = mysqli_query($conn, "SELECT advertisements.id, advertisements.title, advertisements.price, clients.name AS client_name FROM advertisements JOIN clients ON advertisements.client_id = clients.id ORDER BY advertisements.id DESC");
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
        <h2 class="text-xl font-bold mb-1">
            <?php 
                if ($is_pelunasan) echo 'Form Pelunasan Sisa Pembayaran';
                elseif ($editData) echo 'Ubah Data Pembayaran';
                else echo 'Catat Transaksi Baru';
            ?>
        </h2>

        <form id="paymentForm" action="../../logic/payment_process.php" method="POST" class="space-y-4">
            <input type="hidden" name="action" value="<?= ($editData && !$is_pelunasan) ? 'update' : 'insert' ?>">
            <?php if ($editData && !$is_pelunasan): ?>
                <input type="hidden" name="id" value="<?= $editData['id'] ?>">
            <?php endif; ?>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">ID Transaksi</label>
                <input type="text" readonly value="<?= $invoice_id ?>" class="w-full px-3 py-2 border rounded-lg bg-gray-100 font-mono text-gray-500 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Kampanye Iklan *</label>
                
                <?php if ($is_pelunasan): ?>
                    <input type="hidden" name="advertisement_id" value="<?= $default_ad_id ?>">
                <?php endif; ?>

                <select name="advertisement_id" id="advertisement_id" class="w-full px-3 py-2 border rounded-lg bg-white <?= $is_pelunasan ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : '' ?>" <?= $is_pelunasan ? 'disabled' : '' ?>>
                    <option value="" data-price="0">-- Pilih Iklan Klien --</option>
                    <?php while($ad = mysqli_fetch_assoc($ads_query)): ?>
                        <?php 
                            $selected = '';
                            if ($is_pelunasan && $default_ad_id == $ad['id']) {
                                $selected = 'selected';
                            } elseif (isset($editData['advertisement_id']) && $editData['advertisement_id'] == $ad['id']) {
                                $selected = 'selected';
                            }
                        ?>
                        <option value="<?= $ad['id'] ?>" data-price="<?= $ad['price'] ?>" <?= $selected ?>>
                            <?= htmlspecialchars($ad['client_name']) . " - " . htmlspecialchars($ad['title']) ?> (Rp <?= number_format($ad['price'], 0, ',', '.') ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
                <span id="error_ad" class="text-red-500 text-xs hidden">Wajib memilih salah satu kampanye iklan!</span>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">
                    Jumlah Pembayaran (Rp) * <span id="max_info" class="text-blue-600 font-bold"></span>
                </label>
                <input type="number" 
                       name="amount" 
                       id="amount" 
                       placeholder="Masukkan angka tanpa titik/koma" 
                       value="<?= $is_pelunasan ? $default_sisa : ($editData['amount'] ?? '') ?>" 
                       max="<?= $is_pelunasan ? $default_sisa : '' ?>"
                       class="w-full px-3 py-2 border rounded-lg bg-white">
                
                <span id="error_amount" class="text-red-500 text-xs hidden">Nominal pembayaran harus diisi dan harus lebih besar dari 0!</span>
                <span id="error_max_amount" class="text-red-500 text-xs hidden">Nominal tidak boleh melebihi batas maksimum total harga/sisa iklan!</span>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal Bayar *</label>
                    <input type="date" name="payment_date" id="payment_date" value="<?= $editData['payment_date'] ?? date('Y-m-d') ?>" class="w-full px-3 py-2 border rounded-lg bg-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status Pembayaran *</label>
                    <select name="payment_status" id="payment_status" class="w-full px-3 py-2 border rounded-lg bg-white">
                        <option value="belum_lunas" <?= ((isset($editData['payment_status']) && $editData['payment_status'] == 'belum_lunas') && !$is_pelunasan) ? 'selected' : '' ?>>Belum Lunas</option>
                        <option value="lunas" <?= ((isset($editData['payment_status']) && $editData['payment_status'] == 'lunas') || $is_pelunasan) ? 'selected' : '' ?>>Lunas</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Catatan</label>
                <textarea name="notes" rows="3" class="w-full px-3 py-2 border rounded-lg bg-white"><?= $is_pelunasan ? 'Pelunasan sisa pembayaran iklan.' : ($editData['notes'] ?? '') ?></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <a href="index.php" class="px-4 py-2 bg-gray-100 rounded-lg font-medium hover:bg-gray-200">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>

    <script>
        const adSelect = document.getElementById('advertisement_id');
        const amountInput = document.getElementById('amount');
        const maxInfo = document.getElementById('max_info');
        const statusSelect = document.getElementById('payment_status');

        function updateMaxAmount() {
            if (<?= $is_pelunasan ? 'true' : 'false' ?>) {
                maxInfo.innerText = `(Maks Sisa Hutang: Rp ${parseInt(amountInput.getAttribute('max')).toLocaleString('id-ID')})`;
                return;
            }

            const selectedOption = adSelect.options[adSelect.selectedIndex];
            const price = parseInt(selectedOption.getAttribute('data-price')) || 0;

            if (price > 0) {
                amountInput.setAttribute('max', price);
                maxInfo.innerText = `(Maks Harga Iklan: Rp ${price.toLocaleString('id-ID')})`;
            } else {
                amountInput.removeAttribute('max');
                maxInfo.innerText = '';
            }
        }

        adSelect.addEventListener('change', updateMaxAmount);
        
        window.addEventListener('DOMContentLoaded', updateMaxAmount);

        amountInput.addEventListener('input', function() {
            const maxVal = parseInt(amountInput.getAttribute('max'));
            const currentVal = parseInt(amountInput.value);
            
            if (maxVal && currentVal >= maxVal) {
                statusSelect.value = 'lunas';
            } else {
                statusSelect.value = 'belum_lunas';
            }
        });

        document.getElementById('paymentForm').addEventListener('submit', function(e) {
            let isValid = true;
            
            if(adSelect.value === "" && !adSelect.disabled) {
                document.getElementById('error_ad').classList.remove('hidden');
                adSelect.classList.add('border-red-500');
                isValid = false;
            } else {
                document.getElementById('error_ad').classList.add('hidden');
                adSelect.classList.remove('border-red-500');
            }

            const maxAmount = amountInput.getAttribute('max') ? parseInt(amountInput.getAttribute('max')) : null;
            const inputAmount = parseInt(amountInput.value);

            if(amountInput.value.trim() === "" || inputAmount <= 0) {
                document.getElementById('error_amount').classList.remove('hidden');
                document.getElementById('error_max_amount').classList.add('hidden');
                amountInput.classList.add('border-red-500');
                isValid = false;
            } 
            else if(maxAmount !== null && inputAmount > maxAmount) {
                document.getElementById('error_max_amount').classList.remove('hidden');
                document.getElementById('error_amount').classList.add('hidden');
                amountInput.classList.add('border-red-500');
                isValid = false;
            } else {
                document.getElementById('error_amount').classList.add('hidden');
                document.getElementById('error_max_amount').classList.add('hidden');
                amountInput.classList.remove('border-red-500');
            }

            if(!isValid) {
                e.preventDefault(); 
            }
        });
    </script>
</body>
</html>