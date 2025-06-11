<?php
require_once '../includes/header.php';
$keyword = $_GET['keyword'] ?? '';
if ($_SESSION['role'] == 'Quản trị viên') {
    $stmt = $pdo->prepare("SELECT p.*, n.HOTEN, r.TENPHONG FROM PHIEUDATPHONG p JOIN NGUOIDUNG n ON p.MAND = n.MAND JOIN PHONG r ON p.MAPHONG = r.MAPHONG WHERE p.MAPHIEU LIKE ? OR n.HOTEN LIKE ? OR r.TENPHONG LIKE ? OR p.MUCDICH LIKE ?");
    $stmt->execute(["%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%"]);
} else {
    $stmt = $pdo->prepare("SELECT p.*, n.HOTEN, r.TENPHONG FROM PHIEUDATPHONG p JOIN NGUOIDUNG n ON p.MAND = n.MAND JOIN PHONG r ON p.MAPHONG = r.MAPHONG WHERE p.MAND = ? AND (p.MAPHIEU LIKE ? OR n.HOTEN LIKE ? OR r.TENPHONG LIKE ? OR p.MUCDICH LIKE ?)");
    $stmt->execute([$_SESSION['user_id'], "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%"]);
}
$bookings = $stmt->fetchAll();
?>

<div class="bg-white p-6 rounded shadow-md">
    <h2 class="text-xl font-bold mb-4">Tìm kiếm Phiếu đặt phòng</h2>
    <form method="GET" class="mb-4">
        <input type="text" name="keyword" value="<?php echo htmlspecialchars($keyword); ?>" class="form-input search-input" placeholder="Nhập mã, người đặt, phòng hoặc mục đích...">
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 mt-2">Tìm kiếm</button>
    </form>
    <div class="table-container">
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th>Mã Phiếu</th>
                    <th>Người đặt</th>
                    <th>Phòng</th>
                    <th>Thời gian bắt đầu</th>
                    <th>Thời gian kết thúc</th>
                    <th>Mục đích</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($booking['MAPHIEU']); ?></td>
                        <td><?php echo htmlspecialchars($booking['HOTEN']); ?></td>
                        <td><?php echo htmlspecialchars($booking['TENPHONG']); ?></td>
                        <td><?php echo htmlspecialchars($booking['TGBD']); ?></td>
                        <td><?php echo htmlspecialchars($booking['TGKT']); ?></td>
                        <td><?php echo htmlspecialchars($booking['MUCDICH']); ?></td>
                        <td><?php echo htmlspecialchars($booking['TRANGTHAI']); ?></td>
                        <td>
                            <?php if ($booking['TRANGTHAI'] == 'Đã đặt' && ($_SESSION['role'] == 'Quản trị viên' || $booking['MAND'] == $_SESSION['user_id'])): ?>
                                <a href="delete.php?id=<?php echo $booking['MAPHIEU']; ?>" class="text-red-600 hover:underline" onclick="return confirm('Bạn có chắc muốn hủy?')">Hủy</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>