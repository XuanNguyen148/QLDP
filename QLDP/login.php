<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];       // dùng đúng tên viết thường
    $password = $_POST['password'];

    // Truy vấn người dùng theo email
    $stmt = $pdo->prepare("SELECT * FROM NGUOIDUNG WHERE EMAIL = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // So sánh mật khẩu kiểu thường (KHÔNG dùng password_hash nữa)
    if ($user && $password === $user['MATKHAU']) {
        $_SESSION['user_id'] = $user['MAND'];
        $_SESSION['role'] = $user['VAITRO'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Email hoặc mật khẩu không đúng!";
    }
}
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Đăng nhập</h2>
        <?php if (isset($error)): ?>
            <p class="text-red-500 mb-4"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-4">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input" required>
            </div>
            <div class="mb-4">
                <label class="form-label">Mật khẩu</label>
                <input type="password" name="password" class="form-input" required>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">Đăng nhập</button>
        </form>
    </div>
</body>
</html>