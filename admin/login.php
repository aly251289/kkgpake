<?php
session_start();
require_once '../config/koneksi.php';
require_once '../includes/database.php'; // Security: Include the new database helper

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Security: No need for mysqli_real_escape_string with prepared statements
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Security: Use prepared statement to prevent SQL Injection
    $query = "SELECT * FROM users WHERE username = ?";
    $result = db_query($koneksi, $query, 's', [$username]);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Security: Regenerate session ID to prevent session fixation
            session_regenerate_id(true);

            // Password correct, start session
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_name'] = $user['nama_lengkap'];
            $_SESSION['admin_role'] = $user['role'];
            
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator - KKG MI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-xl shadow-2xl w-full max-w-md border border-gray-200">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-emerald-700">Administrator KKG MI</h1>
            <p class="text-gray-500 text-sm mt-2">Silahkan login untuk mengelola website</p>
        </div>

        <?php if($error): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 text-sm" role="alert">
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-5">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                    Username
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </span>
                    <input class="w-full pl-10 pr-3 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition" id="username" name="username" type="text" placeholder="Masukkan username" required>
                </div>
            </div>
            
            <div class="mb-8">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Password
                </label>
                <div class="relative">
                     <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    <input class="w-full pl-10 pr-3 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition" id="password" name="password" type="password" placeholder="Masukkan password" required>
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                <button class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline transition transform hover:-translate-y-1 shadow-md" type="submit">
                    Masuk Dashboard
                </button>
            </div>
            
            <div class="mt-6 text-center">
                <a href="../index.php" class="text-sm text-gray-500 hover:text-emerald-600 font-medium">
                    &larr; Kembali ke Website Utama
                </a>
            </div>
        </form>
    </div>

</body>
</html>
