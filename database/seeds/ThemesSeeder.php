<?php

use App\Theme;
use App\ThemeLabel;
use Illuminate\Database\Seeder;

class ThemesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Theme::query()->delete();

        Theme::insert([
            [
                'id' => 158,
                'name' => 'Star Wars',
                'parent_id' => null
            ],
            [
                'id' => 171,
                'name' => 'Ultimate Collector Series',
                'parent_id' => 158
            ],
            [
                'id' => 1,
                'name' => 'Technic',
                'parent_id' => null
            ],
            [
                'id' => 5,
                'name' => 'Model',
                'parent_id' => 1
            ],
            [
                'id' => 7,
                'name' => 'Construction',
                'parent_id' => 5
            ],
        ]);

        ThemeLabel::query()->delete();

        ThemeLabel::insert([
            [
                'theme_id' => 1,
                'parents_label' => 'None',
                'theme_label' => 'Technic'
            ],
            [
                'theme_id' => 5,
                'parents_label' => 'Technic',
                'theme_label' => 'Technic / Model'
            ],
            [
                'theme_id' => 7,
                'parents_label' => 'Technic / Model',
                'theme_label' => 'Technic / Model / Construction'
            ],
            [
                'theme_id' => 158,
                'parents_label' => 'None',
                'theme_label' => 'Star Wars'
            ],
            [
                'theme_id' => 171,
                'parents_label' => 'Star Wars',
                'theme_label' => 'Star Wars / Ultimate Collector Series'
            ],
        ]);
    }
}
