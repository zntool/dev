<?php

namespace ZnTool\Dev\Package\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;
use ZnCore\Base\Enums\Http\HttpMethodEnum;
use ZnCore\Base\Helpers\LoadHelper;
use ZnCore\Base\Legacy\Yii\Helpers\ArrayHelper;
use ZnCore\Domain\Helpers\EntityHelper;
use ZnTool\Dev\Package\Domain\Entities\ChangedEntity;
use ZnTool\Dev\Package\Domain\Entities\GroupEntity;
use ZnTool\Dev\Package\Domain\Entities\PackageEntity;
use ZnTool\Dev\Package\Domain\Enums\StatusEnum;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GithubOrgsCommand extends BaseCommand
{

    protected static $defaultName = 'package:github:orgs';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<fg=white># github orgs</>');

        $url = 'https://api.github.com/user/orgs?access_token=' . $_ENV['GITHUB_TOKEN'];
        $collection = $this->sendRequest(HttpMethodEnum::GET, $url);
        $orgs = ArrayHelper::getColumn($collection, 'login');
        $repoCollection = [];
        foreach ($orgs as $orgName) {
            $url = "https://api.github.com/orgs/{$orgName}/repos";
            $repos = $this->sendRequest(HttpMethodEnum::GET, $url);
            $reposList = ArrayHelper::getColumn($repos, 'name');
            /*$groupEntity = new GroupEntity();
            $groupEntity->name = */
            $orgArr = [
                'name' => $orgName
            ];
            foreach ($reposList as $repoName) {
                $repoArr = [
                    'name' => $repoName,
                    'org' => $orgArr,
                ];
            }
            $repoCollection[] = $repoArr;
            break;
        }
        $fileName = 'vendor/zntool/dev/src/Package/Domain/Data/package_origin.php';
        LoadHelper::saveConfig($fileName, $repoCollection);
        dd($repoCollection);

        $output->writeln('');
        return 0;
    }

    public function sendRequest(string $method, string $url, array $options = []): array {
        $client = new Client();
        try {
            $response = $client->request($method, $url, $options);
        } catch (ClientException $e) {
            $response = $e->getResponse();
        }
        return json_decode($response->getBody()->getContents());
//        return $response;
    }
}
