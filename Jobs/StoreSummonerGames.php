<?php

namespace ProjectZero4\RiotApi\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use ProjectZero4\RiotApi\Models;
use ProjectZero4\RiotApi\RiotApi;
use ProjectZero4\RiotApi\RiotApiCollection;

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
        foreach ($api->splitSeasonByWeeks($this->season) as $week) {
            $jobs[] = new StoreSummonerGamesByWeek($this->summoner, $week['start'], $week['end']);
        }
        $this->batch()->add($jobs);
    }
}
