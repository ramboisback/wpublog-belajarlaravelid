<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name'=> 'Dhanni Sultan',
            'username'=> 'dhannisultan',
            'email'=> 'mr.ramdhanniagus@gmail.com',
            'is_admin' => true,
            'password' => Hash::make('password123'),
        ]);

        User::factory(5)->create();
    }
}
