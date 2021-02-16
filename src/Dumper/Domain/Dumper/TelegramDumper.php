<?php

namespace ZnTool\Dev\Dumper\Domain\Dumper;

use Symfony\Component\VarDumper\Cloner\Data;
use Symfony\Component\VarDumper\Dumper\DataDumperInterface;
use ZnLib\Telegram\Domain\Facades\Bot;

class TelegramDumper implements DataDumperInterface
{

    public function dump(Data $data)
    {
        Bot::dump($data->getValue());
    }
}
