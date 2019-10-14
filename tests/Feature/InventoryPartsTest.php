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
        $userPart = create('App\UserPart', ['part_num' => $part->part_num]);

        $location->togglePart($userPart);

        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $part->part_num]);

        $response = $this->get(route('api.lego.inventory_parts', $inventory->id));

        $data = $response->getData()->data[0];

        $this->assertEquals($location->location_name, $data->location_name);
    }

    /** @test */
    public function an_authenticated_user_can_sort_result_by_part_name()
    {
        $this->signIn();

        $inventory = create('App\Inventory');
        $partC = create('App\Part', ['name' => 'Part C']);
        $partA = create('App\Part', ['name' => 'Part A']);
        $partB = create('App\Part', ['name' => 'Part B']);

        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $partC->part_num]);
        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $partA->part_num]);
        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $partB->part_num]);

        $this->get(route('api.lego.inventory_parts', ['inventory' => $inventory->id, 'sort' => 'name']))
            ->assertSeeInOrder([$partA->name, $partB->name, $partC->name]);
    }

    /** @test */
    public function an_authenticated_user_can_sort_result_by_set_name_in_descending_order()
    {
        $this->signIn();

        $inventory = create('App\Inventory');
        $partC = create('App\Part', ['name' => 'Part C']);
        $partA = create('App\Part', ['name' => 'Part A']);
        $partB = create('App\Part', ['name' => 'Part B']);

        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $partC->part_num]);
        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $partA->part_num]);
        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $partB->part_num]);

        $this->get(route('api.lego.inventory_parts', ['inventory' => $inventory->id, 'sort' => '-name']))
            ->assertSeeInOrder([$partC->name, $partB->name, $partA->name]);
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_part_name()
    {
        $this->signIn();

        $inventory = create('App\Inventory');
        $firstPart = create('App\Part', ['name' => 'First Part']);
        $secondPart = create('App\Part', ['name' => 'Second Part']);
        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $firstPart->part_num]);
        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $secondPart->part_num]);

        $response = $this->get(route('api.lego.inventory_parts', ['inventory' => $inventory->id, 'name' => $secondPart->name]));

        $this->assertTrue(checkNameExists($response, $secondPart->name));
        $this->assertFalse(checkNameExists($response, $firstPart->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_part_num()
    {
        $this->signIn();

        $inventory = create('App\Inventory');
        $firstPart = create('App\Part', ['name' => 'First Part']);
        $secondPart = create('App\Part', ['name' => 'Second Part']);
        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $firstPart->part_num]);
        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $secondPart->part_num]);

        $response = $this->get(route('api.lego.inventory_parts', ['inventory' => $inventory->id, 'part_num' => $secondPart->part_num]));

        $this->assertTrue(checkNameExists($response, $secondPart->name));
        $this->assertFalse(checkNameExists($response, $firstPart->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_part_category_id()
    {
        $this->signIn();

        $inventory = create('App\Inventory');
        $category = create('App\PartCategory');
        $firstPart = create('App\Part', ['name' => 'First Part']);
        $secondPart = create('App\Part', ['name' => 'Second Part', 'part_category_id' => $category->id]);
        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $firstPart->part_num]);
        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $secondPart->part_num]);

        $response = $this->get(route('api.lego.inventory_parts', ['inventory' => $inventory->id, 'part_category_id' => $secondPart->part_category_id]));

        $this->assertTrue(checkNameExists($response, $secondPart->name));
        $this->assertFalse(checkNameExists($response, $firstPart->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_part_category_label()
    {
        $this->signIn();

        $inventory = create('App\Inventory');
        $category = create('App\PartCategory');
        $firstPart = create('App\Part', ['name' => 'First Part']);
        $secondPart = create('App\Part', ['name' => 'Second Part', 'part_category_id' => $category->id]);
        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $firstPart->part_num]);
        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $secondPart->part_num]);

        $response = $this->get(route('api.lego.inventory_parts', ['inventory' => $inventory->id, 'category_label' => $secondPart->category_label]));

        $this->assertTrue(checkNameExists($response, $secondPart->name));
        $this->assertFalse(checkNameExists($response, $firstPart->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_color_id()
    {
        $this->signIn();

        $inventory = create('App\Inventory');
        $firstColor = create('App\Color', ['name' => 'First Color']);
        $secondColor = create('App\Color', ['name' => 'Second Color']);
        $firstPart = create('App\Part', ['name' => 'First Part']);
        $secondPart = create('App\Part', ['name' => 'Second Part']);
        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $firstPart->part_num, 'color_id' => $firstColor->id]);
        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $secondPart->part_num, 'color_id' => $secondColor->id]);

        $response = $this->get(route('api.lego.inventory_parts', ['inventory' => $inventory->id, 'color_id' => $secondColor->id]));

        $this->assertTrue(checkNameExists($response, $secondPart->name));
        $this->assertFalse(checkNameExists($response, $firstPart->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_color_name()
    {
        $this->signIn();

        $inventory = create('App\Inventory');
        $firstColor = create('App\Color', ['name' => 'First Color']);
        $secondColor = create('App\Color', ['name' => 'Second Color']);
        $firstPart = create('App\Part', ['name' => 'First Part']);
        $secondPart = create('App\Part', ['name' => 'Second Part']);
        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $firstPart->part_num, 'color_id' => $firstColor->id]);
        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $secondPart->part_num, 'color_id' => $secondColor->id]);

        $response = $this->get(route('api.lego.inventory_parts', ['inventory' => $inventory->id, 'color' => $secondColor->name]));

        $this->assertTrue(checkNameExists($response, $secondPart->name));
        $this->assertFalse(checkNameExists($response, $firstPart->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_location_name()
    {
        $this->signIn();

        $inventory = create('App\Inventory');
        $location = create('App\StorageLocation');
        $firstPart = create('App\Part', ['name' => 'First Part']);
        $secondPart = create('App\Part', ['name' => 'Second Part']);
        $userPart = create('App\UserPart', ['part_num' => $secondPart->part_num]);
        $location->togglePart($userPart);
        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $firstPart->part_num]);
        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $secondPart->part_num]);

        $response = $this->get(route('api.lego.inventory_parts', ['inventory' => $inventory->id, 'location_name' => $location->location_name]));

        $this->assertTrue(checkNameExists($response, $secondPart->name));
        $this->assertFalse(checkNameExists($response, $firstPart->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_excluding_spare_pieces()
    {
        $this->signIn();

        $inventory = create('App\Inventory');
        $firstPart = create('App\Part', ['name' => 'First Part']);
        $secondPart = create('App\Part', ['name' => 'Second Part']);
        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $firstPart->part_num, 'is_spare' => 't']);
        create('App\InventoryPart', ['inventory_id' => $inventory->id, 'part_num' => $secondPart->part_num]);

        $response = $this->get(route('api.lego.inventory_parts', ['inventory' => $inventory->id, 'exclude_spare' => 1]));

        $this->assertTrue(checkNameExists($response, $secondPart->name));
        $this->assertFalse(checkNameExists($response, $firstPart->name));
    }
}
