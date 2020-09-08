<?php

namespace ZnTool\Dev\Generator\Domain\Libs\MigrationFieldRender;

use ZnTool\Dev\Generator\Domain\Helpers\FieldRenderHelper;

class TypeTimeRender extends BaseRender
{

    public function isMatch(): bool
    {
        return FieldRenderHelper::isMatchSuffix($this->attributeName, '_at');
    }

    public function run(): string
    {
        return $this->renderCode('dateTime', $this->attributeName);
    }

}
