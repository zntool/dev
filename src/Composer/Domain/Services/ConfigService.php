<?php

namespace ZnTool\Dev\Composer\Domain\Services;

use ZnCore\Collection\Interfaces\Enumerable;
use ZnCore\Collection\Libs\Collection;
use ZnCore\Query\Entities\Query;
use ZnDomain\Service\Base\BaseCrudService;
use ZnLib\Components\Store\StoreFile;
use ZnTool\Dev\Composer\Domain\Interfaces\Repositories\ConfigRepositoryInterface;
use ZnTool\Dev\Composer\Domain\Interfaces\Services\ConfigServiceInterface;
use ZnTool\Package\Domain\Entities\ConfigEntity;
use ZnTool\Package\Domain\Entities\PackageEntity;
use ZnTool\Package\Domain\Interfaces\Repositories\PackageRepositoryInterface;

class ConfigService extends BaseCrudService implements ConfigServiceInterface
{

    private $packageRepository;

    public function __construct(ConfigRepositoryInterface $repository, PackageRepositoryInterface $packageRepository)
    {
        $this->setRepository($repository);
        $this->packageRepository = $packageRepository;
    }

    public function findAll(Query $query = null): Enumerable
    {
        /** @var \ZnCore\Collection\Interfaces\Enumerable | PackageEntity[] $packageCollection */
        $packageCollection = $this->packageRepository->findAll();
        $configCollection = new Collection();
        foreach ($packageCollection as $packageEntity) {
            $composerConfigFile = $packageEntity->getDirectory() . '/composer.json';
            $composerConfigStore = new StoreFile($composerConfigFile);
            $composerConfig = $composerConfigStore->load();
            $confiEntity = new ConfigEntity;
            $confiEntity->setId($packageEntity->getId());
            $confiEntity->setConfig($composerConfig);
            $confiEntity->setPackage($packageEntity);
            //EntityHelper::setAttributes($confiEntity, ComposerConfigMapper::arrayToEntity($composerConfig));
            $configCollection->add($confiEntity);
        }
        return $configCollection;
    }

    public function allWithThirdParty(Query $query = null)
    {
        /** @var \ZnCore\Collection\Interfaces\Enumerable | PackageEntity[] $packageCollection */
        $packageCollection = $this->packageRepository->allWithThirdParty();
        //dd($packageCollection);
        $configCollection = new Collection();
        foreach ($packageCollection as $packageEntity) {
            $composerConfigFile = $packageEntity->getDirectory() . '/composer.json';
            $composerConfigStore = new StoreFile($composerConfigFile);
            $composerConfig = $composerConfigStore->load();
            $confiEntity = new ConfigEntity;
            $confiEntity->setId($packageEntity->getId());
            $confiEntity->setConfig($composerConfig);
            $confiEntity->setPackage($packageEntity);
            //EntityHelper::setAttributes($confiEntity, ComposerConfigMapper::arrayToEntity($composerConfig));
            $configCollection->add($confiEntity);
        }
        return $configCollection;
    }

}
