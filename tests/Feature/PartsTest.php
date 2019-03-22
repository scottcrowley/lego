<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PartsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_view_all_parts()
    {
        $this->signIn();

        $part = create('App\Part');

        $this->get(route('parts.index'))
            ->assertSee($part->name);
    }

    /** @test */
    public function an_authenticated_user_add_a_new_part()
    {
        $this->withExceptionHandling();

        $this->signIn();

        $part = makeRaw('App\Part');

        $this->post(route('parts.store'), $part)
            ->assertRedirect(route('parts.index'));

        $this->assertDatabaseHas('parts', $part);
    }
}
