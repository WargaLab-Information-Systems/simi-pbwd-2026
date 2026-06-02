<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . '/../../helper/db_conn.php';
require_once __DIR__ . '/../../helper/data/payment.php';

$editData = (isset($_GET['edit'])) ? getPaymentById($conn, $_GET['edit']) : null;
$invoice_id = generateInvoiceId($conn, $editData);

$is_pelunasan  = isset($_GET['pelunasan']) ? true : false;
$default_ad_id = isset($_GET['ad_id']) ? $_GET['ad_id'] : '';
$default_sisa  = isset($_GET['sisa']) ? $_GET['sisa'] : '';

$ads_query = mysqli_query($conn, "SELECT advertisements.id, advertisements.title, advertisements.price, advertisements.end_date, clients.name AS client_name FROM advertisements JOIN clients ON advertisements.client_id = clients.id ORDER BY advertisements.id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Transaksi</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                <a href="../clients/index.php" class="block px-4 py-2.5 text-slate-500 hover:bg-slate-50 rounded-xl text-sm font-medium">Klien</a>
                <a href="index.php" class="block px-4 py-2.5 bg-emerald-50 text-emerald-700 rounded-xl text-sm font-medium">Payment</a>
            </div>
        </div>
        <a href="../auth/logout.php" class="block px-4 py-2.5 text-red-600 hover:bg-red-50 rounded-xl text-sm font-medium">Keluar</a>
    </div>

    <div class="flex-1 p-8">
        <div class="max-w-xl">
            <h2 class="text-2xl font-bold text-slate-900 mb-6">
                <?php
                    if ($is_pelunasan) echo 'Form Pelunasan Sisa Pembayaran';
                    elseif ($editData) echo 'Ubah Data Pembayaran';
                    else echo 'Catat Transaksi Baru';
                ?>
            </h2>

            <div class="bg-white border border-slate-100 p-6 rounded-2xl shadow-sm">
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

                        <select <?= $is_pelunasan ? 'disabled' : 'name="advertisement_id"' ?> id="advertisement_id" class="w-full px-3 py-2 border rounded-lg bg-white <?= $is_pelunasan ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : '' ?>">
                        <option value="" data-price="0" data-end="">-- Pilih Iklan Klien --</option>
    
                        <?php while($ad = mysqli_fetch_assoc($ads_query)): ?>
                        <?php
                        $selected = '';
                        if ($is_pelunasan && $default_ad_id == $ad['id']) {
                        $selected = 'selected';
                        } elseif (isset($editData['advertisement_id']) && $editData['advertisement_id'] == $ad['id']) {
                        $selected = 'selected';
                        }
                        ?>
                        <option value="<?= $ad['id'] ?>" data-price="<?= $ad['price'] ?>" data-end="<?= $ad     ['end_date'] ?>" <?= $selected ?>>
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
    
                        <input type="text"
                            name="amount"
                            id="amount"
                            placeholder="Masukkan nominal angka"
                            value="<?= $is_pelunasan ? number_format($default_sisa, 0, ',', '.') : (isset($editData['amount']) ? number_format($editData['amount'], 0, ',', '.') : '') ?>"
                            max="<?= $is_pelunasan ? $default_sisa : '' ?>"
                            class="w-full px-3 py-2 border rounded-lg bg-white">

                        <span id="error_amount" class="text-red-500 text-xs hidden">Nominal pembayaran harus diisi dan harus lebih besar dari 0!</span>
                        <span id="error_max_amount" class="text-red-500 text-xs hidden">Nominal tidak boleh melebihi batas maksimum total harga/sisa iklan!</span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">
                                Tanggal Bayar * <span id="date_max_info" class="text-blue-600 font-bold"></span>
                            </label>
                            <input type="date" 
                                name="payment_date" 
                                id="payment_date" 
                                value="<?= $editData['payment_date'] ?? date('Y-m-d') ?>" 
                                class="w-full px-3 py-2 border rounded-lg bg-white">
                            <span id="error_date_max" class="text-red-500 text-xs hidden">Tanggal bayar tidak boleh melebihi batas masa kontrak iklan!</span>
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
        </div>
    </div>

    <script>
        const adSelect = document.getElementById('advertisement_id');
        const amountInput = document.getElementById('amount');
        const maxInfo = document.getElementById('max_info');
        const statusSelect = document.getElementById('payment_status');
        
        const paymentDateInput = document.getElementById('payment_date');
        const dateMaxInfo = document.getElementById('date_max_info');

        function formatRupiah(angka) {
            let number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa  = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            return split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        }

        function updateMaxAmount() {
            if (<?= $is_pelunasan ? 'true' : 'false' ?>) {
                const maxVal = parseInt(amountInput.getAttribute('max')) || 0;
                maxInfo.innerText = `(Maks Sisa Hutang: Rp ${maxVal.toLocaleString('id-ID')})`;
                return;
            }
            
            const selectedOption = adSelect.options[adSelect.selectedIndex];
            const price = parseInt(selectedOption.getAttribute('data-price')) || 0;
            const endDate = selectedOption.getAttribute('data-end'); // Ambil tanggal kontrak dari opsi iklan

            if (price > 0) {
                amountInput.setAttribute('max', price);
                maxInfo.innerText = `(Maks Harga Iklan: Rp ${price.toLocaleString('id-ID')})`;
            } else {
                amountInput.removeAttribute('max');
                maxInfo.innerText = '';
            }

            if (endDate) {
                paymentDateInput.setAttribute('max', endDate);
                
                const formattedDate = endDate.split('-').reverse().join('-');
                dateMaxInfo.innerText = `(Maks Kontrak: ${formattedDate})`;
            } else {
                paymentDateInput.removeAttribute('max');
                dateMaxInfo.innerText = '';
            }
        }

        adSelect.addEventListener('change', updateMaxAmount);
        window.addEventListener('DOMContentLoaded', updateMaxAmount);

        amountInput.addEventListener('input', function() {
            const cleanValue = this.value.replace(/\./g, '');
            const currentVal = parseInt(cleanValue) || 0;
            const maxVal = parseInt(amountInput.getAttribute('max'));

            this.value = formatRupiah(this.value);

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

            const cleanAmount = amountInput.value.replace(/\./g, '');
            const inputAmount = parseInt(cleanAmount) || 0;
            const maxAmount = amountInput.getAttribute('max') ? parseInt(amountInput.getAttribute('max')) : null;

            if(cleanAmount.trim() === "" || inputAmount <= 0) {
                document.getElementById('error_amount').classList.remove('hidden');
                document.getElementById('error_max_amount').classList.add('hidden');
                amountInput.classList.add('border-red-500');
                isValid = false;
            } else if(maxAmount !== null && inputAmount > maxAmount) {
                document.getElementById('error_max_amount').classList.remove('hidden');
                document.getElementById('error_amount').classList.add('hidden');
                amountInput.classList.add('border-red-500');
                isValid = false;
            } else {
                document.getElementById('error_amount').classList.add('hidden');
                document.getElementById('error_max_amount').classList.add('hidden');
                amountInput.classList.remove('border-red-500');
            }

            const maxDateString = paymentDateInput.getAttribute('max');
            if (maxDateString && paymentDateInput.value) {
                const inputDate = new Date(paymentDateInput.value);
                const maxContractDate = new Date(maxDateString);

                if (inputDate > maxContractDate) {
                    document.getElementById('error_date_max').classList.remove('hidden');
                    paymentDateInput.classList.add('border-red-500');
                    isValid = false;
                } else {
                    document.getElementById('error_date_max').classList.add('hidden');
                    paymentDateInput.classList.remove('border-red-500');
                }
            }

            if(!isValid) {
                e.preventDefault();
            } else {
                amountInput.value = cleanAmount; 
            }
        });
    </script>
</body>
</html>
