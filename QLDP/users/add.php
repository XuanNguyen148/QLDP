<?php
require_once '../includes/header.php';
if ($_SESSION['role'] != 'Quản trị viên') {
    header("Location: ../dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mand = $_POST['mand'];
    $hoten = $_POST['hoten'];
    $email = $_POST['email'];
    $vaitro = $_POST['vaitro'];
    $matkhau = password_hash($_POST['matkhau'], PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("INSERT INTO NGUOIDUNG (MAND, HOTEN, EMAIL, VAITRO, MATKHAU) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$mand, $hoten, $email, $vaitro, $matkhau]);
        $success = "Thêm người dùng thành công!";
    } catch (PDOException $e) {
        $error = "Lỗi: " . $e->getMessage();
    }
}
?>

<div class="bg-white p-6 rounded shadow-md">
    <h2 class="text-xl font-bold mb-4">Thêm Người dùng</h2>
    <?php if (isset($success)): ?>
        <p class="text-green-500 mb-4"><?php echo $success; ?></p>
    <?php elseif (isset($error)): ?>
        <p class="text-red-500 mb-4"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-4">
            <label class="form-label">Mã ND</label>
            <input type="text" name="mand" class="form-input" required pattern="ND\d{3}">
        </div>
        <div class="mb-4">
            <label class="form-label">Họ tên</label>
            <input type="text" name="hoten" class="form-input" required>
        </div>
        <div class="mb-4">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" required pattern=".+@gmail\.com">
        </div>
        <div class="mb-4">
            <label class="form-label">Vai trò</label>
            <select name="vaitro" class="form-select" required>
                <option value="Quản trị viên">Quản trị viên</option>
                <option value="Người dùng">Người dùng</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="form-label">Mật khẩu</label>
            <input type="password" name="matkhau" class="form-input" required>
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Thêm</button>
    </form>
</div>
<?php require_once '../includes/footer.php'; ?>