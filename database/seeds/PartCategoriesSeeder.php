<?php

use App\PartCategory;
use Illuminate\Database\Seeder;

class PartCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PartCategory::query()->delete();

        PartCategory::insert([
            [
                'id' => 3,
                'name' => 'Bricks Sloped'
            ],
            [
                'id' => 5,
                'name' => 'Bricks Special'
            ],
            [
                'id' => 11,
                'name' => 'Bricks'
            ],
        ]);
    }
}
