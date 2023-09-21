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
      $subjects = [
        ['Math', 50],
        ['Science', 60],
        ['English', 70],
      ];

      foreach ($subjects as $subject) {
        $newSubject = new Subject();

        $newSubject->user_id = 1;
        $newSubject->subject = $subject[0];
        $newSubject->pass_mark = $subject[1];

        $newSubject->save();
      }
    }
}
