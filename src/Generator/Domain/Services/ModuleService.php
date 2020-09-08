<?php

namespace ZnTool\Dev\Generator\Domain\Services;

use ZnCore\Base\Legacy\Yii\Helpers\Inflector;
use ZnCore\Base\Helpers\ClassHelper;
use ZnTool\Dev\Generator\Domain\Dto\BuildDto;
use ZnTool\Dev\Generator\Domain\Interfaces\Services\ModuleServiceInterface;
use ZnTool\Dev\Generator\Domain\Scenarios\Generate\BaseScenario;

class ModuleService implements ModuleServiceInterface
{

    public function generate(BuildDto $buildDto)
    {
        $type = Inflector::classify($buildDto->typeModule);
        $scenarioInstance = $this->createScenarioByTypeName($type);
        $scenarioParams = [
            'buildDto' => $buildDto,
            'moduleNamespace' => $buildDto->moduleNamespace,
        ];
        ClassHelper::configure($scenarioInstance, $scenarioParams);
        $scenarioInstance->init();
        $scenarioInstance->run();
    }

    private function createScenarioByTypeName($type): BaseScenario
    {
        $scenarioClass = 'ZnTool\\Dev\\Generator\\Domain\Scenarios\\Generate\\' . $type . 'Scenario';
        /** @var BaseScenario $scenarioInstance */
        $scenarioInstance = new $scenarioClass;
        return $scenarioInstance;
    }

}
