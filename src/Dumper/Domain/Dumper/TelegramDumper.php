<?php

namespace ZnTool\Dev\Dumper\Domain\Dumper;

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

    public function dump(Data $data)
    {
        $this->sendMessage($data->getValue());
    }

    private function sendMessage($messageData)
    {
        $messageText = json_encode($messageData, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $this->responseService->sendMessage($this->chatId, $messageText);
    }
}
