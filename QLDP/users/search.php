<?php
require_once '../includes/header.php';
if ($_SESSION['role'] != 'Quản trị viên') {
    header("Location: ../dashboard.php");
    exit();
}
$keyword = $_GET['keyword'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM NGUOIDUNG WHERE MAND LIKE ? OR HOTEN LIKE ? OR EMAIL LIKE ?");
$stmt->execute(["%$keyword%", "%$keyword%", "%$keyword%"]);
$users = $stmt->fetchAll();
?>

<div class="bg-white p-6 rounded shadow-md">
    <h2 class="text-xl font-bold mb-4">Tìm kiếm Người dùng</h2>
    <form method="GET" class="mb-4">
        <input type="text" name="keyword" value="<?php echo htmlspecialchars($keyword); ?>" class="form-input search-input" placeholder="Nhập mã, họ tên hoặc email...">
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 mt-2">Tìm kiếm</button>
    </form>
    <div class="table-container">
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th>Mã ND</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Vai trò</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['MAND']); ?></td>
                        <td><?php echo htmlspecialchars($user['HOTEN']); ?></td>
                        <td><?php echo htmlspecialchars($user['EMAIL']); ?></td>
                        <td><?php echo htmlspecialchars($user['VAITRO']); ?></td>
                        <td>
                            <a href="edit.php?id=<?php echo $user['MAND']; ?>" class="text-blue-600 hover:underline">Sửa</a>
                            <a href="delete.php?id=<?php echo $user['MAND']; ?>" class="text-red-600 hover:underline ml-2" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>