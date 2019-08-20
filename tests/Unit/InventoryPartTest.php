<?php

namespace Tests\Unit;

use Tests\TestCase;
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
}
