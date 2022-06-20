<?php

namespace ZnTool\Dev\Composer\Domain\Services;

use Illuminate\Support\Collection;
use ZnCore\Base\Libs\Service\Base\BaseCrudService;
use ZnCore\Base\Libs\Query\Entities\Query;
use ZnCore\Base\Libs\Store\StoreFile;
use ZnTool\Package\Domain\Entities\ConfigEntity;
use ZnTool\Package\Domain\Entities\PackageEntity;
use ZnTool\Dev\Composer\Domain\Interfaces\Repositories\ConfigRepositoryInterface;
use ZnTool\Package\Domain\Interfaces\Repositories\PackageRepositoryInterface;
use ZnTool\Dev\Composer\Domain\Interfaces\Services\ConfigServiceInterface;

class ConfigService extends BaseCrudService implements ConfigServiceInterface
{

    private $packageRepository;

    public function __construct(ConfigRepositoryInterface $repository, PackageRepositoryInterface $packageRepository)
    {
        $this->setRepository($repository);
        $this->packageRepository = $packageRepository;
    }

    public function all(Query $query = null)
    {
        /** @var Collection | PackageEntity[] $packageCollection */
        $packageCollection = $this->packageRepository->all();
        $configCollection = new Collection;
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
        /** @var Collection | PackageEntity[] $packageCollection */
        $packageCollection = $this->packageRepository->allWithThirdParty();
        //dd($packageCollection);
        $configCollection = new Collection;
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
