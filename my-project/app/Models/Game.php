<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public function getId(): int
    {
        return $this->id;
    }

    public function getXPlayer(): string
    {
        return $this->x;
    }

    public function getOPlayer(): ?string
    {
        return $this->o;
    }

    public function getBoard(): string
    {
        return $this->board;
    }

    public function getBoardArray(): array
    {
        $boardString = $this->getBoard();
        return json_decode($boardString, true);
    }

    public function getWinner(): ?string
    {
        return $this->winner;
    }

    public function getRevenge(): int
    {
        return $this->revenge;
    }

    public function isFinished(): bool
    {
        $wins = [
            [0, 1, 2], [3, 4, 5], [6, 7, 8], //horizontal
            [0, 3, 6], [1, 4, 7], [2, 5, 8], //vertical
            [0, 4, 8],[2, 4, 6] //diagonal
        ];

        $board = $this->getBoardArray();
        foreach ($wins as $el) {
            if ( ($board[$el[0]] === $board[$el[1]])  && ($board[$el[0]] === $board[$el[2]]) ) {
                $this->winner = $board[$el[0]];
                return true;
            }
        }

        if (!in_array('', $board)) {
            $this->winner = 'd';
            return true;
        }

        return false;
    }
}
