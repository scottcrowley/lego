<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PartCategoryTest extends TestCase
{
    use RefreshDatabase;

    // /** @test */
    // public function it_requires_a_unique_name()
    // {
    //     $this->signIn();

    //     $category = makeRaw('App\PartCategory', ['name' => '']);

    //     $this->post(route('part_categories.store'), $category)
    //         ->assertSessionHasErrors('name');

    //     $category = createRaw('App\PartCategory');

    //     $newCategory = $category;

    //     $this->post(route('part_categories.store'), $newCategory)
    //         ->assertSessionHasErrors('name');
    // }

    // /** @test */
    // public function it_can_have_access_to_its_storage_location_details()
    // {
    //     $this->signIn();

    //     $location = create('App\StorageLocation');

    //     $category = create('App\PartCategory', ['storage_location_id' => $location->id]);

    //     $this->assertEquals($category->fresh()->storageLocation->name, $location->name);
    // }
}
