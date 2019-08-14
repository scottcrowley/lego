<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PartRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_retrieve_all_relationships()
    {
        $this->signIn();

        $partRelationship = create('App\PartRelationship');

        $this->get(route('api.lego.part_relationships'))
            ->assertSee($partRelationship->child_part_num)
            ->assertSee($partRelationship->parent_part_num);
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_parent_part_num()
    {
        $this->signIn();

        $parent = create('App\Part', ['part_num' => 'parentXYZ']);

        $relationship1 = create('App\PartRelationship', ['parent_part_num' => $parent->part_num]);

        $relationship2 = create('App\PartRelationship');

        $this->get(route('api.lego.part_relationships', ['parent_part_num' => 'parentXYZ']))
            ->assertSee($relationship1->child_part_num)
            ->assertSee($relationship1->parent_part_num)
            ->assertDontSee($relationship2->child_part_num)
            ->assertDontSee($relationship2->parent_part_num);
    }

    /** @test */
    public function an_authenticated_user_can_sort_results_by_parent_part_num()
    {
        $this->signIn();

        $parentC = create('App\Part', ['part_num' => 'parentC']);
        $parentA = create('App\Part', ['part_num' => 'parentA']);
        $parentB = create('App\Part', ['part_num' => 'parentB']);

        $relationshipC = create('App\PartRelationship', ['parent_part_num' => $parentC->part_num]);
        $relationshipA = create('App\PartRelationship', ['parent_part_num' => $parentA->part_num]);
        $relationshipB = create('App\PartRelationship', ['parent_part_num' => $parentB->part_num]);

        $this->get(route('api.lego.part_relationships', ['sort' => 'parent_part_num']))
            ->assertSeeInOrder([$relationshipA->parent_part_num, $relationshipB->parent_part_num, $relationshipC->parent_part_num]);
    }

    /** @test */
    public function an_authenticated_user_can_sort_results_by_parent_part_num_in_descending_order()
    {
        $this->signIn();

        $parentC = create('App\Part', ['part_num' => 'parentC']);
        $parentA = create('App\Part', ['part_num' => 'parentA']);
        $parentB = create('App\Part', ['part_num' => 'parentB']);

        $relationshipC = create('App\PartRelationship', ['parent_part_num' => $parentC->part_num]);
        $relationshipA = create('App\PartRelationship', ['parent_part_num' => $parentA->part_num]);
        $relationshipB = create('App\PartRelationship', ['parent_part_num' => $parentB->part_num]);

        $this->get(route('api.lego.part_relationships', ['sortdesc' => 'parent_part_num']))
            ->assertSeeInOrder([$relationshipC->parent_part_num, $relationshipB->parent_part_num, $relationshipA->parent_part_num]);
    }

    /** @test */
    public function an_authenticated_user_can_sort_results_by_child_part_num()
    {
        $this->signIn();

        $childC = create('App\Part', ['part_num' => 'childC']);
        $childA = create('App\Part', ['part_num' => 'childA']);
        $childB = create('App\Part', ['part_num' => 'childB']);

        $relationshipC = create('App\PartRelationship', ['child_part_num' => $childC->part_num]);
        $relationshipA = create('App\PartRelationship', ['child_part_num' => $childA->part_num]);
        $relationshipB = create('App\PartRelationship', ['child_part_num' => $childB->part_num]);

        $this->get(route('api.lego.part_relationships', ['sort' => 'child_part_num']))
            ->assertSeeInOrder([$relationshipA->child_part_num, $relationshipB->child_part_num, $relationshipC->child_part_num]);
    }

    /** @test */
    public function an_authenticated_user_can_sort_results_by_child_part_num_in_descending_order()
    {
        $this->signIn();

        $childC = create('App\Part', ['part_num' => 'childC']);
        $childA = create('App\Part', ['part_num' => 'childA']);
        $childB = create('App\Part', ['part_num' => 'childB']);

        $relationshipC = create('App\PartRelationship', ['child_part_num' => $childC->part_num]);
        $relationshipA = create('App\PartRelationship', ['child_part_num' => $childA->part_num]);
        $relationshipB = create('App\PartRelationship', ['child_part_num' => $childB->part_num]);

        $this->get(route('api.lego.part_relationships', ['sortdesc' => 'child_part_num']))
            ->assertSeeInOrder([$relationshipC->child_part_num, $relationshipB->child_part_num, $relationshipA->child_part_num]);
    }
}
