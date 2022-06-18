<?php

namespace ZnTool\Dev\VarDumper\Subscribers;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\EventDispatcher\Event;
use ZnCore\Base\Libs\Container\Traits\ContainerAwareTrait;
use ZnSandbox\Sandbox\App\Enums\AppEventEnum;
use ZnTool\Dev\VarDumper\Facades\SymfonyDumperFacade;

/**
 * Инициализация дампера (dd() и dump())
 *
 * VAR_DUMPER_OUTPUT - Канал вывода дампа
 * возможные значения: console, telegram
 * Например: VAR_DUMPER_OUTPUT=telegram
 *
 * VAR_DUMPER_BOT_TOKEN - токен бота-отправителя сообщений
 * Например: VAR_DUMPER_BOT_TOKEN=999999999:zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz
 *
 * VAR_DUMPER_BOT_CHAT_ID - ID канала/пользователя для получения сообщений
 * Например: VAR_DUMPER_BOT_CHAT_ID=99999999
 * можно узнать свой ID можно с помощью бота @username_to_id_bot
 *
 * Для запуска консоли дампера выполните:
 *  php vendor/symfony/var-dumper/Resources/bin/var-dump-server
 */
class SymfonyDumperSubscriber implements EventSubscriberInterface
{

    use ContainerAwareTrait;

    public static function getSubscribedEvents(): array
    {
        return [
            AppEventEnum::AFTER_INIT_ENV => 'onAnyEvent',
        ];
    }

    public function onAnyEvent(Event $event, string $eventName)
    {
        if (isset($_ENV['VAR_DUMPER_OUTPUT'])) {
            SymfonyDumperFacade::dumpInConsole($_ENV['VAR_DUMPER_OUTPUT']);
        }
    }
}
