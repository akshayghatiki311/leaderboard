<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ResetScores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-scores';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset all scores to 0';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::query()->update(['points' => 0]);
        $this->info('All scores are reset to 0');
        return 0;
    }
}
