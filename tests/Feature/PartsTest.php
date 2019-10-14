<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PartsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_retrieve_all_parts()
    {
        $this->signIn();

        $part = create('App\Part');

        $response = $this->get(route('api.lego.parts'));

        $this->assertTrue(checkNameExists($response, $part->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_name()
    {
        $this->signIn();

        $firstPart = create('App\Part', ['name' => 'First Part']);
        $secondPart = create('App\Part', ['name' => 'Second Part']);

        $response = $this->get(route('api.lego.parts', ['name' => $secondPart->name]));

        $this->assertTrue(checkNameExists($response, $secondPart->name));
        $this->assertFalse(checkNameExists($response, $firstPart->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_part_num()
    {
        $this->signIn();

        $firstPart = create('App\Part', ['part_num' => 'First Part']);
        $secondPart = create('App\Part', ['part_num' => 'Second Part']);

        $response = $this->get(route('api.lego.parts', ['part_num' => $secondPart->part_num]));

        $this->assertTrue(checkNameExists($response, $secondPart->name));
        $this->assertFalse(checkNameExists($response, $firstPart->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_part_category()
    {
        $this->signIn();

        $category = create('App\PartCategory');
        $firstPart = create('App\Part');
        $secondPart = create('App\Part', ['part_category_id' => $category->id]);

        $response = $this->get(route('api.lego.parts', ['part_category_id' => $category->id]));

        $this->assertTrue(checkNameExists($response, $secondPart->name));
        $this->assertFalse(checkNameExists($response, $firstPart->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_part_category_label()
    {
        $this->signIn();

        $category = create('App\PartCategory');
        $firstPart = create('App\Part');
        $secondPart = create('App\Part', ['part_category_id' => $category->id]);

        $response = $this->get(route('api.lego.parts', ['category_label' => $category->name]));

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

        $this->get(route('api.lego.parts', ['sort' => 'name']))
            ->assertSeeInOrder([$partA->name, $partB->name, $partC->name]);
    }

    /** @test */
    public function an_authenticated_user_can_sort_parts_by_name_in_descending_order()
    {
        $this->signIn();

        $partC = create('App\Part', ['name' => 'C Part']);
        $partA = create('App\Part', ['name' => 'A Part']);
        $partB = create('App\Part', ['name' => 'B Part']);

        $this->get(route('api.lego.parts', ['sort' => '-name']))
            ->assertSeeInOrder([$partC->name, $partB->name, $partA->name]);
    }

    /** @test */
    public function an_authorized_user_can_access_the_parts_image_url()
    {
        $this->signIn();

        $part = create('App\Part');

        $part->addImageUrl('http://www.example.com');

        $response = $this->get(route('api.lego.parts'));

        $data = $response->getData()->data[0];

        $this->assertEquals($part->partImageUrl->first()->image_url, $data->image_url);
    }

    /** @test */
    public function an_authorized_user_can_access_the_parts_category_label()
    {
        $this->signIn();

        $category = create('App\PartCategory');
        $part = create('App\Part', ['part_category_id' => $category->id]);

        $response = $this->get(route('api.lego.parts'));

        $data = $response->getData()->data[0];

        $this->assertEquals($category->name, $data->category_label);
    }
}
