<?php

use ZnTool\Dev\Dumper\Domain\Repositories\Telegram\DumperRepository;

return [
    DumperRepository::class => function () {
        return new DumperRepository($_ENV['DUMPER_BOT_TOKEN'], $_ENV['DUMPER_BOT_ADMIN_ID']);
    },
];
