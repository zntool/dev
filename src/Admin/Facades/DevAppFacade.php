<?php

namespace ZnTool\Dev\Admin\Facades;

use Symfony\Component\Routing\RouteCollection;
use ZnCore\Base\Helpers\DeprecateHelper;
use ZnCore\Base\Helpers\InstanceHelper;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Base\Libs\App\Factories\ApplicationFactory;
use ZnCore\Base\Libs\App\Factories\KernelFactory;
use ZnCore\Base\Libs\App\Helpers\EnvHelper;
use ZnCore\Base\Libs\App\Kernel;
use ZnCore\Base\Libs\App\Loaders\BundleLoader;
use ZnCore\Base\Libs\DotEnv\DotEnv;
use ZnCore\Contract\Kernel\Interfaces\KernelInterface;
use ZnLib\Web\Symfony4\MicroApp\MicroApp;
use ZnLib\Web\Symfony4\Subscribers\TokenSubscriber;
use ZnSandbox\Sandbox\Error\Symfony4\Web\Controllers\ErrorController;

DeprecateHelper::hardThrow();

class DevAppFacade
{

    public static function getBundles(): array {
        return [
            new \ZnBundle\Log\Bundle(['all']),
            new \ZnLib\Web\Bundle(['all']),
//            new \ZnLib\Db\Bundle(['all']),

            new \ZnDatabase\Base\Bundle(['all']),
            new \ZnDatabase\Tool\Bundle(['all']),
            
            new \ZnBundle\Dashboard\Bundle(['all']),
            new \ZnBundle\Notify\Bundle(['all']),
            new \ZnBundle\Language\Bundle(['all']),
            new \ZnBundle\Summary\Bundle(['all']),
            new \ZnUser\Identity\Bundle(['all']),
            new \ZnSandbox\Sandbox\Layout\Bundle(['all']),
            new \ZnUser\Rbac\Bundle(['all']),


            new \ZnCore\Base\Bundle(['all']),
            new \ZnCore\Base\Libs\I18Next\Bundle(['all']),
            new \ZnCore\Base\Libs\App\Bundle(['all']),
            new \ZnSandbox\Sandbox\Symfony\NewBundle(['all']),
//            new \ZnSandbox\Sandbox\Symfony\Bundle(['all']),

            new \ZnUser\Notify\Bundle(['all']),

//            new \ZnLib\Rpc\Bundle(['all']),
//            new \ZnUser\Password\Bundle(['all']),
//            new \ZnSandbox\Sandbox\Application\Bundle(['all']),
//            new \ZnCrypt\Jwt\Bundle(['all']),
//            new \ZnBundle\Reference\Bundle(['all']),
//            new \ZnSandbox\Sandbox\Person\Bundle(['all']),
//            new \ZnSandbox\Sandbox\Settings\Bundle(['all']),
//            new \ZnBundle\Queue\Bundle(['all', 'console']),
//            new \ZnBundle\Person\Bundle(['all']),
//            new \ZnBundle\Eav\Bundle(['all']),
//            new \App\Common\Bundle(['all']),
//            new \ZnLib\Rpc\Bundle(['all']),
        ];
    }

    public static function createAppInstance(array $appBundles = []) {

        foreach ($appBundles as &$bundleClass) {
            $bundleClass = InstanceHelper::ensure($bundleClass, [['all']]);
            /*if(class_exists($bundleClass)) {
                $bundleInstance = InstanceHelper::create($bundleClass, [['all']]);
                $bundles[] = $bundleInstance;
            }*/
        }
        
        $bundles = self::getBundles();
        $bundles = ArrayHelper::merge($bundles, $appBundles);


        self::init();
        $bundleLoader = new BundleLoader($bundles, ['i18next', 'container', 'symfonyAdmin', 'rbac', 'symfonyRpc']);
        $kernel = new Kernel('web');
        $kernel->setLoader($bundleLoader);

//        $kernel = KernelFactory::createWebKernel($bundles, ['i18next', 'container', 'symfonyAdmin', 'rbac', 'symfonyRpc']);



        $application = self::createApp($kernel);
        return $application;
    }

    protected static function init() {
//        $_ENV['ROOT_PATH'] = FileHelper::rootPath();
        EnvHelper::prepareTestEnv();
        DotEnv::init();
        //self::initVarDumper();
        //CorsHelper::autoload();
        EnvHelper::setErrorVisibleFromEnv();
    }

    public static function runApp(array $appBundles = []) {
        $application = self::createAppInstance($appBundles);
        $response = $application->run();
        $response->send();
    }

    public static function createApp(KernelInterface $kernel): MicroApp {


        $config = $kernel->loadAppConfig();
        $container = $kernel->getContainer();
//        $configManager = self::getConfigManager($container);
//        dd($configManager);
        $routes = $container->get(RouteCollection::class); //new RouteCollection();
//dd($routes);
        $application = new MicroApp($container/*, $config['routeCollection']*/);

//        $application = ApplicationFactory::createWeb($kernel);
        $application->setLayout(__DIR__ . '/../../../../../../vendor/znsymfony/admin-panel/src/Symfony4/Admin/views/layouts/admin/main.php');
        $application->addLayoutParam('menuConfigFile', __DIR__ . '/../../../../../../vendor/znsymfony/admin-panel/src/Symfony4/Admin/config/admin_sidebar.php');
        $application->setErrorController(ErrorController::class);
        $application->addSubscriber(TokenSubscriber::class);
        return $application;
    }
}
