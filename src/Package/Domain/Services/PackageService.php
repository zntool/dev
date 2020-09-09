<?php

namespace ZnTool\Dev\Package\Domain\Services;

use ZnCore\Domain\Base\BaseCrudService;
use ZnTool\Dev\Package\Domain\Interfaces\Repositories\PackageRepositoryInterface;
use ZnTool\Dev\Package\Domain\Interfaces\Services\PackageServiceInterface;

class PackageService extends BaseCrudService implements PackageServiceInterface
{

    public function __construct(PackageRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

}
