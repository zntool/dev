<?php

namespace ZnTool\Dev\Dumper\Domain\Facades;

use Illuminate\Container\EntryNotFoundException;
use ZnLib\Web\Symfony4\MicroApp\ContainerHelper;
use ZnTool\Dev\Dumper\Domain\Repositories\Telegram\DumperRepository;

class Bot
{

    public static function send($message, $chatId = null)
    {
        $dumperRepository = self::getRepository();
        $dumperRepository->send($message, $chatId);
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
