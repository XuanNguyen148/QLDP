<?php
class ChatbotHandler {
    private $pdo;
    private $responses;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->loadResponses();
    }

    private function loadResponses() {
        $this->responses = [
            'general' => require_once 'responses/GeneralResponses.php',
            'room' => require_once 'responses/RoomResponses.php',
            'booking' => require_once 'responses/BookingResponses.php'
        ];
    }

    public function handleMessage($message) {
        $message = mb_strtolower(trim($message), 'UTF-8');
        
        foreach ($this->responses as $type => $responseHandler) {
            $response = $responseHandler::getResponse($message, $this->pdo);
            if ($response) return $response;
        }

        return [
            'type' => 'text',
            'content' => 'Xin lỗi, tôi không hiểu câu hỏi của bạn. Bạn có thể hỏi về đặt phòng, thông tin phòng, hoặc các dịch vụ của chúng tôi.'
        ];
    }
}

?>