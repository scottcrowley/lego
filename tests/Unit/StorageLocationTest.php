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

    /** @test */
    public function it_can_get_the_user_part_count_of_each_associated_part()
    {
        $this->signIn();

        $location = create('App\StorageLocation');

        $userPart = create('App\UserPart', ['quantity' => 5]);

        $location->togglePart($userPart);

        $this->assertEquals(5, $location->fresh()->part_counts[$userPart->part_num]);
    }

    /** @test */
    public function it_can_move_all_current_parts_to_a_different_location()
    {
        $this->signIn();

        $originalLocation = create('App\StorageLocation');
        $newLocation = create('App\StorageLocation');

        $userParts = create('App\UserPart', [], 5);

        $userParts->each->toggleLocation($originalLocation);

        $this->assertCount(5, $originalLocation->fresh()->parts);

        $originalLocation->moveAllPartsTo($newLocation);

        $this->assertEmpty($originalLocation->fresh()->parts);
        $this->assertCount(5, $newLocation->fresh()->parts);
    }

    /** @test */
    public function it_can_move_an_array_of_parts_to_a_different_location()
    {
        $this->signIn();

        $originalLocation = create('App\StorageLocation');
        $newLocation = create('App\StorageLocation');

        $part = create('App\UserPart');
        $userParts = create('App\UserPart', [], 5);

        $part->toggleLocation($originalLocation);
        $userParts->each->toggleLocation($originalLocation);

        $this->assertCount(6, $originalLocation->fresh()->parts);

        $originalLocation->movePartsTo([$part], $newLocation);

        $this->assertCount(5, $originalLocation->fresh()->parts);
        $this->assertCount(1, $newLocation->fresh()->parts);
        $this->assertEquals($part->name, $newLocation->parts->first()->name);
    }

    /** @test */
    public function it_can_only_move_parts_to_new_location_if_the_part_is_in_the_current_location()
    {
        $this->signIn();

        $originalLocation = create('App\StorageLocation');
        $newLocation = create('App\StorageLocation');

        $part = create('App\UserPart');
        $userParts = create('App\UserPart', [], 5);
        $partNotInOriginal = create('App\UserPart');

        $part->toggleLocation($originalLocation);
        $userParts->each->toggleLocation($originalLocation);

        $this->assertCount(6, $originalLocation->fresh()->parts);

        $originalLocation->movePartsTo([$part, $partNotInOriginal], $newLocation);

        $this->assertCount(5, $originalLocation->fresh()->parts);
        $this->assertCount(2, $newLocation->fresh()->parts);
        $this->assertEquals($part->part_num, $newLocation->parts[0]->part_num);
        $this->assertEquals($partNotInOriginal->part_num, $newLocation->parts[1]->part_num);
    }

    /** @test */
    public function it_can_move_parts_to_a_new_location_through_api()
    {
        $this->signIn();

        $originalLocation = create('App\StorageLocation');
        $newLocation = create('App\StorageLocation');

        $userParts = create('App\UserPart', [], 5);

        $userParts->each->toggleLocation($originalLocation);

        $this->assertCount(5, $originalLocation->fresh()->parts);
        $this->post(route('api.users.storage.locations.parts.move', [$originalLocation, $newLocation]), $userParts->toArray());

        $this->assertCount(0, $originalLocation->fresh()->parts);
        $this->assertCount(5, $newLocation->fresh()->parts);
    }
}
