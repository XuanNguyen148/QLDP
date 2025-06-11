<?php
require_once '../includes/header.php';
$users = $pdo->query("SELECT MAND, HOTEN FROM NGUOIDUNG")->fetchAll();
$rooms = $pdo->query("SELECT MAPHONG, TENPHONG FROM PHONG")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $maphieu = $_POST['maphieu'];
    $mand = $_SESSION['role'] == 'Quản trị viên' ? $_POST['mand'] : $_SESSION['user_id'];
    $maphong = $_POST['maphong'];
    $tgb = $_POST['tgb'];
    $tgkt = $_POST['tgkt'];
    $mucdich = $_POST['mucdich'];

    // Kiểm tra xung đột thời gian
    $stmt = $pdo->prepare("SELECT * FROM PHIEUDATPHONG WHERE MAPHONG = ? AND TRANGTHAI = 'Đã đặt' AND (
        (TGBD <= ? AND TGKT >= ?) OR (TGBD <= ? AND TGKT >= ?) OR (TGBD >= ? AND TGKT <= ?)
    )");
    $stmt->execute([$maphong, $tgb, $tgb, $tgkt, $tgkt, $tgb, $tgkt]);
    if ($stmt->fetch()) {
        $error = "Phòng đã được đặt trong khoảng thời gian này!";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO PHIEUDATPHONG (MAPHIEU, MAND, MAPHONG, TGBD, TGKT, MUCDICH, TRANGTHAI) VALUES (?, ?, ?, ?, ?, ?, 'Đã đặt')");
            $stmt->execute([$maphieu, $mand, $maphong, $tgb, $tgkt, $mucdich]);
            
            // Cập nhật trạng thái phòng
            $stmt = $pdo->prepare("UPDATE PHONG SET TRANGTHAI = 'Đã đặt' WHERE MAPHONG = ?");
            $stmt->execute([$maphong]);
            
            // Ghi lịch sử
            $thoigian = "$tgb - $tgkt";
            $stmt = $pdo->prepare("INSERT INTO LICHSU (MAPHIEU, THOIGIAN, GHICHU) VALUES (?, ?, 'Đã đặt')");
            $stmt->execute([$maphieu, $thoigian]);
            
            $success = "Tạo phiếu thành công!";
        } catch (PDOException $e) {
            $error = "Lỗi: " . $e->getMessage();
        }
    }
}
?>

<div class="bg-white p-6 rounded shadow-md">
    <h2 class="text-xl font-bold mb-4">Tạo Phiếu đặt phòng</h2>
    <?php if (isset($success)): ?>
        <p class="text-green-500 mb-4"><?php echo $success; ?></p>
    <?php elseif (isset($error)): ?>
        <p class="text-red-500 mb-4"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-4">
            <label class="form-label">Mã Phiếu</label>
            <input type="text" name="maphieu" class="form-input" required pattern="MP\d{3}">
        </div>
        <?php if ($_SESSION['role'] == 'Quản trị viên'): ?>
            <div class="mb-4">
                <label class="form-label">Người đặt</label>
                <select name="mand" class="form-select" required>
                    <?php foreach ($users as $user): ?>
                        <option value="<?php echo $user['MAND']; ?>"><?php echo htmlspecialchars($user['HOTEN']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>
        <div class="mb-4">
            <label class="form-label">Phòng</label>
            <select name="maphong" class="form-select" required>
                <?php foreach ($rooms as $room): ?>
                    <option value="<?php echo $room['MAPHONG']; ?>"><?php echo htmlspecialchars($room['TENPHONG']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-4">
            <label class="form-label">Thời gian bắt đầu</label>
            <input type="datetime-local" name="tgb" class="form-input" required>
        </div>
        <div class="mb-4">
            <label class="form-label">Thời gian kết thúc</label>
            <input type="datetime-local" name="tgkt" class="form-input" required>
        </div>
        <div class="mb-4">
            <label class="form-label">Mục đích</label>
            <select name="mucdich" class="form-select" required>
                <option value="Dạy học">Dạy học</option>
                <option value="Hội thảo">Hội thảo</option>
                <option value="Khác">Khác</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Tạo</button>
    </form>
</div>
<?php require_once '../includes/footer.php'; ?>