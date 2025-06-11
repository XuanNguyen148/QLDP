<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
require_once __DIR__ . '/../config/database.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đặt phòng</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="/QLDP/css/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="/QLDP/assets/css/chatbot.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="/QLDP/assets/js/chatbot.js" defer></script>
    <script src="/QLDP/js/scripts.js" defer></script>
</head>
<body class="bg-gray-100">
    <?php include_once __DIR__ . '/components/chatbot.php'; ?>
    <nav class="bg-blue-600 p-4 text-white shadow-md">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">Quản lý đặt phòng</h1>
            <div class="flex items-center space-x-4">
                <span>Xin chào, <?php echo htmlspecialchars($_SESSION['user_id']); ?> (<?php echo htmlspecialchars($_SESSION['role']); ?>)</span>
                <a href="../logout.php" class="hover:underline">Đăng xuất</a>
            </div>
        </div>
    </nav>
    <div class="container mx-auto mt-6">
        <div class="flex flex-wrap gap-4 mb-6">
            <a href="/QLDP/dashboard.php" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Tổng quan</a>
            <a href="/QLDP/rooms/index.php" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Quản lý Phòng</a>
            <?php if ($_SESSION['role'] == 'Quản trị viên'): ?>
                <a href="/QLDP/users/index.php" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Quản lý Người dùng</a>
            <?php endif; ?>
            <a href="/QLDP/bookings/index.php" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Quản lý Phiếu đặt phòng</a>
            <a href="/QLDP/history/index.php" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Lịch sử</a>
            <?php if ($_SESSION['role'] == 'Quản trị viên'): ?>
                <a href="/QLDP/reports/usage.php" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Báo cáo</a>
            <?php endif; ?>
        </div>