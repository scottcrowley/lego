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
        $userPart = create('App\UserPart', ['part_num' => $part->part_num]);

        $location->togglePart($userPart);

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

    /** @test */
    public function it_can_access_its_storage_location_when_editing_storage_location_parts()
    {
        $this->signIn();

        $location = create('App\StorageLocation');

        $part = create('App\Part');
        $userPart = create('App\UserPart', ['part_num' => $part->part_num]);

        $userPart = ($this->get(route('api.users.storage.locations.parts.toggle', [$location->id, $userPart->part_num])))->getData();

        $response = $this->get(route('api.users.storage.locations.parts.edit', [$location->id, 'part_num' => $userPart->part_num]));

        $this->assertEquals($location->name, $response->getData()->data[0]->location->name);
    }

    /** @test */
    public function it_can_determine_whether_or_not_a_user_owns_the_part()
    {
        $this->signIn();

        $part = create('App\Part');

        $this->assertFalse($part->owns_part);

        create('App\UserPart', ['part_num' => $part->part_num]);

        $this->assertTrue($part->fresh()->owns_part);
    }

    /** @test */
    public function it_can_determine_the_quantity_of_user_parts_for_an_owned_part()
    {
        $this->signIn();

        $part = create('App\Part');

        $this->assertEquals(0, $part->owned_part_count);

        $userPart = create('App\UserPart', ['part_num' => $part->part_num]);

        $this->assertEquals($userPart->quantity, $part->fresh()->owned_part_count);
    }

    /** @test */
    public function it_can_access_the_location_name_of_the_part_if_it_is_owned()
    {
        $this->signIn();

        $location = create('App\StorageLocation');
        $part = create('App\Part');

        $this->assertEmpty($part->owned_part_location_name);

        $userPart = create('App\UserPart', ['part_num' => $part->part_num]);
        $location->togglePart($userPart);

        $this->assertEquals($location->location_name, $part->fresh()->owned_part_location_name);
    }
}
