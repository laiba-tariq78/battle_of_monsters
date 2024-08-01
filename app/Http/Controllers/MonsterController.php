<?php

namespace App\Http\Controllers;

use App\Models\Monster;
use App\Services\MonsterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\QueryException;

class MonsterController extends Controller
{
    /**
     *
     * @var $monsterService
     */
    protected $monsterService;

    /**
     * MonsterService constructor.
     *
     * @param MonsterService $monsterService
     *
     */
    public function __construct(MonsterService $monsterService)
    {
        $this->monsterService = $monsterService;
    }

    /**
     * Create new monster.
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     */
    public function index(): JsonResponse
    {
        $monsters = Monster::all();

        return response()->json(
            [
                'data' => $this->$monsters
            ],
            Response::HTTP_OK
        );
    }
    /**
     * Create new monster.
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     */
    public function store(Request $request): JsonResponse
    {
        $newMonster = $request->only([
            'name',
            'attack',
            'defense',
            'hp',
            'speed',
            'imageUrl'
        ]);

        return response()->json(
            [
                'data' => $this->monsterService->createMonster($newMonster)
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * Update a monster.
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     */
    public function update(Request $request): JsonResponse
    {
        $monsterId = $request->route('id');
        $newMonster = $request->only([
            'name',
            'attack',
            'defense',
            'hp',
            'speed',
            'imageUrl'
        ]);

        $result = $this->monsterService->getMonsterById($monsterId);
        $this->monsterService->updateMonster($monsterId, $newMonster);
        return response()->json('', Response::HTTP_OK);
    }

    /**
     * Remove a monster.
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     */
    public function remove(Request $request): JsonResponse
    {
        $monsterId = $request->route('id');
        $result = $this->monsterService->getMonsterById($monsterId);
        $this->monsterService->removeMonster($monsterId);
        return response()->json('', Response::HTTP_NO_CONTENT);
    }

    public function importCsv(Request $request): JsonResponse
    {
        $file = $request->file('file');
        if ($file) {
            $ext = $file->getClientOriginalExtension();
            if (!in_array($ext, ['csv'])) {
                return response()->json(['message' => 'File should be csv.'], Response::HTTP_BAD_REQUEST);
            }

            if (($handle = fopen($file, "r")) !== FALSE) {
                while (!feof($handle)) {
                    $rowData[] = fgetcsv($handle);
                }

                $csv_data = array_slice($rowData, 1, count($rowData));

                try {
                    $this->monsterService->importMonster($rowData, $csv_data);
                    return response()->json(['data' => 'Records were imported successfully.'], Response::HTTP_OK);
                } catch (QueryException $e) {
                    return response()->json(['message' => 'Incomplete data, check your file.'], Response::HTTP_BAD_REQUEST);
                } catch (Exception $e) {
                    return response()->json(['message' => 'Wrong data mapping.'], Response::HTTP_BAD_REQUEST);
                }
            }
        }

        return response()->json(['message' => 'Wrong data mapping.'], Response::HTTP_BAD_REQUEST);
    }

}
