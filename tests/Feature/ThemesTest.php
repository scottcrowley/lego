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
        $this->withoutExceptionHandling();

        $this->signIn();

        $theme = create('App\Theme');

        $this->get(route('api.lego.themes'))
            ->assertSee($theme->name);
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_name()
    {
        $this->signIn();

        $first = create('App\Theme', ['name' => 'First Theme']);

        $second = create('App\Theme', ['name' => 'Second Theme']);

        $this->get(route('api.lego.themes', ['name' => 'Second Theme']))
            ->assertSee($second->name)
            ->assertDontSee($first->name);
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_parent_theme()
    {
        $this->signIn();

        $parent = create('App\Theme');

        $first = create('App\Theme', ['parent_id' => $parent->id]);

        $second = create('App\Theme');

        $this->get(route('api.lego.themes', ['parent_id' => $parent->id]))
            ->assertSee($first->name)
            ->assertDontSee($second->name);
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

        $this->get(route('api.lego.themes', ['sortdesc' => 'name']))
            ->assertSeeInOrder([$themeC->name, $themeB->name, $themeA->name]);
    }

    /** @test */
    public function an_authenticated_user_can_view_the_heirarchy_name_of_a_theme()
    {
        $this->signIn();

        $topParent = create('App\Theme', ['name' => 'top parent']);

        $parent = create('App\Theme', ['name' => 'parent', 'parent_id' => $topParent->id]);

        $child = create('App\Theme', ['name' => 'child', 'parent_id' => $parent->id]);

        $this->get(route('api.lego.themes', ['name' => 'child']))
            ->assertSee('top parent -> parent');
    }
}
