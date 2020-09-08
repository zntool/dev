<?php

namespace ZnTool\Dev\Package\Domain\Interfaces\Repositories;

use ZnCore\Base\Domain\Interfaces\GetEntityClassInterface;
use ZnCore\Base\Domain\Interfaces\ReadAllInterface;
use ZnCore\Base\Domain\Interfaces\Repository\ReadOneInterface;
use ZnCore\Base\Domain\Interfaces\Repository\RelationConfigInterface;
use ZnCore\Base\Domain\Interfaces\Repository\RepositoryInterface;

interface PackageRepositoryInterface extends RepositoryInterface, GetEntityClassInterface, ReadAllInterface, ReadOneInterface, RelationConfigInterface
{

}
