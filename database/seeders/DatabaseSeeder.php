<?php

namespace Database\Seeders;

use App\Models\Subject;
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
        $subject = new Subject();

        $subject->user_id = 1;
        $subject->subject = 'Math';
        $subject->pass_mark = 50;

        $subject->save();
    }
}
