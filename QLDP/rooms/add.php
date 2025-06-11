<?php
require_once '../includes/header.php';
if ($_SESSION['role'] != 'Quản trị viên') {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $maphong = $_POST['maphong'];
    $tenphong = $_POST['tenphong'];
    $succhan = $_POST['succhan'];
    $trangthai = $_POST['trangthai'];

    try {
        $stmt = $pdo->prepare("INSERT INTO PHONG (MAPHONG, TENPHONG, SUCCHUA, TRANGTHAI) VALUES (?, ?, ?, ?)");
        $stmt->execute([$maphong, $tenphong, $succhan, $trangthai]);
        $success = "Thêm phòng thành công!";
    } catch (PDOException $e) {
        $error = "Lỗi: " . $e->getMessage();
    }
}
?>

<div class="bg-white p-6 rounded shadow-md">
    <h2 class="text-xl font-bold mb-4">Thêm Phòng</h2>
    <?php if (isset($success)): ?>
        <p class="text-green-500 mb-4"><?php echo $success; ?></p>
    <?php elseif (isset($error)): ?>
        <p class="text-red-500 mb-4"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-4">
            <label class="form-label">Mã Phòng</label>
            <input type="text" name="maphong" class="form-input" required pattern=".{5}">
        </div>
        <div class="mb-4">
            <label class="form-label">Tên Phòng</label>
            <input type="text" name="tenphong" class="form-input" required>
        </div>
        <div class="mb-4">
            <label class="form-label">Sức chứa</label>
            <input type="number" name="succhan" class="form-input" required min="1">
        </div>
        <div class="mb-4">
            <label class="form-label">Trạng thái</label>
            <select name="trangthai" class="form-select" required>
                <option value="Trống">Trống</option>
                <option value="Đã đặt">Đã đặt</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Thêm</button>
    </form>
</div>
<?php require_once '../includes/footer.php'; ?>