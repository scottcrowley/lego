<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'username' => $faker->unique()->userName,
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\StorageType::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'description' => $faker->sentence(),
    ];
});

$factory->define(App\StorageLocation::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'description' => $faker->sentence(),
        'storage_type_id' => factory(App\StorageType::class)
    ];
});

$factory->define(App\Theme::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'parent_id' => null
    ];
});

$factory->state(App\Theme::class, 'withParent', function (Faker $faker) {
    return [
        'parent_id' => factory(App\Theme::class)
    ];
});

$factory->define(App\Set::class, function (Faker $faker) {
    return [
        'set_num' => $faker->unique()->word,
        'name' => $faker->unique()->word,
        'year' => $faker->year(),
        'set_img_url' => $faker->url(),
        'set_url' => $faker->url(),
        'theme_id' => factory(App\Theme::class),
        'num_parts' => $faker->randomNumber()
    ];
});

$factory->define(App\Part::class, function (Faker $faker) {
    return [
        'part_num' => $faker->unique()->word(),
        'name' => $faker->word,
        'part_url' => $faker->url(),
        'part_img_url' => $faker->url(),
        'part_category_id' => factory(App\PartCategory::class),
    ];
});

$factory->define(App\PartCategory::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
    ];
});

$factory->define(App\Color::class, function (Faker $faker) {
    return [
        'name' => $faker->colorName,
        'rgb' => $faker->rgbCssColor,
        'is_trans' => $faker->boolean()
    ];
});

$factory->define(App\PartRelationship::class, function (Faker $faker) {
    return [
        'child_part_num' => function() {
            return factory(App\Part::class)->create()->part_num;
        },
        'parent_part_num' => function() {
            return factory(App\Part::class)->create()->part_num;
        }
    ];
});