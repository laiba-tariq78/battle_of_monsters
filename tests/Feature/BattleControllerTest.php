<?php

namespace Tests\Feature;

use App\Models\Monster;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class BattleControllerTest extends TestCase
{
    use RefreshDatabase;

    private $battle, $monster1, $monster2, $monster3, $monster4, $monster5, $monster6, $monster7;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_should_create_a_battle_with_a_bad_request_response_if_one_parameter_is_null()
    {
        $response = $this->postJson('api/battles', [
            'monsterA_id' => 1,
            'monsterB_id' => null,
            'battle_location' => 'Arena'
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJson([
                'error' => 'Bad Request',
            ]);
    }

    public function test_should_create_a_battle_with_404_error_if_one_parameter_has_a_monster_id_does_not_exists()
    {
        $response = $this->postJson('api/battles', [
            'monsterA_id' => 1,
            'monsterB_id' => 999, // Assuming 999 is a non-existent monster ID
            'battle_location' => 'Arena'
        ]);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                'error' => 'Monster not found',
            ]);
    }

    public function test_should_create_battle_correctly_with_monsterA_winning()
    {
        $monsterA = Monster::factory()->create(['attack' => 100, 'name' => 'Monster A']);
        $monsterB = Monster::factory()->create(['attack' => 50, 'name' => 'Monster B']);

        $response = $this->postJson('api/battles', [
            'monsterA_id' => $monsterA->id,
            'monsterB_id' => $monsterB->id,
            'battle_location' => 'Arena'
        ])->assertStatus(Response::HTTP_OK);

        $this->assertEquals('Monster A wins', $response->json('result'));
    }

    public function test_should_create_battle_correctly_with_monsterB_winning()
    {
        $monsterA = Monster::factory()->create(['attack' => 50, 'name' => 'Monster A']);
        $monsterB = Monster::factory()->create(['attack' => 100, 'name' => 'Monster B']);

        $response = $this->postJson('api/battles', [
            'monsterA_id' => $monsterA->id,
            'monsterB_id' => $monsterB->id,
            'battle_location' => 'Arena'
        ])->assertStatus(Response::HTTP_OK);

        $this->assertEquals('Monster B wins', $response->json('result'));
    }

    public function test_should_create_battle_correctly_with_monsterA_winning_if_theirs_speeds_same_and_monsterA_has_higher_attack()
    {
        $monsterA = Monster::factory()->create(['attack' => 100, 'speed' => 50, 'name' => 'Monster A']);
        $monsterB = Monster::factory()->create(['attack' => 50, 'speed' => 50,'name' => 'Monster B']);

        $response = $this->postJson('api/battles', [
            'monsterA_id' => $monsterA->id,
            'monsterB_id' => $monsterB->id,
            'battle_location' => 'Arena'
        ])->assertStatus(Response::HTTP_OK);

        $this->assertEquals('Monster A wins', $response->json('result'));
    }

    public function test_should_create_battle_correctly_with_monsterB_winning_if_theirs_speeds_same_and_monsterB_has_higher_attack()
    {
        $monsterA = Monster::factory()->create(['attack' => 50, 'speed' => 50, 'name' => 'Monster A']);
        $monsterB = Monster::factory()->create(['attack' => 100, 'speed' => 50,'name' => 'Monster B']);

        $response = $this->postJson('api/battles', [
            'monsterA_id' => $monsterA->id,
            'monsterB_id' => $monsterB->id,
            'battle_location' => 'Arena'
        ])->assertStatus(Response::HTTP_OK);

        $this->assertEquals('Monster B wins', $response->json('result'));
    }

    public function test_should_create_battle_correctly_with_monsterA_winning_if_theirs_defense_same_and_monsterA_has_higher_speed()
    {

        $monsterA = Monster::factory()->create(['speed' => 100, 'defense' => 50, 'attack' => 100, 'name' => 'Monster A']);
        $monsterB = Monster::factory()->create(['speed' => 80, 'defense' => 50, 'attack' => 50, 'name' => 'Monster B']);

        $response = $this->postJson('api/battles', [
            'monsterA_id' => $monsterA->id,
            'monsterB_id' => $monsterB->id,
            'battle_location' => 'Arena'
        ])->assertStatus(Response::HTTP_OK);

        $this->assertEquals('Monster A wins', $response->json('result'));
    }

    public function test_should_delete_a_battle_correctly()
    {
        $battle = Monster::factory()->create();

        $this->deleteJson("api/battles/{$battle->id}");

        $this->assertDatabaseMissing('battles', ['id' => $battle->id]);
    }

    public function test_should_delete_with_404_error_if_battle_does_not_exists()
    {
        $response = $this->deleteJson('api/battles/9999');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson([
                'error' => 'Battle not found',
            ]);
    }
}
