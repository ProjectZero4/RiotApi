<?php

namespace ProjectZero4\RiotApi\Jobs;

use GuzzleHttp\Exception\GuzzleException;
use ProjectZero4\RiotApi\Exceptions\RateLimitException;
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
        if ($this->batch() && $this->batch()->canceled()) {
            return;
        }
        $offset = 0;
        $count = 100;
        $start = Carbon::now()->subYears(3)->timestamp;
        $end = Carbon::now()->timestamp;
        try {
            while ($matchIds = $api->listGamesBySummoner($this->summoner, [
                'startTime' => $start,
                'endTime' => $end,
                'count' => $count,
                'start' => $offset,
            ])) {
                $jobs = [];
                foreach ($matchIds as $matchId) {
                    $jobs[] = new StoreGame($matchId);
                }

                $offset += $count;
                $this->batch()->add($jobs);
            }
        } catch (RateLimitException $e) {
            $this->release($e->waitTime + 5);
        } catch (GuzzleException $e) {
            $this->release(60);
        }

    }
}
