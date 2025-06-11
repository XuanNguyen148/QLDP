<?php
require_once '../includes/header.php';
if ($_SESSION['role'] == 'Quản trị viên') {
    $stmt = $pdo->query("SELECT p.*, n.HOTEN, r.TENPHONG FROM PHIEUDATPHONG p JOIN NGUOIDUNG n ON p.MAND = n.MAND JOIN PHONG r ON p.MAPHONG = r.MAPHONG");
} else {
    $stmt = $pdo->prepare("SELECT p.*, n.HOTEN, r.TENPHONG FROM PHIEUDATPHONG p JOIN NGUOIDUNG n ON p.MAND = n.MAND JOIN PHONG r ON p.MAPHONG = r.MAPHONG WHERE p.MAND = ?");
    $stmt->execute([$_SESSION['user_id']]);
}
$bookings = $stmt->fetchAll();
?>

<div class="bg-white p-6 rounded shadow-md">
    <h2 class="text-xl font-bold mb-4">Danh sách Phiếu đặt phòng</h2>
    <a href="add.php" class="mb-4 inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Tạo Phiếu</a>
    <!-- Form tìm kiếm trỏ tới search.php -->
    <form action="search.php" method="get" class="mb-4 flex">
        <input type="text" name="search" class="form-input px-3 py-2 rounded border w-full" placeholder="Tìm kiếm phòng...">
        <button type="submit" class="ml-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Tìm</button>
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