<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PartTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function it_can_have_access_to_part_category_details()
    {
        $this->signIn();

        $category = create('App\PartCategory');

        $part = create('App\Part', ['part_category_id' => $category->id]);

        $this->assertEquals($category->name, $part->category->name);
    }

    /** @test */
    public function it_can_have_access_to_part_category_storage_location_name_and_details()
    {
        $this->signIn();

        $location = create('App\StorageLocation');

        $partCategory = create('App\PartCategory');

        $location->addPartCategory($partCategory);

        $part = create('App\Part', ['part_category_id' => $partCategory->id]);
        
        $this->assertEquals($part->storageLocation->name, $location->name);
    }
}
