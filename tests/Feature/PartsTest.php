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

        $this->assertTrue(checkName($this->get(route('api.lego.parts')), $part->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_name()
    {
        $this->signIn();

        $first = create('App\Part', ['name' => 'Part 1']);

        $second = create('App\Part', ['name' => 'Part 2']);

        $this->assertTrue(checkName($this->get(route('api.lego.parts', ['name' => 'Part 2'])), $second->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_part_num()
    {
        $this->signIn();

        $first = create('App\Part', ['part_num' => 'Part 1']);

        $second = create('App\Part', ['part_num' => 'Part 2']);

        $this->assertTrue(checkName($this->get(route('api.lego.parts', ['part_num' => 'Part 2'])), $second->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_part_category()
    {
        $this->signIn();

        $category = create('App\PartCategory');

        $first = create('App\Part');

        $second = create('App\Part', ['part_category_id' => $category->id]);

        $this->assertTrue(checkName($this->get(route('api.lego.parts', ['part_category_id' => $category->id])), $second->name));
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

        $this->get(route('api.lego.parts', ['sortdesc' => 'name']))
            ->assertSeeInOrder([$partC->name, $partB->name, $partA->name]);
    }
}
