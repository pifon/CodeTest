<?php

declare(strict_types=1);

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Models\Game;
//use App\Models\User;
use App\Repositories\GameRepository;
use App\Transformer\GameTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class Move extends Controller
{
    public function __construct(
        private readonly GameRepository $repository,
        private readonly GameTransformer $transformer,
    ) {
    }

    public function __invoke(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'board' => 'required|array|size:9',
            'board.*' => 'nullable|in:x,o,'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => "Invalid game id or format"], 400);
        }

        $newBoard = $validator->validated();

        /*
        if (!auth()->user()) {
            return response()->json(['message' => 'Not logged in'], 401);
        }
        */
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Not logged in'], 401);
        }

        if (!ctype_digit($id)) {
            return response()->json(['message' => 'Invalid game id or format'], 400);
        }

        try {
            $game = $this->repository->getByID(intval($id));
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Game not found'], 404);
        }

        $player = $user->getAuthIdentifierName();
        if (!in_array($player , [$game->getXPlayer(), $game->getOPlayer()])) {
            return response()->json(['message' => 'Not a player'], 403);
        }

        if (!$this->validMove($newBoard, $game, $player)) {
            return response()->json(['message' => 'Wrong move'], 409);
        }

        $game->board = $newBoard;
        $game->isFinished();
        $game->save();

        return response()->json($this->transformer->transform($game));
    }

    private function validMove(array $newBoard, Game $game, string $player): bool
    {
        // Game is not finished
        if (!empty($game->getWinner())) {
            return false;
        }

        // Exactly 1 change made on board
        $move = array_diff_assoc($newBoard,$game->getBoardArray());
        if (1 !== count($move)) {
            return false;
        }

        // new symbol matches user's assigned
        $moveSymbol = array_values($move)[0];
        if ($moveSymbol === "x") {
            $playerByMark = $game->getXPlayer();
        } else {
            $playerByMark = $game->getOPlayer();
        }

        if ($player !== $playerByMark) {
            return false;
        }

        return true;
    }
}
