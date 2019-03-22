<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PartCategoriesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_view_all_part_categories()
    {
        $this->signIn();

        $category = create('App\PartCategory');

        $this->get(route('part_categories.index'))
            ->assertSee($category->name);
    }

    /** @test */
    public function an_authenticated_user_add_a_new_part_category()
    {
        $this->signIn();

        $category = makeRaw('App\PartCategory');

        $this->post(route('part_categories.store'), $category)
            ->assertRedirect(route('part_categories.index'));

        $this->assertDatabaseHas('part_categories', $category);
    }
}
