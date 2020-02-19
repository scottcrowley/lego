<?php

use App\Color;
use Illuminate\Database\Seeder;

class ColorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Color::query()->delete();

        Color::insert([
            [
                'id' => 1,
                'name' => 'Blue',
                'rgb' => '0055BF',
            ],
            [
                'id' => 4,
                'name' => 'Red',
                'rgb' => 'C91A09',
            ],
            [
                'id' => 70,
                'name' => 'Reddish Brown',
                'rgb' => '582A12',
            ],
            [
                'id' => 72,
                'name' => 'Dark Bluish Gray',
                'rgb' => '6C6E68',
            ],
        ]);
    }
}
