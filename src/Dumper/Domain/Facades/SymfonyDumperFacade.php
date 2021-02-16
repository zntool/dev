<?php

namespace ZnTool\Dev\Dumper\Domain\Facades;

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\AbstractDumper;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\ContextProvider\CliContextProvider;
use Symfony\Component\VarDumper\Dumper\ContextProvider\SourceContextProvider;
use Symfony\Component\VarDumper\Dumper\DataDumperInterface;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use Symfony\Component\VarDumper\Dumper\ServerDumper;
use Symfony\Component\VarDumper\VarDumper;
use ZnCore\Base\Exceptions\InvalidConfigException;
use ZnCore\Base\Helpers\EnvHelper;
use ZnTool\Dev\Dumper\Domain\Dumper\TelegramDumper;

class SymfonyDumperFacade
{

    const URL = 'tcp://127.0.0.1:9912';

    private static $driver;

    public static function dumpInConsole(string $driver)
    {
        self::$driver = $driver;
        VarDumper::setHandler([self::class, 'handler']);
    }

    public static function handler($var)
    {
        $dumper = self::createServerDumper();
        $cloner = new VarCloner();
        $dumper->dump($cloner->cloneVar($var));
    }

    private static function createServerDumper(): DataDumperInterface
    {
        $fallbackDumper = self::getDumper();
        $contextProviders = [
            'cli' => new CliContextProvider(),
            'source' => new SourceContextProvider(),
        ];
        if (self::$driver == 'telegram') {
            return new TelegramDumper(self::URL, $fallbackDumper, $contextProviders);
        } elseif (self::$driver == 'console') {
            return new ServerDumper(self::URL, $fallbackDumper, $contextProviders);
        } else {
            throw new InvalidConfigException('Unknown dumper driver "' . self::$driver . '"! See env config "VAR_DUMPER_OUTPUT".');
        }
    }

    private static function getDumper(): AbstractDumper
    {
        return EnvHelper::isConsole() ? new CliDumper() : new HtmlDumper();
    }
}
