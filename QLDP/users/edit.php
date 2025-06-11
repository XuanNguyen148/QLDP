<?php
require_once '../includes/header.php';
if ($_SESSION['role'] != 'Quản trị viên') {
    header("Location: ../dashboard.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM NGUOIDUNG WHERE MAND = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hoten = $_POST['hoten'];
    $email = $_POST['email'];
    $vaitro = $_POST['vaitro'];
    $matkhau = !empty($_POST['matkhau']) ? password_hash($_POST['matkhau'], PASSWORD_BCRYPT) : $user['MATKHAU'];

    try {
        $stmt = $pdo->prepare("UPDATE NGUOIDUNG SET HOTEN = ?, EMAIL = ?, VAITRO = ?, MATKHAU = ? WHERE MAND = ?");
        $stmt->execute([$hoten, $email, $vaitro, $matkhau, $id]);
        $success = "Cập nhật người dùng thành công!";
    } catch (PDOException $e) {
        $error = "Lỗi: " . $e->getMessage();
    }
}
?>

<div class="bg-white p-6 rounded shadow-md">
    <h2 class="text-xl font-bold mb-4">Sửa Người dùng</h2>
    <?php if (isset($success)): ?>
        <p class="text-green-500 mb-4"><?php echo $success; ?></p>
    <?php elseif (isset($error)): ?>
        <p class="text-red-500 mb-4"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-4">
            <label class="form-label">Mã ND</label>
            <input type="text" value="<?php echo htmlspecialchars($user['MAND']); ?>" class="form-input" disabled>
        </div>
        <div class="mb-4">
            <label class="form-label">Họ tên</label>
            <input type="text" name="hoten" value="<?php echo htmlspecialchars($user['HOTEN']); ?>" class="form-input" required>
        </div>
        <div class="mb-4">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['EMAIL']); ?>" class="form-input" required pattern=".+@gmail\.com">
        </div>
        <div class="mb-4">
            <label class="form-label">Vai trò</label>
            <select name="vaitro" class="form-select" required>
                <option value="Quản trị viên" <?php if ($user['VAITRO'] == 'Quản trị viên') echo 'selected'; ?>>Quản trị viên</option>
                <option value="Người dùng" <?php if ($user['VAITRO'] == 'Người dùng') echo 'selected'; ?>>Người dùng</option>
            </select>
        </div>
        <div class="mb-4">
            <label class="form-label">Mật khẩu (để trống nếu không đổi)</label>
            <input type="password" name="matkhau" class="form-input">
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Cập nhật</button>
    </form>
</div>
<?php require_once '../includes/footer.php'; ?>