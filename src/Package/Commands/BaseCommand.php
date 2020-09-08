<?php

namespace ZnTool\Dev\Package\Commands;

use ZnTool\Dev\Package\Domain\Interfaces\Services\GitServiceInterface;
use ZnTool\Dev\Package\Domain\Interfaces\Services\PackageServiceInterface;
use Symfony\Component\Console\Command\Command;

abstract class BaseCommand extends Command
{

    protected $packageService;
    protected $gitService;

    public function __construct(?string $name = null, PackageServiceInterface $packageService, GitServiceInterface $gitService)
    {
        parent::__construct($name);
        $this->packageService = $packageService;
        $this->gitService = $gitService;
    }

}
