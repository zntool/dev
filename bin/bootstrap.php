<?php


use Illuminate\Container\Container;
use Symfony\Component\Console\Application;
use ZnCore\Base\Console\Helpers\CommandHelper;

/**
 * @var Application $application
 * @var Container $container
 */

$container = Container::getInstance();

// --- Application ---

$container->bind(Application::class, Application::class, true);

// --- Generator ---

$container->bind(\ZnTool\Dev\Generator\Domain\Interfaces\Services\DomainServiceInterface::class, \ZnTool\Dev\Generator\Domain\Services\DomainService::class);
$container->bind(\ZnTool\Dev\Generator\Domain\Interfaces\Services\ModuleServiceInterface::class, \ZnTool\Dev\Generator\Domain\Services\ModuleService::class);

// --- Composer ---


$container->bind(\ZnTool\Dev\Composer\Domain\Interfaces\Repositories\ConfigRepositoryInterface::class, \ZnTool\Dev\Composer\Domain\Repositories\File\ConfigRepository::class);
$container->bind(\ZnTool\Dev\Composer\Domain\Interfaces\Services\ConfigServiceInterface::class, \ZnTool\Dev\Composer\Domain\Services\ConfigService::class);

// --- Package ---

$container->bind(\ZnTool\Dev\Package\Domain\Interfaces\Services\GitServiceInterface::class, \ZnTool\Dev\Package\Domain\Services\GitService::class);
$container->bind(\ZnTool\Dev\Package\Domain\Interfaces\Services\PackageServiceInterface::class, \ZnTool\Dev\Package\Domain\Services\PackageService::class);
$container->bind(\ZnTool\Dev\Package\Domain\Repositories\File\GroupRepository::class, function () {
    $fileName = ! empty($_ENV['PACKAGE_GROUP_CONFIG']) ? __DIR__ . '/../../../../' . $_ENV['PACKAGE_GROUP_CONFIG'] : __DIR__ . '/../src/Package/Domain/Data/package_group.php';
    $repo = new \ZnTool\Dev\Package\Domain\Repositories\File\GroupRepository($fileName);
    return $repo;
});
$container->bind(\ZnTool\Dev\Package\Domain\Interfaces\Repositories\PackageRepositoryInterface::class, \ZnTool\Dev\Package\Domain\Repositories\File\PackageRepository::class);
$container->bind(\ZnTool\Dev\Package\Domain\Interfaces\Repositories\GitRepositoryInterface::class, \ZnTool\Dev\Package\Domain\Repositories\File\GitRepository::class);

CommandHelper::registerFromNamespaceList([
    'ZnTool\Dev\Generator\Commands',
    'ZnTool\Dev\Stress\Commands',
    'ZnTool\Dev\Package\Commands',
    'ZnTool\Dev\Composer\Commands',
    'ZnTool\Dev\Phar\Commands',
], $container);
