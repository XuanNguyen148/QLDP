<?php
require_once '../includes/header.php';
if ($_SESSION['role'] != 'Quản trị viên') {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
try {
    $stmt = $pdo->prepare("DELETE FROM PHONG WHERE MAPHONG = ?");
    $stmt->execute([$id]);
    header("Location: index.php");
    exit();
} catch (PDOException $e) {
    $error = "Lỗi: " . $e->getMessage();
}
?>

<div class="bg-white p-6 rounded shadow-md">
    <h2 class="text-xl font-bold mb-4">Xóa Phòng</h2>
    <p class="text-red-500"><?php echo $error; ?></p>
</div>
<?php require_once '../includes/footer.php'; ?>