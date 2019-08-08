<?php

namespace Tests\Unit;

use App\Set;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SetTest extends TestCase
{
    use RefreshDatabase;

    // /** @test */
    // public function it_requires_a_unique_set_num()
    // {
    //     $this->signIn();

    //     $set = makeRaw('App\Set', ['set_num' => '']);

    //     $this->post(route('sets.store'), $set)
    //         ->assertSessionHasErrors('set_num');

    //     create('App\Set', ['set_num' => 'the set num']);

    //     $set['set_num'] = 'the set num';

    //     $this->post(route('sets.store'), $set)
    //         ->assertSessionHasErrors('set_num');
    // }

    // /** @test */
    // public function it_requires_a_unique_name()
    // {
    //     $this->signIn();

    //     $set = makeRaw('App\Set', ['name' => '']);

    //     $this->post(route('sets.store'), $set)
    //         ->assertSessionHasErrors('name');

    //     create('App\Set', ['name' => 'the set name']);

    //     $set['name'] = 'the set name';

    //     $this->post(route('sets.store'), $set)
    //         ->assertSessionHasErrors('name');
    // }

    // /** @test */
    // public function it_requires_a_year()
    // {
    //     $this->signIn();

    //     $set = makeRaw('App\Set', ['year' => '']);

    //     $this->post(route('sets.store'), $set)
    //         ->assertSessionHasErrors('year');
    // }

    // /** @test */
    // public function it_requires_num_parts()
    // {
    //     $this->signIn();

    //     $set = makeRaw('App\Set', ['num_parts' => '']);

    //     $this->post(route('sets.store'), $set)
    //         ->assertSessionHasErrors('num_parts');
    // }

    // /** @test */
    // public function it_requires_a_valid_theme()
    // {
    //     $this->signIn();

    //     $set = makeRaw('App\Set', ['theme_id' => null]);

    //     $this->post(route('sets.store'), $set)
    //         ->assertSessionDoesntHaveErrors();

    //     $set['theme_id'] = 10;

    //     $this->post(route('sets.store'), $set)
    //         ->assertSessionHasErrors('theme_id');
    // }

    // /** @test */
    // public function it_has_details_on_its_parent_theme()
    // {
    //     $this->signIn();

    //     $theme = create('App\Theme');

    //     create('App\Set', ['theme_id' => $theme->id]);

    //     $set = Set::first();

    //     $this->assertEquals($theme->name, $set->parent_theme->name);
    // }
}
