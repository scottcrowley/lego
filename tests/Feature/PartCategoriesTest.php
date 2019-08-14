<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PartCategoriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_retrieve_all_part_categories()
    {
        $this->signIn();

        $category = create('App\PartCategory');

        $this->get(route('api.lego.part_categories'))
            ->assertSee($category->name);
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_name()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $first = create('App\PartCategory', ['name' => 'First Category']);
        
        $second = create('App\PartCategory', ['name' => 'Second Category']);

        $this->get(route('api.lego.part_categories', ['name' => 'Second Category']))
            ->assertSee($second->name)
            ->assertDontSee($first->name);
    }

    /** @test */
    public function an_authenticated_user_can_sort_categories_by_name()
    {
        $this->signIn();

        $categoriesC = create('App\PartCategory', ['name' => 'C Category']);
        $categoriesA = create('App\PartCategory', ['name' => 'A Category']);
        $categoriesB = create('App\PartCategory', ['name' => 'B Category']);

        $this->get(route('api.lego.part_categories', ['sort' => 'name']))
            ->assertSeeInOrder([$categoriesA->name, $categoriesB->name, $categoriesC->name]);
    }
    
    /** @test */
    public function an_authenticated_user_can_sort_categories_by_name_in_descending_order()
    {
        $this->signIn();

        $categoriesC = create('App\PartCategory', ['name' => 'C Category']);
        $categoriesA = create('App\PartCategory', ['name' => 'A Category']);
        $categoriesB = create('App\PartCategory', ['name' => 'B Category']);

        $this->get(route('api.lego.part_categories', ['sortdesc' => 'name']))
            ->assertSeeInOrder([$categoriesC->name, $categoriesB->name, $categoriesA->name]);
    }
}
