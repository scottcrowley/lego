<?php

use App\Part;
use Illuminate\Database\Seeder;

class PartsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PartCategoriesSeeder::class,
        ]);

        Part::query()->delete();

        Part::insert([
            [
                'part_num' => '3037',
                'name' => 'Slope 45° 2 x 4',
                'part_category_id' => '3', //Bricks Sloped
            ],
            [
                'part_num' => '3039',
                'name' => 'Slope 45° 2 x 2',
                'part_category_id' => '3', //Bricks Sloped
            ],
            [
                'part_num' => '11211',
                'name' => 'Brick Special 1 x 2 with Studs on 1 Side',
                'part_category_id' => '5', //Bricks Special
            ],
            [
                'part_num' => '6232',
                'name' => 'Brick Special 2 x 2 with Pin and Axle Hole',
                'part_category_id' => '5', //Bricks Special
            ],
            [
                'part_num' => '3002',
                'name' => 'Brick 2 x 3',
                'part_category_id' => '11', //Bricks
            ],
            [
                'part_num' => '3001',
                'name' => 'Brick 2 x 4',
                'part_category_id' => '11', //Bricks
            ],
        ]);
    }
}
