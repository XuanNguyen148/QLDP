<?php
// Tắt hiển thị lỗi PHP
error_reporting(0);
ini_set('display_errors', 0);

// Đảm bảo luôn trả về JSON
header('Content-Type: application/json');

try {
    require_once __DIR__ . '/../includes/db.php';
    require_once __DIR__ . '/ChatbotHandler.php';

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Phương thức không được hỗ trợ');
    }

    $message = $_POST['message'] ?? '';
    
    if (empty($message)) {
        throw new Exception('Vui lòng nhập tin nhắn của bạn');
    }

    if (!isset($pdo)) {
        throw new Exception('Không thể kết nối đến cơ sở dữ liệu');
    }
    
    $chatbot = new ChatbotHandler($pdo);
    $response = $chatbot->handleMessage($message);
    
    if (!isset($response['content'])) {
        throw new Exception('Định dạng phản hồi không hợp lệ');
    }
    
    echo json_encode(['response' => $response['content']]);
} catch (Exception $e) {
    error_log("Chatbot Error: " . $e->getMessage());
    echo json_encode(['response' => 'Xin lỗi, có lỗi xảy ra: ' . $e->getMessage()]);
}
?>