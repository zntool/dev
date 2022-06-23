<?php

namespace ZnTool\Dev\Runtime\Domain\Helpers;

use ZnCore\Base\Libs\FileSystem\Helpers\FileStorageHelper;

class UsedHelper
{

    public static function register()
    {
        register_shutdown_function(function () {
            $file = __DIR__ . '/../../../../../../../common/runtime/logs/usedClasses/' . time() . '.json';
            $classes = get_declared_classes();

            $all = [];
            foreach ($classes as $class) {
                $reflection = new ReflectionClass($class);
                if ($reflection->isUserDefined()) {
                    $all['user'][] = $class;
                } else {
                    $all['system'][] = $class;
                }
            }


            $json = json_encode($all, JSON_PRETTY_PRINT);
            FileStorageHelper::save($file, $json);
        });
    }

}