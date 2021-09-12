<?php

namespace ProjectZero4\RiotApi\Jobs;

use Carbon\Carbon;
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

class StoreSummonerGamesByWeek implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    protected Models\Summoner $summoner;
    protected string $start;
    protected string $end;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Models\Summoner $summoner, string $start, string $end)
    {
        $this->summoner = $summoner;
        $this->start = $start;
        $this->end = $end;
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
        foreach ($api->listGamesBySummoner($this->summoner, [
            'startTime' => Carbon::parse($this->start)->timestamp,
            'endTime' => Carbon::parse($this->end)->timestamp,
            'count' => 100,
        ]) as $gameId) {
            $jobs[] = new StoreGame($gameId);
        }

        $this->batch()->add($jobs);
    }
}
