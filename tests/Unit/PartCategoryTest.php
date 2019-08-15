<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PartCategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_access_details_about_its_storage_location()
    {
        $this->signIn();

        $location = create('App\StorageLocation');

        $partCategory = create('App\PartCategory');

        $location->addPartCategory($partCategory);

        $this->assertEquals($partCategory->fresh()->storageLocation[0]->name, $location->name);
    }

    /** @test */
    public function it_can_access_details_about_all_related_parts()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $partCategory = create('App\PartCategory');

        $parts = create('App\Part', ['part_category_id' => $partCategory->id], 4);

        $this->assertCount(4, $partCategory->fresh()->parts);

        $this->assertEquals(4, $partCategory->part_count);
    }
}
