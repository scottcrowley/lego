<?php

namespace Tests\Feature;

use App\ThemeLabel;
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

        $response = $this->get(route('api.lego.sets'));

        $this->assertTrue(checkNameExists($response, $set->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_name()
    {
        $this->signIn();

        $first = create('App\Set', ['name' => 'Set A']);

        $second = create('App\Set', ['name' => 'Set B']);

        $response = $this->get(route('api.lego.sets', ['name' => 'Set B']));

        $this->assertTrue(checkNameExists($response, $second->name));
        $this->assertFalse(checkNameExists($response, $first->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_set_num()
    {
        $this->signIn();

        $first = create('App\Set');

        $second = create('App\Set', ['set_num' => 'Set B']);

        $response = $this->get(route('api.lego.sets', ['set_num' => 'Set B']));

        $this->assertTrue(checkNameExists($response, $second->name));
        $this->assertFalse(checkNameExists($response, $first->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_theme_id()
    {
        $this->signIn();

        $theme = create('App\Theme');

        $first = create('App\Set');

        $second = create('App\Set', ['theme_id' => $theme->id]);

        $response = $this->get(route('api.lego.sets', ['theme_id' => $theme->id]));

        $this->assertTrue(checkNameExists($response, $second->name));
        $this->assertFalse(checkNameExists($response, $first->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_theme_label()
    {
        $this->signIn();

        $parentTheme = create('App\Theme', ['name' => 'The Parent Theme']);
        $firstTheme = create('App\Theme', ['name' => 'First Theme']);
        $secondTheme = create('App\Theme', ['name' => 'Second Theme', 'parent_id' => $parentTheme->id]);
        ThemeLabel::create(['theme_id' => $secondTheme->id, 'parents_label' => $parentTheme->name, 'theme_label' => $parentTheme->name.' / '.$secondTheme->name]);

        $firstSet = create('App\Set', ['theme_id' => $firstTheme->id]);
        $secondSet = create('App\Set', ['theme_id' => $secondTheme->id]);

        $response = $this->get(route('api.lego.sets', ['theme_label' => $secondTheme->name]));

        $this->assertTrue(checkNameExists($response, $secondSet->name));
        $this->assertFalse(checkNameExists($response, $firstSet->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_exact_year()
    {
        $this->signIn();

        $firstSet = create('App\Set', ['year' => '2004']);
        $secondSet = create('App\Set', ['year' => '2010']);

        $response = $this->get(route('api.lego.sets', ['year' => '2010']));

        $this->assertTrue(checkNameExists($response, $secondSet->name));
        $this->assertFalse(checkNameExists($response, $firstSet->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_after_a_given_year()
    {
        $this->signIn();

        $firstSet = create('App\Set', ['year' => '2010']);
        $secondSet = create('App\Set', ['year' => '2015']);

        $response = $this->get(route('api.lego.sets', ['minyear' => '2012']));

        $this->assertTrue(checkNameExists($response, $secondSet->name));
        $this->assertFalse(checkNameExists($response, $firstSet->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_before_a_given_year()
    {
        $this->signIn();

        $firstSet = create('App\Set', ['year' => '2010']);
        $secondSet = create('App\Set', ['year' => '2000']);

        $response = $this->get(route('api.lego.sets', ['maxyear' => '2005']));

        $this->assertTrue(checkNameExists($response, $secondSet->name));
        $this->assertFalse(checkNameExists($response, $firstSet->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_a_minimum_number_of_pieces()
    {
        $this->signIn();

        $firstSet = create('App\Set', ['num_parts' => '500']);
        $secondSet = create('App\Set', ['num_parts' => '2000']);

        $response = $this->get(route('api.lego.sets', ['minpieces' => 1000]));

        $this->assertTrue(checkNameExists($response, $secondSet->name));
        $this->assertFalse(checkNameExists($response, $firstSet->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_a_maximum_number_of_pieces()
    {
        $this->signIn();

        $firstSet = create('App\Set', ['num_parts' => '2000']);
        $secondSet = create('App\Set', ['num_parts' => '500']);

        $response = $this->get(route('api.lego.sets', ['maxpieces' => 1000]));

        $this->assertTrue(checkNameExists($response, $secondSet->name));
        $this->assertFalse(checkNameExists($response, $firstSet->name));
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

        ThemeLabel::create(['theme_id' => $childTheme->id, 'parents_label' => $topParentTheme->name.' / '.$parentTheme->name, 'theme_label' => $topParentTheme->name.' / '.$parentTheme->name.' / '.$childTheme->name]);

        create('App\Set', ['theme_id' => $childTheme->id]);

        $response = $this->get(route('api.lego.sets'));

        $this->assertEquals($response->getData()->data[0]->theme_label, 'top parent theme / parent theme / child theme');
    }

    /** @test */
    public function an_authorized_user_can_access_the_sets_image_url()
    {
        $this->signIn();

        $set = create('App\Set');

        $set->addImageUrl('http://www.example.com');

        $response = $this->get(route('api.lego.sets'));

        $data = $response->getData()->data[0];

        $this->assertEquals($set->setImageUrl->first()->image_url, $data->image_url);
    }
}
