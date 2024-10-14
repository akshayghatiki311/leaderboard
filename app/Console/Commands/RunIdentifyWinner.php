<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\IdentifyWinner;

class RunIdentifyWinner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'winner:identify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch IdentifyWinner to determine the winner';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        IdentifyWinner::dispatch();
        $this->info('IdentifyWinner dispatched.');
        return 0;
    }
}
