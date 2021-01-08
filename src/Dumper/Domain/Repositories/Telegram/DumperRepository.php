<?php

namespace ZnTool\Dev\Dumper\Domain\Repositories\Telegram;

class DumperRepository
{

    private $token;
    private $adminId;

    public function __construct(string $token, int $adminId)
    {
        $this->token = $token;
        $this->adminId = $adminId;
    }

    public function send($messageData, $chatId = null, bool $isEncode = true)
    {
        $url = "https://api.telegram.org/bot" . $this->token . '/sendMessage';
        if($isEncode) {
            $messageText = json_encode($messageData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } else {
            $messageText = $messageData;
        }
        $chatId = $chatId ?: $this->adminId;
        $body = [
            'chat_id' => $chatId,
            'text' => $messageText,
        ];
        return $this->sendPostRequest($url, $body);
    }

    private function sendPostRequest(string $url, array $body)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($body));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
