<?php


namespace ProjectZero4\RiotApi\Commands;


use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use ProjectZero4\RiotApi\Models;
use ProjectZero4\RiotApi\RiotApi;

use Symfony\Component\Process\Process;
use function ProjectZero4\RiotApi\riotApiRoot;
use function ProjectZero4\RiotApi\riotApi;

class Setup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'riot-api:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pre seeds the tables with the latest data from Riot Games';


    protected array $commands = [
        Champions::class,
        Maps::class,
        Queues::class,
    ];

    public function handle()
    {
        foreach ($this->commands as $command) {
//            $this->call($command);
        }

        $this->downloadDataDragon();
    }

    protected function downloadDataDragon()
    {
        $patch = riotApi()->getCurrentPatch();
//        $url = "https://ddragon.leagueoflegends.com/cdn/dragontail-$patch.tgz";
//        $downloadedFile = "/tmp/riot-api/$patch.tgz";
//        $this->downloadFile($url, $downloadedFile);
//        $outputDir = public_path(riotApiRoot());
//        @mkdir($outputDir, 0777, true);
//        $this->output->info("Extracting $downloadedFile to $outputDir");
//        $tar = new Process(['tar', '-xvf', $downloadedFile, '-C', $outputDir]);
//        $tar->run(function ($type, $buffer) {
//            echo $buffer;
//        });
        $this->downloadFile("https://static.developer.riotgames.com/docs/lol/ranked-emblems.zip", "/tmp/riot-api/ranked-emblems.zip");
        $this->downloadFile("https://static.developer.riotgames.com/docs/lol/ranked-positions.zip", "/tmp/riot-api/ranked-positions.zip");
    }

    protected function downloadFile(string $src, string $dest)
    {
        $this->output->info("Downloading $src");
        @mkdir(dirname($dest), 0777, true);
        $progressBar = $this->output->createProgressBar(1);
        $progressBar->setMaxSteps(1);
        $progressBar->setFormat(" %current%/%max% MB [%bar%] %percent:3s%% (%elapsed:6s%/%estimated:-6s%)");
        $client = new Client();
        $unset = true;
        $client->get($src, [
            'sink' => $dest,
            'progress' => function ($downloadTotal, $downloadedBytes) use ($progressBar, &$unset) {
                if ($downloadTotal === 0) {
                    return;
                }

                $this->output->info($this->bytesToMb($downloadTotal));

                if ($unset) {
                    $progressBar->setMaxSteps(ceil($this->bytesToMb($downloadTotal)));
                    $unset = false;
                }

                $progressBar->setProgress($this->bytesToMb($downloadedBytes));
            },
        ]);
        $this->output->info("Before finish");
        $progressBar->finish();
        $this->output->info("after finish");
    }

    protected function bytesToMb(int $bytes): float|int
    {
        return $bytes / 1000000;
    }
}

