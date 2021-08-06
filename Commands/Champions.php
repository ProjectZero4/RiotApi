<?php


namespace App\packages\ProjectZero4\RiotApi\Commands;


use App\packages\ProjectZero4\RiotApi\Models\Champion;
use Illuminate\Console\Command;
use ProjectZero4\RiotApi\RiotApi;

class Champions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'riotApi:champions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates champion database with latest champions';

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
     * @param RiotApi $api
     */
    public function handle(RiotApi $api)
    {
        $new = 0;
        $updated = 0;

        $champions = $api->getChampions()['data'];

        $bar = $this->output->createProgressBar(count($champions));

        $bar->start();

        foreach ($champions as $champion) {
            $champion = $api->getChampion($champion['id']);
            $championModel = Champion::firstOrNew(['id' => $champion['id']]);
            $championModel->fill($champion);
            $championModel->save();
            if(!$championModel->exists) {
                $new++;
            } else {
                $updated++;
            }
            $bar->advance();
        }

        $bar->finish();

        $this->info("{$new} champion(s) have been added and {$updated} champion(s) have been updated!");
    }
}
