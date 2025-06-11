<?php
require_once '../includes/header.php';
if ($_SESSION['role'] != 'Quản trị viên') {
    header("Location: ../dashboard.php");
    exit();
}
$stmt = $pdo->query("SELECT r.MAPHONG, r.TENPHONG, COUNT(p.MAPHIEU) as SOLUOT FROM PHONG r LEFT JOIN PHIEUDATPHONG p ON r.MAPHONG = p.MAPHONG GROUP BY r.MAPHONG, r.TENPHONG");
$usage = $stmt->fetchAll();
?>

<h2 class="text-xl font-bold mb-4">Báo cáo số lượt sử dụng phòng</h2>

<!-- Link đến báo cáo theo mục đích -->
<a href="purpose.php" class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
    Xem báo cáo theo mục đích sử dụng
</a>

<div class="table-container">
    <table class="table-auto w-full">
            <thead>
                <tr>
                    <th>Mã Phòng</th>
                    <th>Tên Phòng</th>
                    <th>Số lượt sử dụng</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usage as $record): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['MAPHONG']); ?></td>
                        <td><?php echo htmlspecialchars($record['TENPHONG']); ?></td>
                        <td><?php echo htmlspecialchars($record['SOLUOT']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<?php require_once '../includes/footer.php'; ?>