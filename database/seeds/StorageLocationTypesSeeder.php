<?php

use App\StorageType;
use Illuminate\Database\Seeder;

class StorageLocationTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StorageType::query()->delete();

        StorageType::insert([
            [
                'id' => '18',
                'name' => 'Clear Plastic Food Container - Large',
                'description' => 'Large plastic food container',
            ],
            [
                'id' => '5',
                'name' => 'White 2 Drawer Bin',
                'description' => 'White bin with 2 pull out drawers and a lift top storage area',
            ],
            [
                'id' => '7',
                'name' => 'Large Plastic Bin - Clear',
                'description' => 'Large clear IKEA plastic storage bin with lid',
            ],
            [
                'id' => '21',
                'name' => 'Clear Plastic Food Container - XS',
                'description' => 'Small plastic turkey container with red lid',
            ],
            [
                'id' => '1',
                'name' => 'Hanging Red Drawers - Large',
                'description' => 'Large hanging red cabinets with pull out drawers',
            ],
        ]);
    }
}
