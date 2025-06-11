<?php
require_once '../includes/header.php';
$keyword = $_GET['keyword'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM PHONG WHERE MAPHONG LIKE ? OR TENPHONG LIKE ?");
$stmt->execute(["%$keyword%", "%$keyword%"]);
$rooms = $stmt->fetchAll();
?>

<div class="bg-white p-6 rounded shadow-md">
    <h2 class="text-xl font-bold mb-4">Tìm kiếm Phòng</h2>
    <form method="GET" class="mb-4">
        <input type="text" name="keyword" value="<?php echo htmlspecialchars($keyword); ?>" class="form-input search-input" placeholder="Nhập mã hoặc tên phòng...">
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 mt-2">Tìm kiếm</button>
    </form>
    <div class="table-container">
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th>Mã Phòng</th>
                    <th>Tên Phòng</th>
                    <th>Sức chứa</th>
                    <th>Trạng thái</th>
                    <?php if ($_SESSION['role'] == 'Quản trị viên'): ?>
                        <th>Hành động</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rooms as $room): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($room['MAPHONG']); ?></td>
                        <td><?php echo htmlspecialchars($room['TENPHONG']); ?></td>
                        <td><?php echo htmlspecialchars($room['SUCCHUA']); ?></td>
                        <td><?php echo htmlspecialchars($room['TRANGTHAI']); ?></td>
                        <?php if ($_SESSION['role'] == 'Quản trị viên'): ?>
                            <td>
                                <a href="edit.php?id=<?php echo $room['MAPHONG']; ?>" class="text-blue-600 hover:underline">Sửa</a>
                                <a href="delete.php?id=<?php echo $room['MAPHONG']; ?>" class="text-red-600 hover:underline ml-2" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>