<?php

namespace ProjectZero4\RiotApi\Commands;

use Illuminate\Console\Command;
use ProjectZero4\RiotApi\Models\Game\Map;
use ProjectZero4\RiotApi\Models\Game\Queue;
use ProjectZero4\RiotApi\RiotApi;

class Queues extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'riotApi:queues';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets the latest queues information from riot games and updates the database.';

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
     * @return int
     */
    public function handle(RiotApi $api)
    {
        foreach ($api->getQueues() as $queue) {
            if (!$map = Map::whereName($queue['map'])->first()) {
                continue;
            }
            Queue::firstOrCreate([
                'id' => $queue['queueId'],
                'map_id' => $map->id,
                'description' => $queue['description'],
                'notes' => $queue['notes'] ?? "",
            ]);
        }
        return 0;
    }
}
