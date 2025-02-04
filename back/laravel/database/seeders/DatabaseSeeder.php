<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    
    public function run(): void
{
    User::factory()->create([
        'id' => 1,
        'name' => 'Usuari 1',
        'email' => 'usuari1@example.com',
    ]);

    User::factory()->create([
        'id' => 2,
        'name' => 'Usuari 2',
        'email' => 'usuari2@example.com',
    ]);

    $this->call(ProjectsTableSeeder::class);
}

}
