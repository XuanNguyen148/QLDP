<?php
require_once '../includes/header.php';
if ($_SESSION['role'] == 'Quản trị viên') {
    $stmt = $pdo->query("SELECT l.*, p.MAND, n.HOTEN, r.TENPHONG FROM LICHSU l JOIN PHIEUDATPHONG p ON l.MAPHIEU = p.MAPHIEU JOIN NGUOIDUNG n ON p.MAND = n.MAND JOIN PHONG r ON p.MAPHONG = r.MAPHONG");
} else {
    $stmt = $pdo->prepare("SELECT l.*, p.MAND, n.HOTEN, r.TENPHONG FROM LICHSU l JOIN PHIEUDATPHONG p ON l.MAPHIEU = p.MAPHIEU JOIN NGUOIDUNG n ON p.MAND = n.MAND JOIN PHONG r ON p.MAPHONG = r.MAPHONG WHERE p.MAND = ?");
    $stmt->execute([$_SESSION['user_id']]);
}
$history = $stmt->fetchAll();
?>

<div class="bg-white p-6 rounded shadow-md">
    <h2 class="text-xl font-bold mb-4">Lịch sử đặt phòng</h2>
    <!-- Form tìm kiếm trỏ tới search.php -->
    <form action="search.php" method="get" class="mb-4 flex">
        <input type="text" name="search" class="form-input px-3 py-2 rounded border w-full" placeholder="Tìm kiếm phòng...">
        <button type="submit" class="ml-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Tìm</button>
    </form>

    <div class="table-container">
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th>Mã Lịch sử</th>
                    <th>Mã Phiếu</th>
                    <th>Người đặt</th>
                    <th>Phòng</th>
                    <th>Thời gian</th>
                    <th>Ghi chú</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($history as $record): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['MALS']); ?></td>
                        <td><?php echo htmlspecialchars($record['MAPHIEU']); ?></td>
                        <td><?php echo htmlspecialchars($record['HOTEN']); ?></td>
                        <td><?php echo htmlspecialchars($record['TENPHONG']); ?></td>
                        <td><?php echo htmlspecialchars($record['THOIGIAN']); ?></td>
                        <td><?php echo htmlspecialchars($record['GHICHU']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>