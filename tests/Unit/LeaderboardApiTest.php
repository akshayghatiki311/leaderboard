<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LeaderboardApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_user_via_api()
    {

        $user_data = [
            'name' => 'John Doe',
            'age' => 30,
            'address' => '123 Main St',
        ];

        $response = $this->postJson('/api/users', $user_data);
        $response->assertStatus(201);
        $response->assertJson([
            'name' => 'John Doe',
            'age' => 30,
            'address' => '123 Main St',
            'points' => 0,
        ]);
        $this->assertDatabaseHas('users', $user_data);
    }

    /** @test */
    public function it_fetches_the_leaderboard_via_api()
    {
        User::factory()->create(['name' => 'Alice', 'points' => 30]);
        User::factory()->create(['name' => 'Bob', 'points' => 50]);

        $response = $this->getJson('/api/leaderboard');
        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Bob', 'points' => 50]);
        $response->assertJsonFragment(['name' => 'Alice', 'points' => 30]);
    }

    /** @test */
    public function it_increments_user_points_via_api()
    {
        $user = User::factory()->create(['name' => 'Charlie', 'points' => 10]);
        $response = $this->postJson("/api/users/{$user->id}/increment");
        $response->assertStatus(200);
        $response->assertJson(['points' => 11]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'points' => 11,
        ]);
    }

    /** @test */
    public function it_decrements_user_points_via_api()
    {

        $user = User::factory()->create(['name' => 'David', 'points' => 20]);

        $response = $this->postJson("/api/users/{$user->id}/decrement");

        $response->assertStatus(200);

        $response->assertJson(['points' => 19]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'points' => 19,
        ]);
    }

    /** @test */
    public function it_deletes_a_user_via_api()
    {

        $user = User::factory()->create(['name' => 'Eve', 'points' => 15]);

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    /** @test */
    public function it_groups_users_by_score_with_average_age_via_api()
    {
        User::factory()->create(['points' => 25, 'age' => 18, 'name' => 'Emma']);
        User::factory()->create(['points' => 25, 'age' => 20, 'name' => 'John']);
        User::factory()->create(['points' => 30, 'age' => 22, 'name' => 'Alice']);

        $response = $this->getJson('/api/groupusers');

        $response->assertStatus(200);

        $response->assertJson([
            '25' => [
                'names' => ['Emma', 'John'],
                'average_age' => 19.00,
            ],
            '30' => [
                'names' => ['Alice'],
                'average_age' => 22.00,
            ],
        ]);
    }
}
