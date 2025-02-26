<?php

declare(strict_types=1);

namespace Tests\Feature\Game;

/*
 *  PROD and DEV Envs are not separated
 *  Seeding DB will fail - feature commented out
 *  tests assumes persistent data from seeding
 */
// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Database\Seeders\GamesTableSeeder;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class DetailsTest extends TestCase
{
    // use RefreshDatabase;

    /**
     * Data provider for game ID scenarios
     */
    public static function gameIdProvider(): array
    {
        return [
            'valid ID' => [1, 200, ''],  // Test case with ID 1 expecting a 200 status
            'non-existent ID' => [999, 404, 'Game not found'], // Test case with ID 999 expecting 404
            'invalid ID' => ['invalid', 400, 'Invalid game id'], // Test case with non-integer ID expecting 400
        ];
    }

    /**
     * Test game fetching scenarios with data provider
     */
    #[DataProvider('gameIdProvider')]
    public function test_view_game_details($gameID, $expectedStatus, $expectedMessage): void
    {
        $response = $this->getJson("/api/game/{$gameID}");
        $response->assertStatus($expectedStatus);
        $this->assertEquals($expectedMessage, $expectedStatus != 200 ? $response->json()['message'] : '');
    }

    /**
     * Test a success fetch of existing game
     */
    public function test_success_view_game_details(): void
    {
        // $this->seed(GamesTableSeeder::class);
        $gameID = 1;
        $response = $this->getJson('/api/game/' . $gameID);

        $response->assertStatus(200);
        $game = $response->json();
        $this->assertCount(6,  $game);
        $this->assertEquals(['id', 'x', 'o', 'board', 'winner', 'revenge'], array_keys($game));
        $this->assertSame($gameID, $game['id']);
    }
}
