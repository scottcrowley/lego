<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\InventoryPart;
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

    /** @test */
    public function it_can_access_details_about_stickered_parts()
    {
        $this->signIn();
        $inventoryPart = create('App\InventoryPart');
        create('App\StickeredPart', ['inventory_id' => $inventoryPart->inventory_id, 'part_num' => $inventoryPart->part_num, 'color_id' => $inventoryPart->color_id]);

        $this->assertEquals($inventoryPart->part_num, $inventoryPart->getStickeredParts()->first()->part_num);
    }

    /** @test */
    public function it_can_access_the_stickered_parts_count_with_the_same_inventory_id_part_num_and_color_id()
    {
        $this->signIn();
        $inventoryPart = create('App\InventoryPart');

        $inventoryPart->addStickeredPart();
        $inventoryPart->addStickeredPart();

        $this->assertEquals(2, $inventoryPart->stickered_parts_count);
    }

    /** @test */
    public function it_can_only_access_stickered_parts_with_the_same_inventory_id_part_num_and_color_id()
    {
        $this->signIn();
        $inventoryPart = create('App\InventoryPart');
        $inventoryPart2 = create('App\InventoryPart', ['inventory_id' => $inventoryPart->inventory_id, 'color_id' => $inventoryPart->color_id]);
        $inventoryPart3 = create('App\InventoryPart');

        $stickeredPart = create('App\StickeredPart', ['inventory_id' => $inventoryPart->inventory_id, 'part_num' => $inventoryPart->part_num, 'color_id' => $inventoryPart->color_id]);
        $stickeredPart2 = create('App\StickeredPart', ['inventory_id' => $inventoryPart->inventory_id, 'part_num' => $inventoryPart2->part_num, 'color_id' => $inventoryPart2->color_id]);
        $stickeredPart3 = create('App\StickeredPart', ['inventory_id' => $inventoryPart3->inventory_id, 'part_num' => $inventoryPart3->part_num, 'color_id' => $inventoryPart3->color_id]);
        $stickeredPart4 = create('App\StickeredPart', ['inventory_id' => $inventoryPart->inventory_id, 'part_num' => $inventoryPart->part_num, 'color_id' => $inventoryPart->color_id]);

        $allStickeredParts = $inventoryPart->getStickeredParts();
        $this->assertEquals(2, $inventoryPart->stickered_parts_count);
        $this->assertEquals($inventoryPart->part_num, $allStickeredParts[0]->part_num);
        $this->assertEquals($inventoryPart->part_num, $allStickeredParts[1]->part_num);
    }

    /** @test */
    public function it_can_add_a_new_stickered_part()
    {
        $this->signIn();
        $inventoryPart = create('App\InventoryPart');

        $stickeredPart = $inventoryPart->addStickeredPart();

        $this->assertCount(1, $inventoryPart->stickeredParts);
        $this->assertEquals($stickeredPart->part_num, $inventoryPart->stickeredParts->first()->part_num);
    }

    /** @test */
    public function it_can_remove_a_stickered_part()
    {
        $this->signIn();
        $stickeredPart = create('App\StickeredPart');
        $inventoryPart = InventoryPart::where([
            ['part_num', '=', $stickeredPart->part_num],
            ['color_id', '=', $stickeredPart->color_id],
        ])->first();

        $stickeredParts = $inventoryPart->removeStickeredPart();

        $this->assertCount(0, $stickeredParts);
    }

    /** @test */
    public function it_can_add_a_new_stickered_part_through_api()
    {
        $this->signIn();
        $inventoryPart = create('App\InventoryPart');

        $response = ($this->post(route('api.lego.inventory_parts.stickered.add', $inventoryPart->inventory_id), [
            'part_num' => $inventoryPart->part_num,
            'color_id' => $inventoryPart->color_id,
        ]))->getData();

        $this->assertDatabaseHas('stickered_parts', [
            'inventory_id' => $inventoryPart->inventory_id,
            'part_num' => $inventoryPart->part_num,
            'color_id' => $inventoryPart->color_id,
        ]);
        $this->assertEquals($inventoryPart->part_num, $response[0]->part_num);
        $this->assertCount(1, $response);
    }

    /** @test */
    public function it_can_remove_a_stickered_part_through_api()
    {
        $this->signIn();
        $stickeredPart = create('App\StickeredPart');
        $stickeredPart2 = create('App\StickeredPart', ['inventory_id' => $stickeredPart->inventory_id]);

        $inventoryPart = InventoryPart::where([
            ['part_num', '=', $stickeredPart->part_num],
            ['color_id', '=', $stickeredPart->color_id],
        ])->first();

        $response = ($this->delete(
            route(
                'api.lego.inventory_parts.stickered.remove',
                [$inventoryPart->inventory_id, $stickeredPart->part_num, $stickeredPart->color_id]
            )
        ))->getData();

        $this->assertCount(1, $response);
        $this->assertDatabaseMissing('stickered_parts', ['id' => $stickeredPart->id]);
    }
}
