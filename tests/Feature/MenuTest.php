<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Models\Menu;

class MenuTest extends TestCase
{
    public function testsMenusAreCreatedCorrectly()
    {
        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        $payload = [
          'name' => 'Lorem',
          'price' => 100,
        ];

        $this->json('POST', '/api/menus', $payload, $headers)
          ->assertStatus(201)
          ->assertJson(['name' => 'Lorem', 'price' => 100]);
    }

    public function testsMenusAreUpdatedCorrectly()
    {
        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        $menu = factory(Menu::class)->create([
          'name' => 'Fried Dragon',
          'price' => 10000
        ]);

        $payload = [
            'name' => 'Fried Dragon updated',
            'price' => 10
        ];

        $response = $this->json('PUT', '/api/menus/' . $menu->id, $payload, $headers)
          ->assertStatus(200)
          ->assertJson([
              'id' => $menu->id,
              'name' => 'Fried Dragon updated',
              'price' => 10,
          ]);
    }

    public function testsMenusAreDeletedCorrectly()
    {
        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];
        $menu = factory(Menu::class)->create([
            'name' => 'Fried Dragon',
            'price' => 1000,
        ]);

        $this->json('DELETE', '/api/menus/' . $menu->id, [], $headers)->assertStatus(204);
    }

    public function testMenusAreListedCorrectly()
    {
        Menu::truncate();

        factory(Menu::class)->create([
            'name' => 'Dragon Steak',
            'price' => 10
        ]);

        factory(Menu::class)->create([
            'name' => 'Snake Steak',
            'price' => 100
        ]);

        $user = factory(User::class)->create();
        $token = $user->generateToken();
        $headers = ['Authorization' => "Bearer $token"];

        $response = $this->json('GET', '/api/menus', [], $headers)
          ->assertStatus(200)
          ->assertJson([
              [ 'name' => 'Dragon Steak', 'price' => 10 ],
              [ 'name' => 'Snake Steak', 'price' => 100 ]
          ])
          ->assertJsonStructure([
              '*' => ['id', 'price', 'name', 'created_at', 'updated_at'],
          ]);
    }
}
