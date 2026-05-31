<?php
session_start();
require_once '../../helper/db_conn.php';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $plain_password = trim($_POST['password']);
    
    if (strlen($plain_password) <= 6) {
        $error_message = "Password harus lebih dari 6 karakter.";
    } else {
        $password = md5($plain_password);
        $query = "SELECT id FROM users WHERE email = '$email' AND password = '$password' LIMIT 1";
        $result = mysqli_query($conn, $query);
        
        if ($result && mysqli_num_rows($result) === 1) {
            $user_data = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $user_data['id'];
            header("Location: ../dashboard/index.php");
            exit;
        } else {
            $error_message = "Email atau Password salah!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login SIMI</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white border border-slate-100 p-8 rounded-2xl shadow-sm">
        <div class="flex items-center gap-2 justify-center mb-6">
            <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center text-white font-bold">S</div>
            <span class="font-bold text-xl text-slate-900">SIMI</span>
        </div>
        
        <?php if ($error_message !== ''): ?>
            <div class="mb-4 p-3 bg-red-50 text-red-600 text-sm rounded-xl text-center font-medium">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form id="loginForm" method="POST" action="" onsubmit="return validateLogin(event)">
            <div class="mb-4">
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Email</label>
                <input type="email" id="email" name="email" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500" autocomplete="off">
                <p id="email_error" class="text-xs text-red-500 mt-1 hidden"></p>
            </div>
            <div class="mb-6">
                <label class="block text-xs font-semibold text-slate-500 uppercase mb-2">Password</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-emerald-500">
                <p id="password_error" class="text-xs text-red-500 mt-1 hidden"></p>
            </div>
            <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl text-sm transition-colors">Masuk</button>
        </form>
    </div>
    <script>
    function validateLogin(event) {
        let isValid = true;
        const email = document.getElementById('email');
        const password = document.getElementById('password');
        
        document.getElementById('email_error').classList.add('hidden');
        document.getElementById('password_error').classList.add('hidden');
        
        if (email.value.trim() === '') {
            document.getElementById('email_error').textContent = 'Email wajib diisi.';
            document.getElementById('email_error').classList.remove('hidden');
            isValid = false;
        }
        if (password.value.trim() === '') {
            document.getElementById('password_error').textContent = 'Password wajib diisi.';
            document.getElementById('password_error').classList.remove('hidden');
            isValid = false;
        } else if (password.value.trim().length <= 6) {
            document.getElementById('password_error').textContent = 'Password harus lebih dari 6 karakter.';
            document.getElementById('password_error').classList.remove('hidden');
            isValid = false;
        }
        if (!isValid) event.preventDefault();
        return isValid;
    }
    </script>
</body>
</html>