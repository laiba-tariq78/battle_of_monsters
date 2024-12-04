<?php

namespace App\Services;

use App\Models\Monster;
use App\Repositories\BattleRepository;
use App\Models\Battle;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class BattleService
{
    /**
     * @var $battleRepository
     */
    protected $battleRepository;

    /**
     * BattleService constructor.
     *
     * @param BattleRepository $battleRepository
     *
     */
    public function __construct(BattleRepository $battleRepository)
    {
        $this->battleRepository = $battleRepository;
    }

    /**
     * Get all battles.
     *
     * @return Collection
     *
     */
    public function getAll(): Collection
    {
        return $this->battleRepository->getAllBattles();
    }

    public function startBattle(Monster $monster1, Monster $monster2): string
    {
        $monster1Turn = $monster1->speed > $monster2->speed || $monster1->attack > $monster2->attack;
        $monster1HP = $monster1->hp;
        $monster2HP = $monster2->hp;

        while ($monster1HP > 0 && $monster2HP > 0) {
            if ($monster1Turn) {
                $damage = max($monster1->attack - $monster2->defense, 1);
                $monster2HP -= $damage;
                $monster1Turn = false;
            } else {
                $damage = max($monster2->attack - $monster1->defense, 1);
                $monster1HP -= $damage;
                $monster1Turn = true;
            }
        }

        if ($monster1HP <= 0 && $monster2HP <= 0) {
            return 'Draw';
        }

        return  $monster1HP > 0 ? $monster1->name . ' wins' : $monster2->name . ' wins';
    }


}
