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
    public function it_can_access_details_about_associated_part_categories()
    {
        $this->signIn();

        $location = create('App\StorageLocation');

        $category = create('App\PartCategory', ['storage_location_id' => $location->id]);

        $this->assertTrue($location->fresh()->partCategories->contains($category));
    }

    /** @test */
    public function it_can_access_details_about_associated_parts()
    {
        $this->signIn();

        $location = create('App\StorageLocation');

        $category = create('App\PartCategory', ['storage_location_id' => $location->id]);

        $parts = create('App\Part', ['part_category_id' => $category->id], 4);

        $partsRelation = $location->parts;

        $this->assertCount(4, $partsRelation);
    }
}
