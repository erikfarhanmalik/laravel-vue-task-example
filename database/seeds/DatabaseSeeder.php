<?php

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->generateMenus();
        $this->generateUsers();
    }

    private function generateMenus()
    {
        Menu::truncate();
        $data = [
            [
                "name" => "Fried Rice",
                "price" => "10"
            ],
            [
                "name" => "Fried Chicken",
                "price" => "5"
            ],
            [
                "name" => "Lemon Tea",
                "price" => "5"
            ]
        ];

        foreach ($data as $datum) {
            (new Menu($datum))->save();
        }
    }

    private function generateUsers()
    {
        // Let's clear the users table first
        User::truncate();

        $faker = \Faker\Factory::create();

        // Let's make sure everyone has the same password and
        // let's hash it before the loop, or else our seeder
        // will be too slow.
        $password = Hash::make('toptal');

        User::create([
           'name' => 'Administrator',
           'email' => 'admin@test.com',
           'password' => $password,
       ]);

        // And now let's generate a few dozen users for our app:
        for ($i = 0; $i < 10; $i++) {
            User::create([
               'name' => $faker->name,
               'email' => $faker->email,
               'password' => $password,
           ]);
        }
    }
}
