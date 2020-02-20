<?php

use App\Set;
use Illuminate\Database\Seeder;

class SetsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ThemesSeeder::class,
        ]);

        Set::query()->delete();

        Set::insert([
            [
                'set_num' => '8110-1',
                'name' => 'Unimog U400',
                'year' => 2011,
                'theme_id' => 7,
                'num_parts' => 2048
            ],
            [
                'set_num' => '8053-1',
                'name' => 'Mobile Crane',
                'year' => 2010,
                'theme_id' => 7,
                'num_parts' => 1289
            ],
            [
                'set_num' => '8069-1',
                'name' => 'Backhoe Loader',
                'year' => 2011,
                'theme_id' => 7,
                'num_parts' => 609
            ],
            [
                'set_num' => '10195-1',
                'name' => 'Republic Dropship with AT-OT',
                'year' => 2009,
                'theme_id' => 171,
                'num_parts' => 1757
            ],
            [
                'set_num' => '10212-1',
                'name' => 'Imperial Shuttle',
                'year' => 2010,
                'theme_id' => 171,
                'num_parts' => 2502
            ],
            [
                'set_num' => '75021-1',
                'name' => 'Republic Gunship',
                'year' => 2013,
                'theme_id' => 158,
                'num_parts' => 1174
            ],
            [
                'set_num' => '7965-1',
                'name' => 'Millennium Falcon',
                'year' => 2011,
                'theme_id' => 158,
                'num_parts' => 1253
            ],
        ]);
    }
}
