<?php

namespace ProjectZero4\RiotApi\Commands;

use Illuminate\Console\Command;
use ProjectZero4\RiotApi\Models\Game\Map;
use ProjectZero4\RiotApi\RiotApi;

class Maps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'riot-api:maps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will get the most to date Map info from Riot Games and update the database.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param RiotApi $api
     * @return int
     */
    public function handle(RiotApi $api): int
    {
        foreach ($api->getMaps() as $map) {
            Map::firstOrCreate([
                'id' => $map['mapId'],
                'name' => $map['mapName'],
                'notes' => $map['notes'],
            ]);
        }
        return 0;
    }
}
