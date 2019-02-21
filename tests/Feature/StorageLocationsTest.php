<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StorageLocationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_view_all_locations()
    {
        $this->signIn();

        $location = create('App\StorageLocation');

        $this->get(route('storage.locations.index'))
            ->assertSee($location->name);
    }
    
    /** @test */
    public function an_authenticated_user_can_view_location_create_page()
    {
        $this->signIn();

        $this->get(route('storage.locations.create'))
            ->assertSee('Add A New Storage Location');
    }

    /** @test */
    public function an_authenticated_user_can_add_a_new_location()
    {
        $this->signIn();

        $location = makeRaw('App\StorageLocation');

        $this->post(route('storage.locations.store'), $location)
            ->assertRedirect(route('storage.locations.index'));

        $this->assertDatabaseHas('storage_locations', $location);

        $this->get(route('storage.locations.index'))
            ->assertSee($location['name']);
    }
    
    /** @test */
    public function an_authenticated_user_can_view_location_edit_page()
    {
        $this->signIn();
        $location = create('App\StorageLocation');

        $this->get(route('storage.locations.edit', $location->id))
            ->assertSee('Edit ' . $location->name);
    }
}
