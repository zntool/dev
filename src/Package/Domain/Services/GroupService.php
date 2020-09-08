<?php

namespace ZnTool\Dev\Package\Domain\Services;

use ZnCore\Base\Domain\Base\BaseCrudService;
use ZnTool\Dev\Package\Domain\Repositories\File\GroupRepository;

class GroupService extends BaseCrudService
{

    public function __construct(GroupRepository $repository)
    {
        $this->repository = $repository;
    }

}
