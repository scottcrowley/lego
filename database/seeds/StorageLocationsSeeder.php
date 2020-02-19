<?php

use App\StorageLocation;
use Illuminate\Database\Seeder;

class StorageLocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            StorageLocationTypesSeeder::class,
        ]);

        StorageLocation::query()->delete();

        StorageLocation::insert([
            [
                'id' => '6',
                'name' => 'Pretzel Stick Container',
                'nickname' => null,
                'description' => 'Large plastic pretzel stick container',
                'storage_type_id' => '18', //Clear Plastic Food Container - Large
            ],
            [
                'id' => '152',
                'name' => 'White 2 Drawer Bin #01-02',
                'nickname' => null,
                'description' => 'White 2 drawer bin',
                'storage_type_id' => '5', //White 2 Drawer Bin
            ],
            [
                'id' => '51',
                'name' => 'Large IKEA Bin #05',
                'nickname' => 'Lg Bin 05',
                'description' => 'Large IKEA plastic bin',
                'storage_type_id' => '7', //Large Plastic Bin - Clear
            ],
            [
                'id' => '39',
                'name' => 'Turkey Container #13',
                'nickname' => 'Turkey 13',
                'description' => 'Small flat plastic turkey container',
                'storage_type_id' => '21', //21-Clear Plastic Food Container - XS
            ],
            [
                'id' => '98',
                'name' => 'Red Hanging Case Large #01-06',
                'nickname' => 'Hanging Tall 01-06',
                'description' => 'Large red hanging case',
                'storage_type_id' => '1', //Hanging Red Drawers - Large
            ],
        ]);
    }
}
