<?php

use App\UserPart;
use Illuminate\Database\Seeder;

class UserPartsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PartsSeeder::class,
            ColorsSeeder::class,
        ]);

        UserPart::query()->delete();

        UserPart::insert([
            [
                'part_num' => '3037', //Slope 45° 2 x 4
                'color_id' => '1', //Blue
                'quantity' => '19',
            ],
            [
                'part_num' => '3037', //Slope 45° 2 x 4
                'color_id' => '4', //Red
                'quantity' => '1',
            ],
            [
                'part_num' => '3039', //Slope 45° 2 x 2
                'color_id' => '1', //Blue
                'quantity' => '22',
            ],
            [
                'part_num' => '3039', //Slope 45° 2 x 2
                'color_id' => '4', //Red
                'quantity' => '9',
            ],
            [
                'part_num' => '11211', //Brick Special 1 x 2 with Studs on 1 Side
                'color_id' => '4', //Red
                'quantity' => '2',
            ],
            [
                'part_num' => '6232', //Brick Special 2 x 2 with Pin and Axle Hole
                'color_id' => '4', //Red
                'quantity' => '6',
            ],
            [
                'part_num' => '6232', //Brick Special 2 x 2 with Pin and Axle Hole
                'color_id' => '72', //Dark Bluish Gray
                'quantity' => '11',
            ],
            [
                'part_num' => '3002', //Brick 2 x 3
                'color_id' => '1', //Blue
                'quantity' => '13',
            ],
            [
                'part_num' => '3002', //Brick 2 x 3
                'color_id' => '72', //Dark Bluish Gray
                'quantity' => '49',
            ],
            [
                'part_num' => '3001', //Brick 2 x 4
                'color_id' => '1', //Blue
                'quantity' => '9',
            ],
            [
                'part_num' => '3001', //Brick 2 x 4
                'color_id' => '4', //Red
                'quantity' => '18',
            ],
            [
                'part_num' => '3001', //Brick 2 x 4
                'color_id' => '70', //Reddish Brown
                'quantity' => '53',
            ],
            [
                'part_num' => '3001', //Brick 2 x 4
                'color_id' => '72', //Dark Bluish Gray
                'quantity' => '64',
            ],
        ]);
    }
}
