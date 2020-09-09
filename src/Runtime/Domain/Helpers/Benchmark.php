<?php

namespace ZnTool\Dev\Runtime\Domain\Helpers;

use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use php7extension\yii\web\ServerErrorHttpException;
use php7rails\app\helpers\EnvService;
use ZnCore\Base\Helpers\StringHelper;
use ZnCore\Base\Libs\Store\StoreFile;

class Benchmark
{

    private static $data = [];
    private static $sessionId = null;

    public static function begin($name, $data = null)
    {
        $microTime = microtime(true);
        if ( ! self::isEnable()) {
            return;
        }
        $name = self::getName($name);
        $item['name'] = $name;
        $item['begin'] = $microTime;
        $item['data'] = [$data];
        self::append($item);
    }

    public static function end($name, $data = null)
    {
        $microTime = microtime(true);
        if ( ! self::isEnable()) {
            return;
        }
        $name = self::getName($name);
        if ( ! isset(self::$data[$name])) {
            return;
        }
        $item = self::$data[$name];
        if (isset($item['end'])) {
            return;
        }

        if ( ! isset($item['begin'])) {
            throw new ServerErrorHttpException('Benchmark not be started!');
        }
        $item['end'] = $microTime;
        if ($data) {
            $item['data'][] = $data;
        }
        self::append($item);
    }

    public static function flushAll()
    {
        self::$data = [];
    }

    public static function all()
    {
        return self::$data;
    }

    public static function allFlat($percision = 5)
    {
        $durations = ArrayHelper::map(self::$data, 'name', 'duration');
        $durations = array_map(function ($value) {
            return round($value, $percision);
        }, $durations);
        return $durations;
    }

    private static function getName($name)
    {
        if (is_string($name)) {
            return $name;
        }
        $scope = microtime(true) . '_' . serialize($name);
        $hash = hash('md5', $scope);
        return $hash;
    }

    private static function isEnable()
    {
        return true;
        return EnvService::get('mode.benchmark', false);
    }

    private static function getRequestId()
    {
        if ( ! self::$sessionId) {
            self::$sessionId = time() . '.' . StringHelper::generateRandomString();
        }
        return self::$sessionId;
    }

    private static function getFileName()
    {
        $dir = ROOT_DIR . '/common/runtime/logs/benchmark';
        $file = self::getRequestId() . '.json';
        return $dir . DIRECTORY_SEPARATOR . $file;
    }

    private static function getStoreInstance()
    {
        $fileName = self::getFileName();
        $store = new StoreFile($fileName);
        return $store;
    }

    private static function append($item)
    {
        $name = $item['name'];
        if ( ! empty($item['end'])) {
            $item['duration'] = $item['end'] - $item['begin'];
        }
        self::$data[$name] = $item;
        if ( ! empty($item['duration'])) {
            /*$store = self::getStoreInstance();
            $store->save([
                '_SERVER' => $_SERVER,
                'data' => self::$data,

            ]);*/
        }
    }

}