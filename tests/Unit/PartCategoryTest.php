<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PartCategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_requires_a_unique_name()
    {
        $this->signIn();

        $category = makeRaw('App\PartCategory', ['name' => '']);

        $this->post(route('part_categories.store'), $category)
            ->assertSessionHasErrors('name');

        $existingCategory = createRaw('App\PartCategory');

        $category['name'] = $existingCategory['name'];

        $this->post(route('part_categories.store'), $category)
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_can_access_details_about_its_storage_location()
    {
        $this->signIn();

        $location = create('App\StorageLocation');
        
        $partCategory = create('App\PartCategory');

        $location->addPartCategory($partCategory);

        $this->assertEquals($partCategory->fresh()->storageLocation[0]->name, $location->name);
    }
}
