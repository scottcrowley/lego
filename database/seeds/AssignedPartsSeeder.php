<?php

use App\PartStorageLocation;
use Illuminate\Database\Seeder;

class AssignedPartsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserPartsSeeder::class,
            StorageLocationsSeeder::class,
        ]);

        PartStorageLocation::query()->delete();

        PartStorageLocation::insert([
            [
                'part_num' => '3037',
                'storage_location_id' => 51
            ],
            [
                'part_num' => '3039',
                'storage_location_id' => 51
            ],
            [
                'part_num' => '11211',
                'storage_location_id' => 39
            ],
            [
                'part_num' => '6232',
                'storage_location_id' => 98
            ],
            [
                'part_num' => '3001',
                'storage_location_id' => 6
            ],
            [
                'part_num' => '3002',
                'storage_location_id' => 152
            ],
        ]);
    }
}
