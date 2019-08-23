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
    public function it_can_have_access_to_its_storage_location_name_and_details()
    {
        $this->signIn();

        $location = create('App\StorageLocation');

        $part = create('App\Part');

        $location->addPart($part);

        $this->assertEquals($part->location->name, $location->name);
    }

    /** @test */
    public function it_can_add_an_image_url()
    {
        $this->signIn();

        $part = create('App\Part');

        $part->addImageUrl('http://www.example.com');

        $this->assertEquals($part->partImageUrl->first()->image_url, 'http://www.example.com');
    }
}
