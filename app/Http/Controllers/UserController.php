<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Jobs\GenerateAddressQrCode;

class UserController extends Controller
{
    
    public function addUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:1',
            'address' => 'required|string|max:255',
        ]);

        $user = User::create([
        'name' => $request->name,
        'age' => $request->age,
        'address' => $request->address,
        'points' => 0,  
        ]);

        GenerateAddressQrCode::dispatch($user);

        return response()->json($user, 201);
    }

    

    public function deleteUser($id)
    {
        User::destroy($id);
        return response()->json(['message' => 'User deleted']);
    }

    
    public function getUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        
        return response()->json($user);
    }

    
    public function getAverageAgeByPoints()
    {
        $users = User::select('points', 'name', 'age')
                ->get()
                ->groupBy('points');

        $grouped_data = $users->map(function ($group) {
            $names = $group->pluck('name')->toArray();
            $average_age = $group->avg('age');
            
            return [
                'names' => $names,
                'average_age' => round($average_age, 2),
            ];
        });

        return response()->json($grouped_data);
    }
}
