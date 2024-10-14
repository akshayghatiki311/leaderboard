<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Winner;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class IdentifyWinner implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $max_points = User::max('points');
        $top_scorers = User::where('points', $max_points)->get();
        if ($top_scorers->count() > 1) {
            \Log::info('There is a tie for highest points.');
            return;
        }
        if ($top_scorers->count() == 1) {
            $winner = $top_scorers->first();

            Winner::create([
                'user_id' => $winner->id,
                'points' => $winner->points,
                'won_at' => now(),
            ]);
        }
    }
}
