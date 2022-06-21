<?php

namespace ZnTool\Dev\Runtime\Domain;

use ZnCore\Base\Libs\Domain\Interfaces\DomainInterface;

class Domain implements DomainInterface
{

    public function getName()
    {
        return 'runtime';
    }

}