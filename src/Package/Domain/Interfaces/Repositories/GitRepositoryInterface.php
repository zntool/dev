<?php

namespace ZnTool\Dev\Package\Domain\Interfaces\Repositories;

use Illuminate\Support\Collection;
use ZnCore\Base\Domain\Interfaces\GetEntityClassInterface;
use ZnTool\Dev\Package\Domain\Entities\PackageEntity;

interface GitRepositoryInterface extends GetEntityClassInterface
{

    public function isHasChanges(PackageEntity $packageEntity): bool;

    public function allChanged();

    public function allVersion(PackageEntity $packageEntity);

    public function allCommit(PackageEntity $packageEntity): Collection;

    public function allTag(PackageEntity $packageEntity): Collection;
}
