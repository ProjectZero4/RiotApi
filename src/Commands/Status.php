<?php


namespace ProjectZero4\RiotApi\Commands;


use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class Status extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'riotApi:fresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate all the riot api databases';

    protected array $tables = [
        'champion_masteries',
        'champions',
        'games',
        'leagues',
        'maps',
        'participants',
        'queues',
        'rune_pages',
        'summoners',
        'teams',
    ];

    public function handle()
    {
        $stmts = [];
        foreach ($this->tables as $table) {
            $stmts[] = "truncate $table";
        }
        echo implode(';', $stmts);
        DB::select(implode(';', $stmts));
    }
}

