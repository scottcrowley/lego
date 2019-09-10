<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserPartTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_have_access_to_part_details()
    {
        $this->signIn();

        $part = create('App\Part');

        $userPart = create('App\UserPart', ['part_num' => $part->part_num]);

        $this->assertEquals($part->name, $userPart->name);
    }

    /** @test */
    public function it_can_have_access_to_part_category_details()
    {
        $this->signIn();

        $category = create('App\PartCategory');

        $part = create('App\Part', ['part_category_id' => $category->id]);

        $userPart = create('App\UserPart', ['part_num' => $part->part_num]);

        $this->assertEquals($category->name, $userPart->category_label);
        $this->assertEquals($part->part_category_id, $userPart->part->part_category_id);
    }

    /** @test */
    public function it_can_have_access_to_part_color_details()
    {
        $this->signIn();

        $category = create('App\PartCategory');

        $part = create('App\Part', ['part_category_id' => $category->id]);

        $color = create('App\Color');

        $userPart = create('App\UserPart', ['part_num' => $part->part_num, 'color_id' => $color->id]);

        $this->assertEquals($color->name, $userPart->color_name);
        $this->assertEquals($color->id, $userPart->color->id);
    }

    /** @test */
    public function it_can_have_access_to_part_image_url_details()
    {
        $this->signIn();

        $part = create('App\Part');

        $part->addImageUrl('http://www.example.com');

        $userPart = create('App\UserPart', ['part_num' => $part->part_num]);

        $this->assertEquals('http://www.example.com', $userPart->image_url);
    }

    /** @test */
    public function it_can_have_access_to_part_ldraw_image_url_details()
    {
        $this->signIn();

        $part = create('App\Part');

        $color = create('App\Color');

        $userPart = create('App\UserPart', ['part_num' => $part->part_num, 'color_id' => $color->id]);

        $ldrawBaseUrl = $userPart->getLdrawBaseUrl();

        $ldrawUrl = str_replace(['{color_id}', '{part_num}'], [$color->id, $part->part_num], $ldrawBaseUrl);

        $this->assertEquals($ldrawUrl, $userPart->ldraw_image_url);
    }

    /** @test */
    public function it_can_have_access_to_part_storage_location_details()
    {
        $this->signIn();

        $location = create('App\StorageLocation');

        $part = create('App\UserPart');

        $location->togglePart($part);

        $userPart = create('App\UserPart', ['part_num' => $part->part_num]);

        $this->assertEquals($location->name, $userPart->location->name);
    }

    /** @test */
    public function it_can_have_access_to_part_storage_location_name()
    {
        $this->signIn();

        $location = create('App\StorageLocation');

        $part = create('App\UserPart');

        $location->togglePart($part);

        $userPart = create('App\UserPart', ['part_num' => $part->part_num]);

        $this->assertEquals($location->location_name, $userPart->location_name);
    }

    /** @test */
    public function it_can_get_a_default_location_name_when_part_location_is_null()
    {
        $this->signIn();

        $part = create('App\UserPart');

        $userPart = create('App\UserPart', ['part_num' => $part->part_num]);

        $this->assertEquals('None', $userPart->location_name);
    }

    /** @test */
    public function it_can_retrieve_all_parts_through_api()
    {
        $this->signIn();

        $color1 = create('App\Color');
        $color2 = create('App\Color');

        $part = create('App\Part');

        create('App\UserPart', ['part_num' => $part->part_num, 'color_id' => $color1->id]);
        create('App\UserPart', ['part_num' => $part->part_num, 'color_id' => $color2->id]);

        $response = ($this->get(route('api.users.parts.all')))->getData()->data;
        $this->assertCount(1, $response);

        $response = ($this->get(route('api.users.parts.individual')))->getData()->data;
        $this->assertCount(2, $response);
    }

    /** @test */
    public function it_can_be_assigned_to_a_storage_location()
    {
        $this->signIn();

        $location = create('App\StorageLocation');

        $userPart = create('App\UserPart');

        $userPart->toggleLocation($location);

        $this->assertEquals($location->fresh()->parts->first()->name, $userPart->name);
        $this->assertEquals($userPart->fresh()->storageLocation->name, $location->name);
    }
}
