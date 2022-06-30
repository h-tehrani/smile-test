<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                "name" => "Arisha Barron"
            ],
            [
                "name" => "Branden Gibson"
            ],
            [
                "name" => "Rhonda Church"
            ],
            [
                "name" => "Georgina Hazel"
            ]
        ];
        User::query()->insert($users);
    }
}
