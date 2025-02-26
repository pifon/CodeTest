<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Game;

class GameRepository
{
    public function getById(int $id): Game
    {
        return Game::findOrFail($id);
    }
}
