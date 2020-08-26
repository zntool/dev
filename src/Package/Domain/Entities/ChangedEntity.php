<?php

namespace PhpLab\Dev\Package\Domain\Entities;

use PhpLab\Core\Domain\Interfaces\Entity\EntityIdInterface;

class ChangedEntity
{

    private $package;
    private $status;

    public function getPackage(): PackageEntity
    {
        return $this->package;
    }

    public function setPackage(PackageEntity $package): void
    {
        $this->package = $package;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status): void
    {
        $this->status = $status;
    }
}
