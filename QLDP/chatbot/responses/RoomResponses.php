<?php
class RoomResponses {
    public static function getResponse($message, $pdo) {
        if (strpos($message, 'phòng trống') !== false) {
            return self::getAvailableRooms($pdo);
        }
        if (strpos($message, 'giá phòng') !== false) {
            return self::getRoomPrices($pdo);
        }
        return null;
    }

    private static function getAvailableRooms($pdo) {
        $stmt = $pdo->query("SELECT COUNT(*) as count FROM PHONG WHERE TRANGTHAI = 'Trống'");
        $result = $stmt->fetch();
        return [
            'type' => 'text',
            'content' => "Hiện có {$result['count']} phòng trống."
        ];
    }

    private static function getRoomPrices($pdo) {
        $stmt = $pdo->query("SELECT TENPHONG, GIA FROM PHONG ORDER BY GIA");
        $rooms = $stmt->fetchAll();
        
        $content = "Bảng giá phòng:\n";
        foreach ($rooms as $room) {
            $content .= "{$room['TENPHONG']}: " . number_format($room['GIA']) . "đ\n";
        }
        
        return ['type' => 'text', 'content' => $content];
    }
}
?>