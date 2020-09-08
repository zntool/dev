<?php

namespace ZnTool\Dev\Stress\Domain;

use ZnCore\Base\Domain\Interfaces\DomainInterface;

class Domain implements DomainInterface
{

    public function getName()
    {
        return 'dev';
    }

}