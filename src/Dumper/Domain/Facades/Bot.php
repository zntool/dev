<?php

namespace ZnTool\Dev\Dumper\Domain\Facades;

use Illuminate\Container\Container;
use ZnTool\Dev\Dumper\Domain\Repositories\Telegram\DumperRepository;

class Bot
{

    public static function send($message, $chatId = null)
    {
        $container = Container::getInstance();
        /** @var DumperRepository $repo */
        $repo = $container->get(DumperRepository::class);
        $repo->send($message, $chatId);
    }
}
