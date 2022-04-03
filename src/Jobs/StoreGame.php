<?php

namespace ProjectZero4\RiotApi\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use ProjectZero4\RiotApi\Exceptions\RateLimitException;
use ProjectZero4\RiotApi\Models;
use ProjectZero4\RiotApi\RiotApi;
use ProjectZero4\RiotApi\RiotApiCollection;

class StoreGame implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    protected string $matchId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $matchId)
    {
        $this->matchId = $matchId;
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

        if (Models\Game\Game::where('matchId', $this->matchId)->first()) {
            return;
        }

        DB::transaction(function () use ($api) {
            try {
                $data = $api->rawGameById($this->matchId);
            } catch (RateLimitException $e) {
                $this->release($e->waitTime + 5);
                return;
            }
            $info = $data['info'];
            $gameData = array_merge($info, [
                'matchId' => $data['metadata']['matchId'],
            ]);
            $game = new Models\Game\Game($gameData);
            $game->save();
            $participantTeams = collect($info['participants'])->groupBy('teamId');
            $participants = new RiotApiCollection();
            foreach ($info['teams'] as $team) {
                $teamModel = new Models\Game\Team($team);
                $game->teams()->save($teamModel);
                foreach ($participantTeams->get($team['teamId']) as $participant) {
                    $participantModel = new Models\Game\Participant($participant);
                    $participantModel->game_id = $teamModel->game_id;
                    $teamModel->participants()->save($participantModel);
                    $runePage = new Models\Game\RunePage($participant['perks']);
                    $participantModel->runePage()->save($runePage);
                    $participants->add($participantModel);
                }
            }

            $game->participants()->saveMany($participants);
        });
    }
}
