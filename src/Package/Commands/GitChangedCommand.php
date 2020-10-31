<?php

namespace ZnTool\Dev\Package\Commands;

use Illuminate\Support\Collection;
use ZnCore\Domain\Helpers\EntityHelper;
use ZnTool\Dev\Package\Domain\Entities\ChangedEntity;
use ZnTool\Dev\Package\Domain\Entities\PackageEntity;
use ZnTool\Dev\Package\Domain\Enums\StatusEnum;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GitChangedCommand extends BaseCommand
{

    protected static $defaultName = 'package:git:changed';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<fg=white># Packages with changes</>');
        $collection = $this->packageService->all();
        $output->writeln('');
        if ($collection->count() == 0) {
            $output->writeln('<fg=magenta>Not found packages!</>');
            $output->writeln('');
            return 0;
        }
        $totalCollection = $this->displayProgress($collection, $input, $output);
        $output->writeln('');
        if ($totalCollection->count() == 0) {
            $output->writeln('<fg=magenta>No changes!</>');
            $output->writeln('');
            return 0;
        }
        $this->displayTotal($totalCollection, $input, $output);
        $output->writeln('');
        return 0;
    }

    private function displayProgress(Collection $collection, InputInterface $input, OutputInterface $output): Collection
    {
        /** @var PackageEntity[] | Collection $collection */
        /** @var PackageEntity[] | Collection $totalCollection */
        $totalCollection = new Collection;
        foreach ($collection as $packageEntity) {
            $packageId = $packageEntity->getId();
            $output->write(" $packageId ... ");
            $isHasChanges = $this->gitService->isHasChanges($packageEntity);
            $isGit = is_file($packageEntity->getDirectory() . '/.git/config');
            $changedEntity = new ChangedEntity;
            $changedEntity->setPackage($packageEntity);
            if( ! $isGit) {
                $output->writeln("<fg=magenta>Not found git repository</>");
                $changedEntity->setStatus(StatusEnum::NOT_FOUND_REPO);
                $totalCollection->add($changedEntity);
            } elseif ($isHasChanges) {
                $output->writeln("<fg=yellow>Has changes</>");
                $changedEntity->setStatus(StatusEnum::CHANGED);
                $totalCollection->add($changedEntity);
            } else {
                $output->writeln("<fg=green>OK</>");
                //$changedEntity->setStatus(StatusEnum::OK);
            }
        }
        return $totalCollection;
    }

    private function displayTotal(Collection $totalCollection, InputInterface $input, OutputInterface $output)
    {
        /** @var ChangedEntity[] | Collection $totalCollection */
        $output->writeln('<fg=yellow>Has changes:</>');
        $output->writeln('');

        $fastCommands = [];
        foreach ($totalCollection as $changedEntity) {
            $packageEntity = $changedEntity->getPackage();
            $packageId = $packageEntity->getId();
            $vendorDir = __DIR__ . '/../../../../../';
            $dir = realpath($vendorDir) . '/' . $packageId;
            $orgDir = realpath($vendorDir) . '/' . $packageEntity->getGroup()->name;
            if($changedEntity->getStatus() == StatusEnum::CHANGED) {
                $fastCommands[] = "cd $dir && git add . && git commit -m upd && git push";
                $output->writeln("<fg=yellow> {$packageId}</>");
            } elseif ($changedEntity->getStatus() == StatusEnum::NOT_FOUND_REPO) {
                $packageName = $packageEntity->getName();
                $gitUrl = $packageEntity->getGitUrl();
                $fastCommands[] = "cd $orgDir && mkdir -p bak && mv -f {$packageName} bak/{$packageName} && git clone $gitUrl && cp -rp bak/{$packageName}/* {$packageName}/ && rm -rf bak/*";
                $output->writeln("<fg=magenta> {$packageId}</>");
            }
        }
        
        $output->writeln('');
        $output->writeln('<fg=yellow>Fast command:</>');
        $output->writeln('');
        
        foreach ($fastCommands as $fastCommand) {
            $output->writeln($fastCommand);
        }
    }
}
