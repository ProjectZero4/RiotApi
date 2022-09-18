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
    protected $signature = 'riot-api:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks that everything is setup and ready to use';

    public function handle()
    {
    }
}

