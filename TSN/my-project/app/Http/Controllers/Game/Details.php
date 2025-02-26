<?php

declare(strict_types=1);

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Repositories\GameRepository;
use App\Transformer\GameTransformer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Details extends Controller
{
    public function __construct(
        private readonly GameRepository $repository,
        private readonly GameTransformer $transformer,
    ) {
    }

    public function __invoke(Request $request, string $id): JsonResponse
    {
        /*
        if (!auth()->user()) {
            return response()->json(['message' => 'Not logged in'], 401);
        }
        */

        if (!ctype_digit($id)) {
            return response()->json(['message' => 'Invalid game id'], 400);
        }

        try {
            $game = $this->repository->getByID(intval($id));
        } catch (ModelNotFoundException) {
            return response()->json(['message' => 'Game not found'], 404);
        }

        return response()->json($this->transformer->transform($game));
    }
}
