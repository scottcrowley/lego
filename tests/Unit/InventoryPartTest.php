<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InventoryPartTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_access_details_about_each_associated_part()
    {
        $this->signIn();

        $part = create('App\Part');

        $inventoryPart = create('App\InventoryPart', ['part_num' => $part->part_num]);

        $this->assertEquals($part->name, $inventoryPart->part->name);
    }

    /** @test */
    public function it_can_access_details_about_each_associated_part_color()
    {
        $this->signIn();

        $color = create('App\Color');

        $inventoryPart = create('App\InventoryPart', ['color_id' => $color->id]);

        $this->assertEquals($color->name, $inventoryPart->color->name);
    }

    /** @test */
    public function it_can_access_its_inventory()
    {
        $this->signIn();

        $inventory = create('App\Inventory');

        $inventoryPart = create('App\InventoryPart', ['inventory_id' => $inventory->id]);

        $this->assertEquals($inventory->toArray(), $inventoryPart->inventory->toArray());
    }

    /** @test */
    public function it_can_determine_how_many_parts_are_owned_for_each_inventory_part_and_color_combination()
    {
        $this->signIn();

        $userPart = create('App\UserPart', ['quantity' => 20]);
        $inventoryPart = create('App\InventoryPart', ['part_num' => $userPart->part_num, 'color_id' => $userPart->color_id]);

        $this->assertEquals(20, $inventoryPart->getTotalOwned());

        $this->assertEquals($inventoryPart->quantity.' / 20', $inventoryPart->quantity_label);
    }

    /** @test */
    public function it_can_cache_an_inventory_part_selection_through_api()
    {
        $this->signIn();

        $inventoryPart = create('App\InventoryPart');

        $part = $inventoryPart->only(['inventory_id', 'part_num', 'color_id', 'is_spare']);
        $part['selected'] = true;
        $key = $part['part_num'].'-'.$part['color_id'].'-'.$part['is_spare'];

        Cache::shouldReceive('get')
            ->once()
            ->with($part['inventory_id'], [])
            ->andReturn([]);

        Cache::shouldReceive('put')
            ->once()
            ->with($part['inventory_id'], [$key => true], \Mockery::any());

        $response = $this->post(route('api.lego.inventory_parts.selection.update'), $part);

        $this->assertTrue($response->getData()->$key);

        Cache::shouldReceive('get')
            ->once()
            ->with($part['inventory_id'], [])
            ->andReturn([$key => true]);

        $response = $this->get(route('api.lego.inventory_parts.selection.get', $part['inventory_id']));

        $this->assertTrue($response->getData()->$key);

        $part['selected'] = false;

        Cache::shouldReceive('get')
            ->once()
            ->with($part['inventory_id'], [])
            ->andReturn([$key => true]);

        Cache::shouldReceive('put')
            ->once()
            ->with($part['inventory_id'], [], \Mockery::any());

        $response = $this->post(route('api.lego.inventory_parts.selection.update'), $part);

        $this->assertEmpty($response->getData());
    }
}
