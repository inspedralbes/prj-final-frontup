<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProjectsTableSeeder extends Seeder
{
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/data/projects.json'));
        $projects = json_decode($json, true);

        foreach ($projects as &$project) {
            if (isset($project['created_at'])) {
                $project['created_at'] = Carbon::parse($project['created_at'])->format('Y-m-d H:i:s');
            }
            if (isset($project['updated_at'])) {
                $project['updated_at'] = Carbon::parse($project['updated_at'])->format('Y-m-d H:i:s');
            }
        }

        DB::table('projects')->insert($projects);
    }
}
