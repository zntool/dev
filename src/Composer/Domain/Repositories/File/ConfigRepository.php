<?php

namespace ZnTool\Dev\Composer\Domain\Repositories\File;

use ZnTool\Dev\Package\Domain\Entities\ConfigEntity;
use ZnTool\Dev\Composer\Domain\Interfaces\Repositories\ConfigRepositoryInterface;

class ConfigRepository implements ConfigRepositoryInterface
{

    public function tableName() : string
    {
        return 'package_config';
    }

    public function getEntityClass() : string
    {
        return ConfigEntity::class;
    }

}
