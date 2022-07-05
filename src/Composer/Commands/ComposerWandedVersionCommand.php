<?php

namespace ZnTool\Dev\Composer\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use yii\helpers\ArrayHelper;
use ZnCore\Domain\Collection\Interfaces\Enumerable;
use ZnTool\Dev\Composer\Domain\Interfaces\Services\ConfigServiceInterface;
use ZnTool\Package\Domain\Entities\ConfigEntity;
use ZnTool\Package\Domain\Helpers\ComposerConfigHelper;
use ZnTool\Package\Domain\Interfaces\Services\GitServiceInterface;
use ZnTool\Package\Domain\Libs\Depend;

class ComposerWandedVersionCommand extends Command
{

    protected static $defaultName = 'composer:config:wanted-version';

    protected $configService;
    protected $gitService;

    public function __construct(?string $name = null, ConfigServiceInterface $configService, GitServiceInterface $gitService)
    {
        parent::__construct($name);
        $this->configService = $configService;
        $this->gitService = $gitService;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<fg=white># Composer wanted version</>');
        $output->writeln('');
        /** @var ConfigEntity[] | Enumerable $collection */
        $collection = $this->configService->findAll();
        /** @var ConfigEntity[] | Enumerable $collection */
        $thirdPartyCollection = $this->configService->allWithThirdParty();
        $namespacesPackages = ComposerConfigHelper::extractPsr4AutoloadPackages($thirdPartyCollection);

        $output->writeln('<fg=white>Get packages version...</>');
        $output->writeln('');
        $lastVersions = $this->gitService->lastVersionCollection();

        if ($collection->count() == 0) {
            $output->writeln('<fg=magenta>Not found packages!</>');
            $output->writeln('');
            return 0;
        }

        $output->writeln('<fg=white>Get packages info...</>');
        $output->writeln('');

        $progressBar = new ProgressBar($output);
        $progressBar->setMaxSteps($collection->count());
        $progressBar->start();
        $depend = new Depend($namespacesPackages, $lastVersions);
        $deps = $depend->allDepends($collection, function () use ($progressBar) {
            $progressBar->advance();
        });
        $progressBar->finish();

        $output->writeln('');
        $output->writeln('');

        foreach ($deps as $depId => $dep) {
            $output->writeln('<fg=magenta># ' . $depId . '</>');
            $output->writeln('');
            $output->writeln(Yaml::dump($dep, 10));
        }
        return 0;
    }

}
