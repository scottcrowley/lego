<?php

namespace Tests\Feature;

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

        $set = create('App\Set', ['theme_id' => $theme->id]);

        create('App\Inventory', ['set_num' => $set->set_num]);

        $response = $this->get(route('api.lego.inventories'));

        $data = $response->getData()->data[0];

        $this->assertEquals($set->theme->theme_label, $data->theme_label);
    }
}
