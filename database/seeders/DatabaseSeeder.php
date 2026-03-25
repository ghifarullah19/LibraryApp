<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin Tester',
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
        ]);

        Author::factory(10)->create();

        Publisher::factory(5)->create();

        Book::factory(30)->create();
    }
}
