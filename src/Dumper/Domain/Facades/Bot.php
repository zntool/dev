<?php

namespace ZnTool\Dev\Dumper\Domain\Facades;

use Illuminate\Container\EntryNotFoundException;
use ZnCore\Base\Libs\App\Helpers\ContainerHelper;
use ZnTool\Dev\Dumper\Domain\Repositories\Telegram\DumperRepository;

class Bot
{

    public static function dump($message, $chatId = null, bool $isEncode = true)
    {
        $dumperRepository = self::getRepository();
        $dumperRepository->send($message, $chatId, $isEncode);
    }

    public static function send($message, $chatId = null, bool $isEncode = true)
    {
        self::dump($message, $chatId, $isEncode);
    }

    public static function sendAsString($message, $chatId = null)
    {
        $dumperRepository = self::getRepository();
        $dumperRepository->send($message, $chatId, false);
    }

    private static function getRepository(): DumperRepository
    {
        $container = ContainerHelper::getContainer();
        try {
            $dumperRepository = $container->get(DumperRepository::class);
        } catch (EntryNotFoundException $e) {
            $dumperRepository = new DumperRepository($_ENV['DUMPER_BOT_TOKEN'], $_ENV['DUMPER_BOT_ADMIN_ID']);
        }
        return $dumperRepository;
    }
}
