<?php
class ChatbotHandler {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function handleMessage($message) {
        $message = mb_strtolower(trim($message), 'UTF-8');
        
        // Kiểm tra các từ khóa chào hỏi
        if (strpos($message, 'xin chào') !== false || strpos($message, 'chào') !== false) {
            return [
                'type' => 'text',
                'content' => 'Xin chào! Tôi có thể giúp gì cho bạn? Bạn có thể hỏi về: đặt phòng, thông tin phòng, hoặc các dịch vụ của chúng tôi.'
            ];
        }

        // Kiểm tra các từ khóa về phòng
        if (strpos($message, 'phòng') !== false) {
            // Tìm kiếm thông tin phòng cụ thể
            if (preg_match('/phòng\s+([a-z0-9]+)/i', $message, $matches)) {
                $roomId = strtoupper($matches[1]);
                return $this->getSpecificRoom($roomId);
            }
            
            // Hỏi về phòng trống
            if (strpos($message, 'trống') !== false || strpos($message, 'còn') !== false) {
                return $this->getAvailableRooms();
            }
            
            // Hỏi về giá phòng
            if (strpos($message, 'giá') !== false || strpos($message, 'bao nhiêu') !== false) {
                return $this->getRoomPrices();
            }
            
            // Hỏi về thông tin chi tiết phòng
            if (strpos($message, 'thông tin') !== false || strpos($message, 'chi tiết') !== false) {
                return $this->getRoomDetails();
            }
            
            // Hỏi về tất cả phòng
            if (strpos($message, 'tất cả') !== false || strpos($message, 'danh sách') !== false) {
                return $this->getAllRooms();
            }
        }

        // Kiểm tra các từ khóa về đặt phòng
        if (strpos($message, 'đặt phòng') !== false || strpos($message, 'đặt') !== false) {
            return [
                'type' => 'text',
                'content' => 'Để đặt phòng, bạn cần: 1. Chọn phòng phù hợp 2. Chọn thời gian 3. Điền thông tin đặt phòng. Bạn có thể vào mục "Quản lý Phiếu đặt phòng" để thực hiện.'
            ];
        }

        // Kiểm tra các từ khóa về thời gian
        if (strpos($message, 'giờ') !== false || strpos($message, 'thời gian') !== false) {
            return [
                'type' => 'text',
                'content' => 'Chúng tôi làm việc từ 7h30 đến 16h00 các ngày trong tuần. Thời gian đặt phòng phải nằm trong khoảng này.'
            ];
        }

        // Kiểm tra các từ khóa về liên hệ
        if (strpos($message, 'liên hệ') !== false || strpos($message, 'số điện thoại') !== false) {
            return [
                'type' => 'text',
                'content' => 'Bạn có thể liên hệ với chúng tôi qua số điện thoại: 0123456789 hoặc email: support@example.com'
            ];
        }

        // Nếu không tìm thấy câu trả lời phù hợp
        return [
            'type' => 'text',
            'content' => 'Xin lỗi, tôi không hiểu câu hỏi của bạn. Bạn có thể hỏi về: đặt phòng, thông tin phòng, hoặc các dịch vụ của chúng tôi.'
        ];
    }

    private function getSpecificRoom($roomId) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM PHONG WHERE MAPHONG = ?");
            $stmt->execute([$roomId]);
            $room = $stmt->fetch();
            
            if (!$room) {
                return [
                    'type' => 'text',
                    'content' => "Không tìm thấy thông tin về phòng $roomId. Vui lòng kiểm tra lại mã phòng."
                ];
            }
            
            $content = "Thông tin phòng $roomId:\n\n";
            $content .= "Mã phòng: {$room['MAPHONG']}\n";
            $content .= "Tên phòng: {$room['TENPHONG']}\n";
            $content .= "Sức chứa: {$room['SUCCHUA']} người\n";
            $content .= "Trạng thái: {$room['TRANGTHAI']}\n";
            if (isset($room['GIA'])) {
                $content .= "Giá: " . number_format($room['GIA']) . "đ\n";
            }
            
            return ['type' => 'text', 'content' => $content];
        } catch (Exception $e) {
            error_log("Error getting specific room: " . $e->getMessage());
            return [
                'type' => 'text',
                'content' => 'Xin lỗi, không thể lấy thông tin phòng lúc này.'
            ];
        }
    }

    private function getAvailableRooms() {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM PHONG WHERE TRANGTHAI = 'Trống'");
            $result = $stmt->fetch();
            return [
                'type' => 'text',
                'content' => "Hiện có {$result['count']} phòng trống. Bạn có muốn xem thông tin chi tiết về các phòng không?"
            ];
        } catch (Exception $e) {
            error_log("Error getting available rooms: " . $e->getMessage());
            return [
                'type' => 'text',
                'content' => 'Xin lỗi, không thể lấy thông tin phòng trống lúc này.'
            ];
        }
    }

    private function getRoomPrices() {
        try {
            $stmt = $this->pdo->query("SELECT TENPHONG, GIA FROM PHONG ORDER BY GIA");
            $rooms = $stmt->fetchAll();
            
            if (empty($rooms)) {
                return [
                    'type' => 'text',
                    'content' => 'Hiện không có thông tin về giá phòng.'
                ];
            }
            
            $content = "Bảng giá phòng:\n";
            foreach ($rooms as $room) {
                $content .= "{$room['TENPHONG']}: " . number_format($room['GIA']) . "đ\n";
            }
            
            return ['type' => 'text', 'content' => $content];
        } catch (Exception $e) {
            error_log("Error getting room prices: " . $e->getMessage());
            return [
                'type' => 'text',
                'content' => 'Xin lỗi, không thể lấy thông tin giá phòng lúc này.'
            ];
        }
    }

    private function getRoomDetails() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM PHONG ORDER BY MAPHONG");
            $rooms = $stmt->fetchAll();
            
            if (empty($rooms)) {
                return [
                    'type' => 'text',
                    'content' => 'Hiện không có thông tin về phòng.'
                ];
            }
            
            $content = "Thông tin chi tiết các phòng:\n\n";
            foreach ($rooms as $room) {
                $content .= "Mã phòng: {$room['MAPHONG']}\n";
                $content .= "Tên phòng: {$room['TENPHONG']}\n";
                $content .= "Sức chứa: {$room['SUCCHUA']} người\n";
                $content .= "Trạng thái: {$room['TRANGTHAI']}\n";
                if (isset($room['GIA'])) {
                    $content .= "Giá: " . number_format($room['GIA']) . "đ\n";
                }
                $content .= "------------------------\n";
            }
            
            return ['type' => 'text', 'content' => $content];
        } catch (Exception $e) {
            error_log("Error getting room details: " . $e->getMessage());
            return [
                'type' => 'text',
                'content' => 'Xin lỗi, không thể lấy thông tin chi tiết phòng lúc này.'
            ];
        }
    }

    private function getAllRooms() {
        try {
            $stmt = $this->pdo->query("SELECT MAPHONG, TENPHONG, TRANGTHAI FROM PHONG ORDER BY MAPHONG");
            $rooms = $stmt->fetchAll();
            
            if (empty($rooms)) {
                return [
                    'type' => 'text',
                    'content' => 'Hiện không có thông tin về phòng.'
                ];
            }
            
            $content = "Danh sách tất cả các phòng:\n\n";
            foreach ($rooms as $room) {
                $content .= "{$room['MAPHONG']} - {$room['TENPHONG']} ({$room['TRANGTHAI']})\n";
            }
            
            return ['type' => 'text', 'content' => $content];
        } catch (Exception $e) {
            error_log("Error getting all rooms: " . $e->getMessage());
            return [
                'type' => 'text',
                'content' => 'Xin lỗi, không thể lấy danh sách phòng lúc này.'
            ];
        }
    }
}
?>