<?php

namespace PhpLab\Dev\Package\Commands;

use Illuminate\Support\Collection;
use PhpLab\Core\Domain\Helpers\EntityHelper;
use PhpLab\Dev\Package\Domain\Entities\ChangedEntity;
use PhpLab\Dev\Package\Domain\Entities\PackageEntity;
use PhpLab\Dev\Package\Domain\Enums\StatusEnum;
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
            if( ! $isGit) {
                $output->writeln("<fg=magenta>Not found git repository</>");
                $changedEntity = new ChangedEntity;
                $changedEntity->setPackage($packageEntity);
                $changedEntity->setStatus(StatusEnum::NOT_FOUND_REPO);
                $totalCollection->add($changedEntity);
            } elseif ($isHasChanges) {
                $output->writeln("<fg=yellow>Has changes</>");
                $changedEntity = new ChangedEntity;
                $changedEntity->setPackage($packageEntity);
                $changedEntity->setStatus(StatusEnum::CHANGED);
                $totalCollection->add($changedEntity);
            } else {
                $output->writeln("<fg=green>OK</>");
            }
        }
        return $totalCollection;
    }

    private function displayTotal(Collection $totalCollection, InputInterface $input, OutputInterface $output)
    {
        /** @var ChangedEntity[] | Collection $totalCollection */
        $output->writeln('<fg=yellow>Has changes:</>');
        $output->writeln('');
        foreach ($totalCollection as $changedEntity) {
            $packageEntity = $changedEntity->getPackage();
            $packageId = $packageEntity->getId();
            if($changedEntity->getStatus() == StatusEnum::CHANGED) {
                $vendorDir = __DIR__ . '/../../../../../';
                $dir = realpath($vendorDir) . '/' . $packageId;
                $fastCommand = "cd $dir && git add . && git commit -m upd && git push";
                $output->writeln("<fg=yellow> {$packageId}</> ($fastCommand)");
            } elseif ($changedEntity->getStatus() == StatusEnum::NOT_FOUND_REPO) {
                $output->writeln("<fg=magenta> {$packageId}</> (not found repo)");
            }
        }
    }
}
