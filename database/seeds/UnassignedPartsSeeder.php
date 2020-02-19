<?php

use Illuminate\Database\Seeder;

class UnassignedPartsSeeder extends Seeder
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
        ]);
    }
}
