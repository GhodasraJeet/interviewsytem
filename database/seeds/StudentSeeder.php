<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Student::class,500)->create();
        $technology=App\Technology::all();
        App\Student::all()->each(function ($user) use ($technology) {
            $user->getTechnology()->attach(
                $technology->random(rand(1,5))->pluck('id')->toArray()
            );
        });


        // factory(App\Student::class,10)->create()->each(function($student){
            // dd($student);
            // $student->getTechnology()->attach([1,2]);
            // $student->getTechnology()->save(factory(App\Technology::class)->make());
        // });
    }
}
