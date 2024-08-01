<?php

namespace Tests\Feature;

use App\Models\Monster;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class MonsterControllerTest extends TestCase
{
    use RefreshDatabase;

    private $monster;

    public function setUp(): void
    {
        parent::setUp();
        $this->monster = $this->createMonsters([
            'name' => 'My monster Test',
            'attack' => 20,
            'defense' => 40,
            'hp' => 70,
            'speed' => 10,
            'imageUrl' => ''
        ]);
    }

//    public function test_should_import_all_the_csv_objects_into_the_database_successfully()
//    {
//        // Create a temporary CSV file with valid monster data
//        $csvContent = "name,attack,defense,hp,speed,imageUrl\n" .
//            "Monster1,50,30,100,20,http://example.com/image1.png\n" .
//            "Monster2,60,40,110,25,http://example.com/image2.png";
//
//        $csvPath = storage_path('testing/monsters.csv');
//        file_put_contents($csvPath, $csvContent);
//
//        // Perform the POST request to import the CSV
//        $response = $this->postJson('api/monsters/import', [
//            'csv_file' => new \Illuminate\Http\UploadedFile($csvPath, 'monsters.csv', null, null, true)
//        ])->assertStatus(Response::HTTP_OK);
//
//        // Check if monsters were imported successfully
//        $this->assertDatabaseHas('monsters', ['name' => 'Monster1']);
//        $this->assertDatabaseHas('monsters', ['name' => 'Monster2']);
//    }
//
//    public function test_should_fail_when_importing_csv_file_with_empty_monster()
//    {
//        // Create a temporary CSV file with an empty monster row
//        $csvContent = "name,attack,defense,hp,speed,imageUrl\n" .
//            "Monster1,50,30,100,20,http://example.com/image1.png\n" .
//            ",,40,110,25,http://example.com/image2.png"; // Empty monster row
//
//        $csvPath = storage_path('testing/monsters.csv');
//        file_put_contents($csvPath, $csvContent);
//
//        // Perform the POST request to import the CSV
//        $response = $this->postJson('api/monsters/import', [
//            'csv_file' => new \Illuminate\Http\UploadedFile($csvPath, 'monsters.csv', null, null, true)
//        ])->assertStatus(Response::HTTP_BAD_REQUEST);
//
//        $this->assertEquals('Invalid data found in CSV file.', $response->json('message'));
//    }
//
//    public function test_should_fail_when_importing_csv_file_with_wrong_or_inexistent_columns()
//    {
//        // Create a temporary CSV file with wrong column names
//        $csvContent = "wrong_name_column,attack,defense,hp,speed,imageUrl\n" .
//            "Monster1,50,30,100,20,http://example.com/image1.png";
//
//        $csvPath = storage_path('testing/monsters.csv');
//        file_put_contents($csvPath, $csvContent);
//
//        // Perform the POST request to import the CSV
//        $response = $this->postJson('api/monsters/import', [
//            'csv_file' => new \Illuminate\Http\UploadedFile($csvPath, 'monsters.csv', null, null, true)
//        ])->assertStatus(Response::HTTP_BAD_REQUEST);
//
//        $this->assertEquals('Invalid or missing columns in CSV file.', $response->json('message'));
//    }
//
//    public function test_should_fail_when_trying_import_a_file_with_different_extension()
//    {
//        // Create a temporary text file with the wrong extension
//        $txtContent = "This is not a CSV file.";
//
//        $txtPath = storage_path('testing/monsters.txt');
//        file_put_contents($txtPath, $txtContent);
//
//        // Perform the POST request to import the file
//        $response = $this->postJson('api/monsters/import', [
//            'csv_file' => new \Illuminate\Http\UploadedFile($txtPath, 'monsters.txt', null, null, true)
//        ])->assertStatus(Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
//
//        $this->assertEquals('The provided file is not a valid CSV file.', $response->json('message'));
//    }
//
//    public function test_should_fail_when_importing_none_file()
//    {
//        // Perform the POST request without providing a file
//        $response = $this->postJson('api/monsters/import', [])
//            ->assertStatus(Response::HTTP_BAD_REQUEST);
//
//        $this->assertEquals('No file was uploaded.', $response->json('message'));
//    }

}
