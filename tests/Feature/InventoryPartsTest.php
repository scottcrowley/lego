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
}
