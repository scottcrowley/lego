<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InventoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_access_all_related_inventory_parts()
    {
        $this->signIn();

        $inventory = create('App\Inventory');

        $inventoryPart = create('App\InventoryPart', ['inventory_id' => $inventory->id]);

        $inventoryParts = $inventory->fresh()->parts;

        $this->assertCount(1, $inventoryParts);

        $this->assertEquals($inventoryPart->part_num, $inventoryParts->first()->part_num);
    }

    /** @test */
    public function it_can_access_details_about_its_associtated_set()
    {
        $this->signIn();

        $set = create('App\Set');

        $inventory = create('App\Inventory', ['set_num' => $set->set_num]);

        $this->assertEquals($set->name, $inventory->set->name);
    }

    /** @test */
    public function it_can_retrieve_details_about_all_stickered_parts()
    {
        $this->signIn();

        $inventory = create('App\Inventory');
        $stickeredPart = create('App\StickeredPart', ['inventory_id' => $inventory->id]);

        $this->assertEquals($stickeredPart->part_num, $inventory->stickeredParts->first()->part_num);
    }
}
