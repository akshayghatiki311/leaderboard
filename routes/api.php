<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\UserController;

Route::get('/leaderboard', [LeaderboardController::class, 'index']);  // Fetch leaderboard
Route::post('/users', [UserController::class, 'addUser']);  // Add a new user
Route::delete('/users/{id}', [UserController::class, 'deleteUser']);  // Delete a user
Route::get('/users/{id}', [UserController::class, 'getUser']);  // Get a user
Route::get('/groupusers', [UserController::class, 'getAverageAgeByPoints']);  // Get group users
Route::post('/users/{id}/increment', [LeaderboardController::class, 'increment']);  // Increment points
Route::post('/users/{id}/decrement', [LeaderboardController::class, 'decrement']);  // Decrement points
