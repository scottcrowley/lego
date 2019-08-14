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
        $this->withoutExceptionHandling();

        $this->signIn();
        
        $set = create('App\Set');
        
        $this->get(route('api.lego.sets'))
            ->assertSee($set->name);
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_name()
    {
        $this->signIn();

        $first = create('App\Set', ['name' => 'Set A']);
        
        $second = create('App\Set', ['name' => 'Set B']);

        $this->get(route('api.lego.sets', ['name' => 'Set B']))
            ->assertSee($second->name)
            ->assertDontSee($first->name);
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_set_num()
    {
        $this->signIn();

        $first = create('App\Set');
        
        $second = create('App\Set', ['set_num' => 'Set B']);

        $this->get(route('api.lego.sets', ['set_num' => 'Set B']))
            ->assertSee($second->name)
            ->assertDontSee($first->name);
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_theme()
    {
        $this->signIn();

        $theme = create('App\Theme');

        $first = create('App\Set');
        
        $second = create('App\Set', ['theme_id' => $theme->id]);

        $this->get(route('api.lego.sets', ['theme_id' => $theme->id]))
            ->assertSee($second->name)
            ->assertDontSee($first->name);
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_exact_year()
    {
        $this->signIn();

        $first = create('App\Set', ['year' => '2004']);
        
        $second = create('App\Set', ['year' => '2010']);

        $this->get(route('api.lego.sets', ['year' => '2010']))
            ->assertSee($second->name)
            ->assertDontSee($first->name);
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_before_a_given_year()
    {
        $this->signIn();

        $first = create('App\Set', ['year' => '2015']);
        
        $second = create('App\Set', ['year' => '2010']);

        $this->get(route('api.lego.sets', ['beforeyear' => '2012']))
            ->assertSee($second->name)
            ->assertDontSee($first->name);
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_after_a_given_year()
    {
        $this->signIn();

        $first = create('App\Set', ['year' => '2000']);
        
        $second = create('App\Set', ['year' => '2010']);

        $this->get(route('api.lego.sets', ['afteryear' => '2005']))
            ->assertSee($second->name)
            ->assertDontSee($first->name);
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
}
