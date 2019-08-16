<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SetsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_retrieve_all_sets()
    {
        $this->signIn();

        $set = create('App\Set');

        $this->assertTrue(checkName($this->get(route('api.lego.sets')), $set->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_name()
    {
        $this->signIn();

        $first = create('App\Set', ['name' => 'Set A']);

        $second = create('App\Set', ['name' => 'Set B']);

        $this->assertTrue(checkName($this->get(route('api.lego.sets', ['name' => 'Set B'])), $second->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_set_num()
    {
        $this->signIn();

        $first = create('App\Set');

        $second = create('App\Set', ['set_num' => 'Set B']);

        $this->assertTrue(checkName($this->get(route('api.lego.sets', ['set_num' => 'Set B'])), $second->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_theme()
    {
        $this->signIn();

        $theme = create('App\Theme');

        $first = create('App\Set');

        $second = create('App\Set', ['theme_id' => $theme->id]);

        $this->assertTrue(checkName($this->get(route('api.lego.sets', ['theme_id' => $theme->id])), $second->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_exact_year()
    {
        $this->signIn();

        $first = create('App\Set', ['year' => '2004']);

        $second = create('App\Set', ['year' => '2010']);

        $this->assertTrue(checkName($this->get(route('api.lego.sets', ['year' => '2010'])), $second->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_before_a_given_year()
    {
        $this->signIn();

        $first = create('App\Set', ['year' => '2015']);

        $second = create('App\Set', ['year' => '2010']);

        $this->assertTrue(checkName($this->get(route('api.lego.sets', ['minyear' => '2012'])), $second->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_after_a_given_year()
    {
        $this->signIn();

        $first = create('App\Set', ['year' => '2000']);

        $second = create('App\Set', ['year' => '2010']);

        $this->assertTrue(checkName($this->get(route('api.lego.sets', ['maxyear' => '2005'])), $second->name));
    }

    /** @test */
    public function an_authenticated_user_can_sort_sets_by_name()
    {
        $this->signIn();

        $setC = create('App\Set', ['name' => 'C Set']);
        $setA = create('App\Set', ['name' => 'A Set']);
        $setB = create('App\Set', ['name' => 'B Set']);

        $this->get(route('api.lego.sets', ['sort' => 'name']))
            ->assertSeeInOrder([$setA->name, $setB->name, $setC->name]);
    }

    /** @test */
    public function an_authenticated_user_can_sort_sets_by_name_in_descending_order()
    {
        $this->signIn();

        $setC = create('App\Set', ['name' => 'C Set']);
        $setA = create('App\Set', ['name' => 'A Set']);
        $setB = create('App\Set', ['name' => 'B Set']);

        $this->get(route('api.lego.sets', ['sortdesc' => 'name']))
            ->assertSeeInOrder([$setC->name, $setB->name, $setA->name]);
    }

    /** @test */
    public function an_authenticated_user_can_view_a_theme_heirarchy_for_a_set()
    {
        $this->signIn();

        $topParentTheme = create('App\Theme', ['name' => 'top parent theme']);

        $parentTheme = create('App\Theme', ['name' => 'parent theme', 'parent_id' => $topParentTheme->id]);

        $childTheme = create('App\Theme', ['name' => 'child theme', 'parent_id' => $parentTheme->id]);

        create('App\Set', ['theme_id' => $childTheme->id]);

        $response = $this->get(route('api.lego.sets'));

        $this->assertEquals($response->getData()->data[0]->theme_label, 'top parent theme -> parent theme -> child theme');
    }
}
