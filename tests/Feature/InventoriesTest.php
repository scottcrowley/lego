<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InventoriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authorized_user_can_view_all_inventories()
    {
        $this->signIn();

        $inventories = create('App\Inventory', [], 4);

        $response = $this->get(route('api.lego.inventories'));

        $data = $response->getData()->data;

        $this->assertCount(4, $data);
    }
}
