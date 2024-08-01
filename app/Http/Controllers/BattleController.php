<?php

namespace App\Http\Controllers;

use App\Models\Battle;
use App\Services\BattleService;
use App\Services\MonsterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class BattleController extends Controller
{

    /**
     *
     * @var $battleService
     */
    protected $battleService;

    /**
     *
     * @var $monsterService
     */
    protected $monsterService;

    /**
     * BattleService constructor.
     *
     * @param BattleService $battleService
     * @param MonsterService $monsterService
     *
     */
    public function __construct(BattleService $battleService, MonsterService $monsterService)
    {
        $this->battleService = $battleService;
        $this->monsterService = $monsterService;
    }

    /**
     * Get all battles.
     *
     * @return JsonResponse
     *
     */
    public function index(): JsonResponse
    {
        return response()->json(
            [
                'data' => $this->battleService->getAll()
            ],
            Response::HTTP_OK
        );
    }


    /**
     * Start a battle between two monsters.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function startBattle(Request $request): JsonResponse
    {
        $monster1Id = $request->input('monsterA_id');
        $monster2Id = $request->input('monsterB_id');


        if (is_null($monster1Id) || is_null($monster2Id)) {
            return response()->json(['error' => 'Bad Request'], Response::HTTP_BAD_REQUEST);
        }

        $monster1 = $this->monsterService->getMonsterById($monster1Id);
        $monster2 = $this->monsterService->getMonsterById($monster2Id);


        if (!$monster1 || !$monster2) {
            return response()->json(['error' => 'Monster not found'], Response::HTTP_NOT_FOUND);
        }

        $winner = $this->battleService->startBattle($monster1, $monster2);

        return response()->json(['result' => $winner], Response::HTTP_OK);
    }





    /**
     * Remove a battle.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function remove(Request $request): JsonResponse
    {
        $battleId = $request->route('id');
        $battle = Battle::find($battleId);

        if ($battle) {
            $battle->delete();
            return response()->json(null, Response::HTTP_NO_CONTENT);
        }
        return response()->json(['error' => 'Battle not found'], Response::HTTP_NOT_FOUND);
    }
}
