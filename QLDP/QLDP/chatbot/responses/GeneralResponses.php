<?php
class GeneralResponses {
    public static function getResponse($message, $pdo = null) {
        $responses = [
            'xin chào' => 'Xin chào! Tôi có thể giúp gì cho bạn?',
            'cảm ơn' => 'Không có gì! Tôi luôn sẵn sàng hỗ trợ bạn.',
            'liên hệ' => 'Bạn có thể liên hệ với chúng tôi qua số điện thoại: 0123456789 hoặc email: support@example.com',
            'giờ làm việc' => 'Chúng tôi làm việc từ 8h00 - 22h00 hàng ngày.',
        ];

        foreach ($responses as $keyword => $response) {
            if (strpos($message, $keyword) !== false) {
                return ['type' => 'text', 'content' => $response];
            }
        }
        return null;
    }
}
?>