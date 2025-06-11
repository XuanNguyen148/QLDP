<?php
require_once '../includes/header.php';
if ($_SESSION['role'] != 'Quản trị viên') {
    header("Location: ../dashboard.php");
    exit();
}
$stmt = $pdo->query("SELECT * FROM NGUOIDUNG");
$users = $stmt->fetchAll();
?>

<div class="bg-white p-6 rounded shadow-md">
    <h2 class="text-xl font-bold mb-4">Danh sách Người dùng</h2>
    <a href="add.php" class="mb-4 inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Thêm Người dùng</a>
    <!-- Form tìm kiếm trỏ tới search.php -->
    <form action="search.php" method="get" class="mb-4 flex">
        <input type="text" name="search" class="form-input px-3 py-2 rounded border w-full" placeholder="Tìm kiếm phòng...">
        <button type="submit" class="ml-2 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Tìm</button>
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