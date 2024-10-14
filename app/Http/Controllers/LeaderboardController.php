<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class LeaderboardController extends Controller
{
    
    public function index()
    {
        $users = User::orderBy('points', 'DESC')->get();
        return response()->json($users);
    }

    public function increment(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $user->increment('points');
        return response()->json($user);
    }

    public function decrement(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        if ($user->points <= 0) {
            return response()->json(['error' => 'User cannot have negative points'], 400);
        }
        $user->decrement('points');
        return response()->json($user);
    }
}
