<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Models\Comment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Users
        User::factory()->count(3)->admin()->create();
        User::factory()->count(3)->manager()->create();
        User::factory()->count(5)->create();

        // Projects
        Project::factory()->count(5)->create();

        // Tasks
        Task::factory()->count(10)->create();

        // Comments
        Comment::factory()->count(10)->create();
    }
}
