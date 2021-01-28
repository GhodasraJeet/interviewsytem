<?php

use App\Technology;
use Illuminate\Database\Seeder;

class TechnologySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['tech'=>'Laravel'],
            ['tech'=>'JavaScript'],
            ['tech'=>'jQuery'],
            ['tech'=>'HTML'],
            ['tech'=>'Android'],
            ['tech'=>'iOs'],
            ['tech'=>'Python'],
            ['tech'=>'React Native'],
            ['tech'=>'CSS'],
            ['tech'=>'React'],
        ];
        Technology::insert($data);
    }
}
