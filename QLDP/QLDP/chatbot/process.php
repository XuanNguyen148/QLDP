<?php
require_once '../includes/db.php';
require_once 'ChatbotHandler.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'] ?? '';
    
    if (empty($message)) {
        echo json_encode(['error' => 'Tin nhắn không được để trống']);
        exit;
    }

    try {
        $chatbot = new ChatbotHandler($pdo);
        $response = $chatbot->handleMessage($message);
        echo json_encode(['response' => $response['content']]);
    } catch (Exception $e) {
        echo json_encode(['error' => 'Có lỗi xảy ra']);
    }
}
?>