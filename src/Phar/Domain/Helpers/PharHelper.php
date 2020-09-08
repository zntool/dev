<?php

namespace ZnTool\Dev\Phar\Domain\Helpers;

use ZnCore\Base\Legacy\Yii\Helpers\FileHelper;
use ZnCore\Base\Libs\Store\StoreFile;

class PharHelper
{

    public static function loadConfig($profileName): array {
        $config = null;
        if(isset($_ENV['PHAR_CONFIG_FILE']) && file_exists(FileHelper::path($_ENV['PHAR_CONFIG_FILE']))) {
            $store = new StoreFile(FileHelper::path($_ENV['PHAR_CONFIG_FILE']));
            $config = $store->load();
        }
        if(isset($config['profiles'][$profileName])) {
            return $config['profiles'][$profileName];
        }
    }
}