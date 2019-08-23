<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InventoryPartsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_view_all_parts_related_to_an_inventory()
    {
        $this->signIn();

        $inventory = create('App\Inventory');

        $inventoryPart1 = create('App\InventoryPart', ['inventory_id' => $inventory->id]);
        $inventoryPart2 = create('App\InventoryPart', ['inventory_id' => $inventory->id]);

        $response = $this->get(route('api.lego.inventory_parts', $inventory->id));

        $data = $response->getData()->data;

        $this->assertCount(2, $data);

        $this->assertEquals($inventoryPart1->part_num, $data[0]->part_num);
        $this->assertEquals($inventoryPart2->part_num, $data[1]->part_num);
    }

    /** @test */
    public function an_authorized_user_can_access_the_associated_parts_name()
    {
        $this->signIn();

        $inventory = create('App\Inventory');
        $part = create('App\Part');

        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $part->part_num]);

        $response = $this->get(route('api.lego.inventory_parts', $inventory->id));

        $data = $response->getData()->data[0];

        $this->assertEquals($part->name, $data->name);
    }

    /** @test */
    public function an_authorized_user_can_access_the_associated_parts_category_label()
    {
        $this->signIn();

        $inventory = create('App\Inventory');
        $category = create('App\PartCategory');
        $part = create('App\Part', ['part_category_id' => $category->id]);

        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $part->part_num]);

        $response = $this->get(route('api.lego.inventory_parts', $inventory->id));

        $data = $response->getData()->data[0];

        $this->assertEquals($category->name, $data->category_label);
    }

    /** @test */
    public function an_authorized_user_can_access_the_associated_parts_image_url()
    {
        $this->signIn();

        $inventory = create('App\Inventory');
        $part = create('App\Part');

        $part->addImageUrl('http://www.example.com');

        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $part->part_num]);

        $response = $this->get(route('api.lego.inventory_parts', $inventory->id));

        $data = $response->getData()->data[0];

        $this->assertEquals($part->partImageUrl->first()->image_url, $data->image_url);
    }

    /** @test */
    public function an_authorized_user_can_access_the_associated_parts_color_name()
    {
        $this->signIn();

        $inventory = create('App\Inventory');
        $color = create('App\Color');

        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'color_id' => $color->id]);

        $response = $this->get(route('api.lego.inventory_parts', $inventory->id));

        $data = $response->getData()->data[0];

        $this->assertEquals($color->name, $data->color_name);
    }

    /** @test */
    public function an_authorized_user_can_access_the_associated_parts_ldraw_image_url()
    {
        $this->signIn();

        $inventory = create('App\Inventory');
        $color = create('App\Color');
        $part = create('App\Part');

        $inventoryPart = create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $part->part_num, 'color_id' => $color->id]);

        $response = $this->get(route('api.lego.inventory_parts', $inventory->id));

        $data = $response->getData()->data[0];

        $url = str_replace(['{color_id}', '{part_num}'], [$color->id, $part->part_num], $inventoryPart->getLdrawBaseUrl());

        $this->assertEquals($url, $data->ldraw_image_url);
    }

    /** @test */
    public function an_authorized_user_can_access_the_associated_parts_storage_location()
    {
        $this->signIn();

        $inventory = create('App\Inventory');
        $location = create('App\StorageLocation');
        $part = create('App\Part');

        $location->addPart($part);

        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $part->part_num]);

        $response = $this->get(route('api.lego.inventory_parts', $inventory->id));

        $data = $response->getData()->data[0];

        $this->assertEquals($location->location_name, $data->location_name);
    }
}
