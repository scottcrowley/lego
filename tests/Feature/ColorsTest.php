<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ColorsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_view_all_colors()
    {
        $this->signIn();

        $color = create('App\Color');

        $this->get(route('colors.index'))
            ->assertSee($color->name);
    }

    /** @test */
    public function an_authenticated_user_can_add_a_new_color()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $color = makeRaw('App\Color');

        $this->post(route('colors.store'), $color);

        $this->assertDatabaseHas('colors', $color);
    }
}
