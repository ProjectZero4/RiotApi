<?php


namespace ProjectZero4\RiotApi\Commands;


use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use ProjectZero4\RiotApi\Jobs\StoreSummonerGames;
use Illuminate\Console\Command;
use ProjectZero4\RiotApi\RiotApi;

class Games extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'riotApi:games {--summonerName=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Queues up the jobs required to get the games for summoners';
    /**
     * @param RiotApi $api
     */
    public function handle(RiotApi $api)
    {
        $summonerName = $this->option('summonerName');
        $summoner = $api->summonerBySummonerName($summonerName);
        Bus::batch([new StoreSummonerGames($summoner)])
            ->name("Importing Games for Summoner: $summoner->name")
            ->onQueue('riot-api-match')
            ->dispatch();
    }
}
