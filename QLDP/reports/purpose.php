<?php
require_once '../includes/header.php';
if ($_SESSION['role'] != 'Quản trị viên') {
    header("Location: ../dashboard.php");
    exit();
}
$stmt = $pdo->query("SELECT MUCDICH, COUNT(*) as SOLUONG FROM PHIEUDATPHONG GROUP BY MUCDICH");
$purposes = $stmt->fetchAll();
?>

<div class="bg-white p-6 rounded shadow-md">
    <h2 class="text-xl font-bold mb-4">Báo cáo theo mục đích sử dụng</h2>
    <div class="table-container">
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th>Mục đích</th>
                    <th>Số lượng</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($purposes as $record): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['MUCDICH']); ?></td>
                        <td><?php echo htmlspecialchars($record['SOLUONG']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>