<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SetsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_view_all_sets()
    {
        $this->signIn();

        $set = create('App\Set');

        $this->get(route('sets.index'))
            ->assertSee($set->name);
    }

    /** @test */
    public function an_authenticated_user_can_add_a_new_set()
    {
        $this->signIn();

        $set = makeRaw('App\Set');

        $this->post(route('sets.store'), $set);

        $this->assertDatabaseHas('sets', $set);
    }
}
