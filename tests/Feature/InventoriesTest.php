<?php

namespace Tests\Feature;

use App\ThemeLabel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InventoriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authorized_user_can_retrieve_all_inventories()
    {
        $this->signIn();

        $inventories = create('App\Inventory', [], 4);

        $response = $this->get(route('api.lego.inventories'));

        $data = $response->getData()->data;

        $this->assertCount(4, $data);
    }

    /** @test */
    public function an_authorized_user_can_access_the_associated_sets_year()
    {
        $this->signIn();

        $set = create('App\Set', ['year' => 2018]);

        create('App\Inventory', ['set_num' => $set->set_num]);

        $response = $this->get(route('api.lego.inventories'));

        $data = $response->getData()->data[0];

        $this->assertEquals($set->year, $data->year);
    }

    /** @test */
    public function an_authorized_user_can_access_the_associated_sets_name()
    {
        $this->signIn();

        $set = create('App\Set', ['name' => 'The New Set']);

        create('App\Inventory', ['set_num' => $set->set_num]);

        $response = $this->get(route('api.lego.inventories'));

        $data = $response->getData()->data[0];

        $this->assertEquals($set->name, $data->name);
    }

    /** @test */
    public function an_authorized_user_can_access_the_associated_sets_image_url()
    {
        $this->signIn();

        $set = create('App\Set');

        $set->addImageUrl('http://www.example.com');

        create('App\Inventory', ['set_num' => $set->set_num]);

        $response = $this->get(route('api.lego.inventories'));

        $data = $response->getData()->data[0];

        $this->assertEquals($set->setImageUrl->first()->image_url, $data->image_url);
    }

    /** @test */
    public function an_authorized_user_can_access_the_associated_sets_num_parts()
    {
        $this->signIn();

        $set = create('App\Set');

        create('App\Inventory', ['set_num' => $set->set_num]);

        $response = $this->get(route('api.lego.inventories'));

        $data = $response->getData()->data[0];

        $this->assertEquals($set->num_parts, $data->num_parts);
    }

    /** @test */
    public function an_authorized_user_can_access_the_associated_sets_theme_label()
    {
        $this->signIn();

        $parentTheme = create('App\Theme', ['name' => 'The Parent Theme']);

        $theme = create('App\Theme', ['name' => 'The New Theme', 'parent_id' => $parentTheme->id]);

        ThemeLabel::create(['theme_id' => $theme->id, 'parents_label' => $parentTheme->name, 'theme_label' => $parentTheme->name.' / '.$theme->name]);

        $set = create('App\Set', ['theme_id' => $theme->id]);

        create('App\Inventory', ['set_num' => $set->set_num]);

        $response = $this->get(route('api.lego.inventories'));

        $data = $response->getData()->data[0];

        $this->assertEquals($set->theme->theme_label, $data->theme_label);
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_set_num()
    {
        $this->signIn();

        $set = create('App\Set');
        $firstInventory = create('App\Inventory');
        $secondInventory = create('App\Inventory', ['set_num' => $set->set_num]);

        $response = $this->get(route('api.lego.inventories', ['set_num' => $set->set_num]));

        $this->assertTrue(checkNameExists($response, $secondInventory->name));
        $this->assertFalse(checkNameExists($response, $firstInventory->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_set_name()
    {
        $this->signIn();

        $set = create('App\Set');
        $firstInventory = create('App\Inventory');
        $secondInventory = create('App\Inventory', ['set_num' => $set->set_num]);

        $response = $this->get(route('api.lego.inventories', ['name' => $set->name]));

        $this->assertTrue(checkNameExists($response, $secondInventory->name));
        $this->assertFalse(checkNameExists($response, $firstInventory->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_theme_id()
    {
        $this->signIn();

        $theme = create('App\Theme');
        $firstSet = create('App\Set');
        $secondSet = create('App\Set', ['theme_id' => $theme->id]);
        $firstInventory = create('App\Inventory', ['set_num' => $firstSet->set_num]);
        $secondInventory = create('App\Inventory', ['set_num' => $secondSet->set_num]);

        $response = $this->get(route('api.lego.inventories', ['theme_id' => $theme->id]));

        $this->assertTrue(checkNameExists($response, $secondInventory->name));
        $this->assertFalse(checkNameExists($response, $firstInventory->name));
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
        $firstInventory = create('App\Inventory', ['set_num' => $firstSet->set_num]);
        $secondInventory = create('App\Inventory', ['set_num' => $secondSet->set_num]);

        $response = $this->get(route('api.lego.inventories', ['theme_label' => $secondTheme->name]));

        $this->assertTrue(checkNameExists($response, $secondInventory->name));
        $this->assertFalse(checkNameExists($response, $firstInventory->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_exact_set_year()
    {
        $this->signIn();

        $firstSet = create('App\Set', ['year' => '2010']);
        $secondSet = create('App\Set', ['year' => '2004']);
        $firstInventory = create('App\Inventory', ['set_num' => $firstSet->set_num]);
        $secondInventory = create('App\Inventory', ['set_num' => $secondSet->set_num]);

        $response = $this->get(route('api.lego.inventories', ['year' => '2004']));

        $this->assertTrue(checkNameExists($response, $secondInventory->name));
        $this->assertFalse(checkNameExists($response, $firstInventory->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_after_a_given_set_year()
    {
        $this->signIn();

        $firstSet = create('App\Set', ['year' => '2004']);
        $secondSet = create('App\Set', ['year' => '2006']);
        $firstInventory = create('App\Inventory', ['set_num' => $firstSet->set_num]);
        $secondInventory = create('App\Inventory', ['set_num' => $secondSet->set_num]);

        $response = $this->get(route('api.lego.inventories', ['minyear' => '2005']));

        $this->assertTrue(checkNameExists($response, $secondInventory->name));
        $this->assertFalse(checkNameExists($response, $firstInventory->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_before_a_given_set_year()
    {
        $this->signIn();

        $firstSet = create('App\Set', ['year' => '2006']);
        $secondSet = create('App\Set', ['year' => '2004']);
        $firstInventory = create('App\Inventory', ['set_num' => $firstSet->set_num]);
        $secondInventory = create('App\Inventory', ['set_num' => $secondSet->set_num]);

        $response = $this->get(route('api.lego.inventories', ['maxyear' => '2005']));

        $this->assertTrue(checkNameExists($response, $secondInventory->name));
        $this->assertFalse(checkNameExists($response, $firstInventory->name));
    }

    /** @test */
    public function an_authenticated_user_can_sort_results_by_set_name()
    {
        $this->signIn();

        $setC = create('App\Set', ['name' => 'C Set']);
        $setA = create('App\Set', ['name' => 'A Set']);
        $setB = create('App\Set', ['name' => 'B Set']);
        create('App\Inventory', ['set_num' => $setC->set_num]);
        create('App\Inventory', ['set_num' => $setA->set_num]);
        create('App\Inventory', ['set_num' => $setB->set_num]);

        $this->get(route('api.lego.inventories', ['sort' => 'name']))
            ->assertSeeInOrder([$setA->name, $setB->name, $setC->name]);
    }

    /** @test */
    public function an_authenticated_user_can_sort_results_by_set_name_in_descending_order()
    {
        $this->signIn();

        $setC = create('App\Set', ['name' => 'C Set']);
        $setA = create('App\Set', ['name' => 'A Set']);
        $setB = create('App\Set', ['name' => 'B Set']);
        create('App\Inventory', ['set_num' => $setC->set_num]);
        create('App\Inventory', ['set_num' => $setA->set_num]);
        create('App\Inventory', ['set_num' => $setB->set_num]);

        $this->get(route('api.lego.sets', ['sort' => '-name']))
            ->assertSeeInOrder([$setC->name, $setB->name, $setA->name]);
    }
}
