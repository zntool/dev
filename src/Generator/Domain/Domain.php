<?php

namespace ZnTool\Dev\Generator\Domain;

use ZnCore\Base\Domain\Interfaces\DomainInterface;

class Domain implements DomainInterface
{

    public function getName()
    {
        return 'generator';
    }

}