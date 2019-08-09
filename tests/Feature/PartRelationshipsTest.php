<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PartRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_view_all_relationships()
    {
        $this->signIn();

        $partRelationship = create('App\PartRelationship');

        $this->get(route('part_relationships.index'))
            ->assertSee($partRelationship->child_part_num)
            ->assertSee($partRelationship->parent_part_num);
    }

    /** @test */
    public function an_authenticated_user_can_add_a_new_relationship()
    {
        $this->signIn();

        $partRelationship = makeRaw('App\PartRelationship');

        $this->post(route('part_relationships.index'), $partRelationship)
            ->assertRedirect(route('part_relationships.index'));
        
        $this->assertDatabaseHas('part_relationships', $partRelationship);
    }
}
