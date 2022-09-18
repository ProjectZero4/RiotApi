<?php


namespace ProjectZero4\RiotApi\Commands;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Console\Command;
use ProjectZero4\RiotApi\Models;

class Fresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'riot-api:fresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate all the riot api databases';

    protected array $models = [
        Models\ChampionMastery::class,
        Models\Champion::class,
        Models\Game\Game::class,
        Models\League::class,
        Models\Game\Map::class,
        Models\Game\Participant::class,
        Models\Game\Queue::class,
        Models\Game\RunePage::class,
        Models\Summoner::class,
        Models\Game\Team::class,
    ];

    public function handle()
    {
        foreach ($this->models as $model) {
            /** @var Model $model */
            $model::query()->truncate();
        }

        $this->call(Setup::class);
    }
}

