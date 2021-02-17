<?php

namespace ZnTool\Dev\VarDumper\Dumper;

use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\VarDumper\Dumper\DataDumperInterface;
use ZnLib\Telegram\Domain\Facades\Bot;
use ZnLib\Telegram\Domain\Facades\BotFacade;

class TelegramDumper implements DataDumperInterface
{

    private $responseService;
    private $chatId;

    public function __construct(string $botToken, int $chatId)
    {
        $this->responseService = BotFacade::getResponseService($botToken);
        $this->chatId = $chatId;
    }

    public function dump(Data $data, $output = null)
    {
        $value = $data->getValue(true);
        $this->sendMessage($value);
    }

    private function sendMessage($messageData)
    {
        $messageText = json_encode($messageData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $messageArray = str_split($messageText, 4096);
        foreach ($messageArray as $messageItem) {
            $this->responseService->sendMessage($this->chatId, $messageItem);
        }
    }
}
