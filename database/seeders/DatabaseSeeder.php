<?php

namespace Database\Seeders;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create some subjects
        $subjects = [
            'Math',
            'English',
            'Science',
            'History',
            'Computer Science',
        ];

        foreach ($subjects as $subject) {
            Subject::create([
                'subject' => $subject,
            ]);
        }

        // Assign some subjects to users
        $users = User::all();

        foreach ($users as $user) {
            $user->subjects()->attach(Subject::all()->random(3));
        }
    }
}
