<?php

namespace ZnTool\Dev\Admin\Facades;

use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Base\Libs\App\Factories\ApplicationFactory;
use ZnCore\Base\Libs\App\Factories\KernelFactory;
use ZnCore\Contract\Kernel\Interfaces\KernelInterface;
use ZnLib\Web\Symfony4\MicroApp\MicroApp;
use ZnLib\Web\Symfony4\Subscribers\TokenSubscriber;
use ZnSandbox\Sandbox\Error\Symfony4\Web\Controllers\ErrorController;

class DevAppFacade
{

    public static function getBundles(): array {
        return [
            new \ZnBundle\Log\Bundle(['all']),
            new \ZnLib\Web\Bundle(['all']),
            new \ZnLib\Db\Bundle(['all']),
            new \ZnBundle\Dashboard\Bundle(['all']),
            new \ZnBundle\Notify\Bundle(['all']),
            new \ZnBundle\Language\BundleNew(['all']),
            new \ZnBundle\Summary\Bundle(['all']),
            new \ZnBundle\User\NewBundle(['all']),
            new \ZnSandbox\Sandbox\Layout\Bundle(['all']),
            new \ZnSandbox\Sandbox\Casbin\Bundle(['all']),
            new \ZnSandbox\Sandbox\Symfony\Bundle(['all']),
            new \ZnSandbox\Sandbox\UserNotify\Bundle(['all']),

//            new \ZnLib\Rpc\Bundle(['all']),
//            new \ZnSandbox\Sandbox\UserSecurity\Bundle(['all']),
//            new \ZnSandbox\Sandbox\Application\Bundle(['all']),
//            new \ZnCrypt\Jwt\Bundle(['all']),
//            new \ZnBundle\Reference\Bundle(['all']),
//            new \App\Person\Bundle(['all']),
//            new \ZnSandbox\Sandbox\Settings\Bundle(['all']),
//            new \ZnBundle\Queue\Bundle(['all', 'console']),
//            new \ZnBundle\Person\Bundle(['all']),
//            new \ZnBundle\Eav\Bundle(['all']),
//            new \App\Common\Bundle(['all']),
//            new \ZnSandbox\Sandbox\Rpc\Bundle(['all']),
        ];
    }

    public static function runApp(array $appBundles = []) {
        $bundles = self::getBundles();
        $bundles = ArrayHelper::merge($bundles, $appBundles);
        $kernel = KernelFactory::createWebKernel($bundles, ['i18next', 'container', 'symfonyAdmin']);
        $application = self::createApp($kernel);
        $response = $application->run();
        $response->send();
    }

    public static function createApp(KernelInterface $kernel): MicroApp {
        $application = ApplicationFactory::createWeb($kernel);
        $application->setLayout(__DIR__ . '/../../../../../../vendor/znsymfony/admin-panel/src/Symfony4/Admin/views/layouts/admin/main.php');
        $application->addLayoutParam('menuConfigFile', __DIR__ . '/../../../../../../vendor/znsymfony/admin-panel/src/Symfony4/Admin/config/admin_sidebar.php');
        $application->setErrorController(ErrorController::class);
        $application->addSubscriber(TokenSubscriber::class);
        return $application;
    }
}
