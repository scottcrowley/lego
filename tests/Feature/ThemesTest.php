<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThemesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_retrieve_all_themes()
    {
        $this->signIn();

        $theme = create('App\Theme');

        $response = $this->get(route('api.lego.themes'));

        $this->assertTrue(checkNameExists($response, $theme->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_name()
    {
        $this->signIn();

        $first = create('App\Theme', ['name' => 'First Theme']);

        $second = create('App\Theme', ['name' => 'Second Theme']);

        $response = $this->get(route('api.lego.themes', ['name' => 'Second Theme']));

        $this->assertTrue(checkNameExists($response, $second->name));
        $this->assertFalse(checkNameExists($response, $first->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_parent_theme()
    {
        $this->signIn();

        $parent = create('App\Theme');

        $first = create('App\Theme');

        $second = create('App\Theme', ['parent_id' => $parent->id]);

        $response = $this->get(route('api.lego.themes', ['parent_id' => $parent->id]));

        $this->assertTrue(checkNameExists($response, $second->name));
        $this->assertFalse(checkNameExists($response, $first->name));
    }

    /** @test */
    public function an_authenticated_user_can_sort_themes_by_name()
    {
        $this->signIn();

        $themeC = create('App\Theme', ['name' => 'C Theme']);
        $themeA = create('App\Theme', ['name' => 'A Theme']);
        $themeB = create('App\Theme', ['name' => 'B Theme']);

        $this->get(route('api.lego.themes', ['sort' => 'name']))
            ->assertSeeInOrder([$themeA->name, $themeB->name, $themeC->name]);
    }

    /** @test */
    public function an_authenticated_user_can_sort_themes_by_name_in_descending_order()
    {
        $this->signIn();

        $themeC = create('App\Theme', ['name' => 'C Theme']);
        $themeA = create('App\Theme', ['name' => 'A Theme']);
        $themeB = create('App\Theme', ['name' => 'B Theme']);

        $this->get(route('api.lego.themes', ['sort' => '-name']))
            ->assertSeeInOrder([$themeC->name, $themeB->name, $themeA->name]);
    }

    /** @test */
    public function an_authenticated_user_can_view_the_heirarchy_name_of_a_theme()
    {
        $this->signIn();

        $themeLabel = create('App\ThemeLabel');

        $data = ($this->get(route('api.lego.themes')))->getData()->data;

        $this->assertEquals($themeLabel->theme_label, $data[2]->theme_label);
    }
}
