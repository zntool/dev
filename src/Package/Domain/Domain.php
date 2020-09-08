<?php

namespace ZnTool\Dev\Package\Domain;

use ZnCore\Base\Domain\Interfaces\DomainInterface;

class Domain implements DomainInterface
{

    public function getName()
    {
        return 'package';
    }

}