<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ColorsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_retrieve_all_colors()
    {
        $this->signIn();

        $color = create('App\Color');

        $response = $this->get(route('api.lego.colors'));

        $this->assertTrue(checkNameExists($response, $color->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_color_name()
    {
        $this->signIn();

        $white = create('App\Color', ['name' => 'white']);

        $black = create('App\Color', ['name' => 'black']);

        $response = $this->get(route('api.lego.colors', ['name' => 'black']));

        $this->assertTrue(checkNameExists($response, $black->name));
        $this->assertfalse(checkNameExists($response, $white->name));
    }

    /** @test */
    public function an_authenticated_user_can_sort_colors_by_name()
    {
        $this->signIn();

        $colorC = create('App\Color', ['name' => 'C Color']);
        $colorA = create('App\Color', ['name' => 'A Color']);
        $colorB = create('App\Color', ['name' => 'B Color']);

        $this->get(route('api.lego.colors', ['sort' => 'name']))
            ->assertSeeInOrder([$colorA->name, $colorB->name, $colorC->name]);
    }

    /** @test */
    public function an_authenticated_user_can_sort_colors_by_name_in_descending_order()
    {
        $this->signIn();

        $colorC = create('App\Color', ['name' => 'C Color']);
        $colorA = create('App\Color', ['name' => 'A Color']);
        $colorB = create('App\Color', ['name' => 'B Color']);

        $this->get(route('api.lego.colors', ['sort' => '-name']))
            ->assertSeeInOrder([$colorC->name, $colorB->name, $colorA->name]);
    }
}
