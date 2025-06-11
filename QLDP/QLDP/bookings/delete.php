<?php
require_once '../includes/header.php';
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM PHIEUDATPHONG WHERE MAPHIEU = ?");
$stmt->execute([$id]);
$booking = $stmt->fetch();

if ($booking && ($booking['MAND'] == $_SESSION['user_id'] || $_SESSION['role'] == 'Quản trị viên') && $booking['TRANGTHAI'] == 'Đã đặt') {
    try {
        $stmt = $pdo->prepare("UPDATE PHIEUDATPHONG SET TRANGTHAI = 'Hủy' WHERE MAPHIEU = ?");
        $stmt->execute([$id]);

        // Cập nhật trạng thái phòng
        $stmt = $pdo->prepare("UPDATE PHONG SET TRANGTHAI = 'Trống' WHERE MAPHONG = ?");
        $stmt->execute([$booking['MAPHONG']]);

        // Ghi lịch sử
        $thoigian = $booking['TGBD'] . ' - ' . $booking['TGKT'];
        $stmt = $pdo->prepare("INSERT INTO LICHSU (MAPHIEU, THOIGIAN, GHICHU) VALUES (?, ?, 'Hủy')");
        $stmt->execute([$id, $thoigian]);

        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        $error = "Lỗi: " . $e->getMessage();
    }
} else {
    $error = "Không thể hủy phiếu này!";
}
?>

<div class="bg-white p-6 rounded shadow-md">
    <h2 class="text-xl font-bold mb-4">Hủy Phiếu đặt phòng</h2>
    <p class="text-red-500"><?php echo $error; ?></p>
</div>
<?php require_once '../includes/footer.php'; ?>