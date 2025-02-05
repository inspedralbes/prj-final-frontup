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
        'name' => 'admin',
        'email' => 'admin@gmail.com',
        'password' => '1234'
    ]);
    
    User::factory()->create([
        'id' => 2,
        'name' => 'juan',
        'email' => 'juan@gmail.com',
        'password' => '1234'
    ]);

    $this->call(ProjectsTableSeeder::class);
}

}
