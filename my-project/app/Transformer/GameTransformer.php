<?php

declare(strict_types=1);

namespace App\Transformer;

use App\Models\Game;

class GameTransformer
{
    public function transform(Game $game)
    {
        return [
            'id' => $game->getId(),
            'x' => $game->getXPlayer(),
            'o' => $game->getOPlayer(),
            'board' => $game->getBoard(),
            'winner' => $game->getWinner(),
            'revenge' => $game->getRevenge(),
        ];
    }
}
