<?php

use App\UserSet;
use Illuminate\Database\Seeder;

class UserSetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SetsSeeder::class,
        ]);

        UserSet::query()->delete();

        UserSet::insert([
            [
                'set_num' => '8110-1',
                'quantity' => 1
            ],
            [
                'set_num' => '8053-1',
                'quantity' => 1
            ],
            [
                'set_num' => '8069-1',
                'quantity' => 1
            ],
            [
                'set_num' => '10195-1',
                'quantity' => 1
            ],
            [
                'set_num' => '10212-1',
                'quantity' => 1
            ],
            [
                'set_num' => '75021-1',
                'quantity' => 1
            ],
            [
                'set_num' => '7965-1',
                'quantity' => 1
            ],
        ]);
    }
}
