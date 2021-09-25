<?php

namespace ProjectZero4\RiotApi\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ProjectZero4\RiotApi\Models;
use ProjectZero4\RiotApi\RiotApi;

class StoreSummonerGames implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    protected Models\Summoner $summoner;
    protected ?string $season;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Models\Summoner $summoner, ?string $season = null)
    {
        $this->summoner = $summoner;
        $this->season = $season;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(RiotApi $api)
    {
        //
        if ($this->batch()->canceled()) {
            return;
        }
        $jobs = [];
        $seasonTimes = $api->getSeasonTimes($this->season);
        $offset = 0;
        $count = 100;
        while ($gameIds = $api->listGamesBySummoner($this->summoner, [
            'startTime' => Carbon::parse($seasonTimes['start'])->timestamp,
            'endTime' => Carbon::parse($seasonTimes['end'])->timestamp,
            'count' => $count,
            'start' => $offset,
        ])) {
            foreach ($gameIds as $gameId) {
                $jobs[] = new StoreGame($gameId);
            }

            $offset += $count;
        }
        $this->batch()->add($jobs);
    }
}
