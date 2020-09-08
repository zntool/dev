<?php

namespace ZnTool\Dev\Generator\Domain\Interfaces\Services;

use ZnTool\Dev\Generator\Domain\Dto\BuildDto;

interface ModuleServiceInterface
{

    public function generate(BuildDto $buildDto);

}
