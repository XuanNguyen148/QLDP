<?php
require_once '../includes/header.php';
if ($_SESSION['role'] != 'Quản trị viên') {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM PHONG WHERE MAPHONG = ?");
$stmt->execute([$id]);
$room = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tenphong = $_POST['tenphong'];
    $succhan = $_POST['succhan'];
    $trangthai = $_POST['trangthai'];

    try {
        $stmt = $pdo->prepare("UPDATE PHONG SET TENPHONG = ?, SUCCHUA = ?, TRANGTHAI = ? WHERE MAPHONG = ?");
        $stmt->execute([$tenphong, $succhan, $trangthai, $id]);
        $success = "Cập nhật phòng thành công!";
    } catch (PDOException $e) {
        $error = "Lỗi: " . $e->getMessage();
    }
}
?>

<div class="bg-white p-6 rounded shadow-md">
    <h2 class="text-xl font-bold mb-4">Sửa Phòng</h2>
    <?php if (isset($success)): ?>
        <p class="text-green-500 mb-4"><?php echo $success; ?></p>
    <?php elseif (isset($error)): ?>
        <p class="text-red-500 mb-4"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-4">
            <label class="form-label">Mã Phòng</label>
            <input type="text" value="<?php echo htmlspecialchars($room['MAPHONG']); ?>" class="form-input" disabled>
        </div>
        <div class="mb-4">
            <label class="form-label">Tên Phòng</label>
            <input type="text" name="tenphong" value="<?php echo htmlspecialchars($room['TENPHONG']); ?>" class="form-input" required>
        </div>
        <div class="mb-4">
            <label class="form-label">Sức chứa</label>
            <input type="number" name="succhan" value="<?php echo htmlspecialchars($room['SUCCHUA']); ?>" class="form-input" required min="1">
        </div>
        <div class="mb-4">
            <label class="form-label">Trạng thái</label>
            <select name="trangthai" class="form-select" required>
                <option value="Trống" <?php if ($room['TRANGTHAI'] == 'Trống') echo 'selected'; ?>>Trống</option>
                <option value="Đã đặt" <?php if ($room['TRANGTHAI'] == 'Đã đặt') echo 'selected'; ?>>Đã đặt</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Cập nhật</button>
    </form>
</div>
<?php require_once '../includes/footer.php'; ?>