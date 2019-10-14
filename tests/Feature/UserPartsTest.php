<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserPartsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authorized_user_can_retrieve_all_their_owned_parts()
    {
        $this->signIn();

        $color1 = create('App\Color');
        $color2 = create('App\Color');

        $part = create('App\Part');

        create('App\UserPart', ['part_num' => $part->part_num, 'color_id' => $color1]);
        create('App\UserPart', ['part_num' => $part->part_num, 'color_id' => $color2]);

        $response = $this->get(route('api.users.parts.all'));

        $data = $response->getData()->data;

        $this->assertCount(1, $data);
        $this->assertEquals($part->name, $data[0]->name);
    }

    /** @test */
    public function an_authorized_user_can_retrieve_all_their_owned_individual_parts()
    {
        $this->signIn();

        $color1 = create('App\Color');
        $color2 = create('App\Color');

        $part = create('App\Part');

        create('App\UserPart', ['part_num' => $part->part_num, 'color_id' => $color1]);
        create('App\UserPart', ['part_num' => $part->part_num, 'color_id' => $color2]);

        $response = $this->get(route('api.users.parts.individual'));

        $data = $response->getData()->data;

        $this->assertCount(2, $data);
        $this->assertEquals($part->name, $data[0]->name);
        $this->assertEquals($part->name, $data[1]->name);
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_name()
    {
        $this->signIn();

        $firstPart = create('App\Part', ['name' => 'First Part']);
        $secondPart = create('App\Part', ['name' => 'Second Part']);
        create('App\UserPart', ['part_num' => $firstPart->part_num]);
        create('App\UserPart', ['part_num' => $secondPart->part_num]);

        $response = $this->get(route('api.users.parts.all', ['name' => $secondPart->name]));

        $this->assertTrue(checkNameExists($response, $secondPart->name));
        $this->assertFalse(checkNameExists($response, $firstPart->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_part_num()
    {
        $this->signIn();

        $firstPart = create('App\Part', ['name' => 'First Part']);
        $secondPart = create('App\Part', ['name' => 'Second Part']);
        create('App\UserPart', ['part_num' => $firstPart->part_num]);
        create('App\UserPart', ['part_num' => $secondPart->part_num]);

        $response = $this->get(route('api.users.parts.all', ['part_num' => $secondPart->part_num]));

        $this->assertTrue(checkNameExists($response, $secondPart->name));
        $this->assertFalse(checkNameExists($response, $firstPart->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_part_category_id()
    {
        $this->signIn();

        $category = create('App\PartCategory');
        $firstPart = create('App\Part', ['name' => 'First Part']);
        $secondPart = create('App\Part', ['name' => 'Second Part', 'part_category_id' => $category->id]);
        create('App\UserPart', ['part_num' => $firstPart->part_num]);
        create('App\UserPart', ['part_num' => $secondPart->part_num]);

        $response = $this->get(route('api.users.parts.all', ['part_category_id' => $category->id]));

        $this->assertTrue(checkNameExists($response, $secondPart->name));
        $this->assertFalse(checkNameExists($response, $firstPart->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_part_category_name()
    {
        $this->signIn();

        $category = create('App\PartCategory');
        $firstPart = create('App\Part', ['name' => 'First Part']);
        $secondPart = create('App\Part', ['name' => 'Second Part', 'part_category_id' => $category->id]);
        create('App\UserPart', ['part_num' => $firstPart->part_num]);
        create('App\UserPart', ['part_num' => $secondPart->part_num]);

        $response = $this->get(route('api.users.parts.all', ['category_label' => $category->name]));

        $this->assertTrue(checkNameExists($response, $secondPart->name));
        $this->assertFalse(checkNameExists($response, $firstPart->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_storage_location_id()
    {
        $this->signIn();

        $location = create('App\StorageLocation');
        $firstPart = create('App\Part', ['name' => 'First Part']);
        $secondPart = create('App\Part', ['name' => 'Second Part']);
        create('App\UserPart', ['part_num' => $firstPart->part_num]);
        $userPart = create('App\UserPart', ['part_num' => $secondPart->part_num]);

        $location->togglePart($userPart);

        $response = $this->get(route('api.users.parts.all', ['location_id' => $location->id]));

        $this->assertTrue(checkNameExists($response, $secondPart->name));
        $this->assertFalse(checkNameExists($response, $firstPart->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_storage_location_name()
    {
        $this->signIn();

        $location = create('App\StorageLocation');
        $firstPart = create('App\Part', ['name' => 'First Part']);
        $secondPart = create('App\Part', ['name' => 'Second Part']);
        create('App\UserPart', ['part_num' => $firstPart->part_num]);
        $userPart = create('App\UserPart', ['part_num' => $secondPart->part_num]);

        $location->togglePart($userPart);

        $response = $this->get(route('api.users.parts.all', ['location_name' => $location->location_name]));

        $this->assertTrue(checkNameExists($response, $secondPart->name));
        $this->assertFalse(checkNameExists($response, $firstPart->name));
    }

    /** @test */
    public function an_authenticated_user_can_sort_parts_by_name()
    {
        $this->signIn();

        $partC = create('App\Part', ['name' => 'C Part']);
        $partA = create('App\Part', ['name' => 'A Part']);
        $partB = create('App\Part', ['name' => 'B Part']);
        create('App\UserPart', ['part_num' => $partC->part_num]);
        create('App\UserPart', ['part_num' => $partA->part_num]);
        create('App\UserPart', ['part_num' => $partB->part_num]);

        $this->get(route('api.users.parts.all', ['sort' => 'name']))
            ->assertSeeInOrder([$partA->name, $partB->name, $partC->name]);
    }

    /** @test */
    public function an_authenticated_user_can_sort_parts_by_name_in_descending_order()
    {
        $this->signIn();

        $partC = create('App\Part', ['name' => 'C Part']);
        $partA = create('App\Part', ['name' => 'A Part']);
        $partB = create('App\Part', ['name' => 'B Part']);
        create('App\UserPart', ['part_num' => $partC->part_num]);
        create('App\UserPart', ['part_num' => $partA->part_num]);
        create('App\UserPart', ['part_num' => $partB->part_num]);

        $this->get(route('api.users.parts.all', ['sort' => '-name']))
            ->assertSeeInOrder([$partC->name, $partB->name, $partA->name]);
    }
}
