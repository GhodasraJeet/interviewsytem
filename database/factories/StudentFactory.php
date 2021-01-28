<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Student;
use Faker\Generator as Faker;

$factory->define(Student::class, function (Faker $faker) {
    return [
		'state_id' => $faker->numberBetween($min = 1, $max = 7),
        'name' => $faker->name,
        'email' => $faker->email,
        'phone' => '9876543125',
        'attachment' =>'1609479229.png',
        'date' => $faker->dateTime($max = 'now', $timezone = null),
        'fresher' => 1,
        'expereince_year' => 0,
        'expereince_month' => 0,
    ];
});
