<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StorageLocationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_requires_a_name()
    {
        $this->signIn();

        $location = makeRaw('App\StorageLocation', ['name' => '']);

        $this->post(route('storage.locations.store'), $location)
            ->assertSessionHasErrors('name');

        $location = createRaw('App\StorageLocation');

        $location['name'] = '';

        $this->patch(route('storage.locations.update', $location['id']), $location)
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_requires_a_unique_name()
    {
        $this->signIn();

        $first_location = createRaw('App\StorageLocation');
        $new_location = makeRaw('App\StorageLocation');

        $new_location['name'] = $first_location['name'];

        $this->post(route('storage.locations.store'), $new_location)
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_requires_a_description()
    {
        $this->signIn();

        $location = makeRaw('App\StorageLocation', ['description' => '']);

        $this->post(route('storage.locations.store'), $location)
            ->assertSessionHasErrors('description');

        $location = createRaw('App\StorageLocation');

        $location['description'] = '';

        $this->patch(route('storage.locations.update', $location['id']), $location)
            ->assertSessionHasErrors('description');
    }

    /** @test */
    public function it_requires_a_valid_storage_type()
    {
        $this->signIn();

        $location = makeRaw('App\StorageLocation', ['storage_type_id' => 0]);

        $this->post(route('storage.locations.store'), $location)
            ->assertSessionHasErrors('storage_type_id');

        $location = createRaw('App\StorageLocation');

        $location['storage_type_id'] = 0;

        $this->patch(route('storage.locations.update', $location['id']), $location)
            ->assertSessionHasErrors('storage_type_id');
    }

    /** @test */
    public function it_can_get_details_on_associated_storage_type()
    {
        $this->signIn();

        $type = create('App\StorageType');

        $location = create('App\StorageLocation', ['storage_type_id' => $type->id]);

        $this->assertEquals($type->name, $location->type->name);
    }

    /** @test */
    public function it_can_access_details_about_associated_parts()
    {
        $this->signIn();

        $location = create('App\StorageLocation');

        $part = create('App\UserPart');

        $location->togglePart($part);

        $this->assertEquals($part->part_num, $location->fresh()->parts->first()->part_num);
    }

    /** @test */
    public function it_can_toggle_an_associated_part()
    {
        $this->signIn();

        $location = create('App\StorageLocation');

        $part = create('App\UserPart');

        $location->togglePart($part);

        $this->assertEquals($part->part_num, $location->fresh()->parts[0]->part_num);

        $location->togglePart($part);

        $this->assertEquals(0, $location->fresh()->parts->count());
    }

    /** @test */
    public function it_can_toggle_an_associated_part_through_api()
    {
        $this->signIn();

        $location = create('App\StorageLocation');

        $part = create('App\UserPart');

        $part = ($this->get(route('api.users.storage.locations.parts.toggle', [$location->id, $part->part_num])))->getData();
        $this->assertEquals($location->name, $part->location->name);

        $response = ($this->get(route('api.users.storage.locations.parts', $location->id)))->getData()->data[0];
        $this->assertEquals($part->part_num, $response->part_num);

        $part = ($this->get(route('api.users.storage.locations.parts.toggle', [$location->id, $part->part_num])))->getData();
        $this->assertNull($part->location);

        $response = ($this->get(route('api.users.storage.locations.parts', $location->id)))->getData()->data;
        $this->assertCount(0, $response);
    }

    /** @test */
    public function it_can_retrieve_all_parts_through_api()
    {
        $this->signIn();

        $location = create('App\StorageLocation');
        $color1 = create('App\Color');
        $color2 = create('App\Color');

        $part = create('App\Part');

        $userPart = create('App\UserPart', ['part_num' => $part->part_num, 'color_id' => $color1->id]);
        create('App\UserPart', ['part_num' => $part->part_num, 'color_id' => $color2->id]);

        $location->togglePart($userPart);

        $response = ($this->get(route('api.users.storage.locations.parts', $location->id)))->getData()->data;
        $this->assertCount(1, $response);

        $response = ($this->get(route('api.users.storage.locations.parts.individual', $location->id)))->getData()->data;
        $this->assertCount(2, $response);
    }
}
