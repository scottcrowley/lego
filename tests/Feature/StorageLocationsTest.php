<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StorageLocationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_view_all_locations()
    {
        $this->signIn();

        $location = create('App\StorageLocation');

        $this->get(route('storage.locations.index'))
            ->assertSee($location->name);
    }

    /** @test */
    public function an_authenticated_user_can_view_location_create_page()
    {
        $this->signIn();

        $this->get(route('storage.locations.create'))
            ->assertSee('Add A New Storage Location');
    }

    /** @test */
    public function an_authenticated_user_can_add_a_new_location()
    {
        $this->signIn();

        $location = makeRaw('App\StorageLocation');

        $this->post(route('storage.locations.store'), $location)
            ->assertRedirect(route('storage.locations.index'));

        $this->assertDatabaseHas('storage_locations', $location);

        $this->get(route('storage.locations.index'))
            ->assertSee($location['name']);
    }

    /** @test */
    public function an_authenticated_user_can_view_location_edit_page()
    {
        $this->signIn();
        $location = create('App\StorageLocation');

        $this->get(route('storage.locations.edit', $location->id))
            ->assertSee('Edit '.$location->name);
    }

    /** @test */
    public function an_authenticated_user_can_update_a_location()
    {
        $this->signIn();

        $location = create('App\StorageLocation');

        $location->name = 'Some new name';

        $this->patch(route('storage.locations.update', $location->id), $location->toArray())
            ->assertRedirect(route('storage.locations.index'));

        $this->get(route('storage.locations.index'))
            ->assertSee('Some new name');
    }

    /** @test */
    public function an_authenticated_user_can_copy_an_existing_storage_location()
    {
        $this->signIn();

        $originalLocation = create('App\StorageLocation');

        $this->get(route('storage.locations.copy', $originalLocation->id))
            ->assertSee($originalLocation->name)
            ->assertSee($originalLocation->description)
            ->assertSee($originalLocation->fresh()->type->name);
    }

    /** @test */
    public function an_authenticated_user_can_toggle_a_part_association_through_api()
    {
        $this->signIn();

        $location = create('App\StorageLocation');
        $part = create('App\Part');
        $userPart = create('App\UserPart', ['part_num' => $part->part_num]);

        $this->get(route('api.users.storage.locations.parts.toggle', ['location' => $location->id, 'part' => $userPart->part_num]));

        $this->assertEquals($part->name, $location->parts->first()->name);
    }

    /** @test */
    public function an_authenticated_user_can_move_parts_from_one_location_to_another_through_api()
    {
        $this->signIn();

        $firstLocation = create('App\StorageLocation', ['name' => 'First Location']);
        $secondLocation = create('App\StorageLocation', ['name' => 'Second Location']);
        $firstPart = create('App\Part', ['name' => 'First Part']);
        $secondPart = create('App\Part', ['name' => 'Second Part']);
        $firstUserPart = create('App\UserPart', ['part_num' => $firstPart->part_num]);
        $secondUserPart = create('App\UserPart', ['part_num' => $secondPart->part_num]);

        $firstLocation->togglePart($firstUserPart);

        $this->post(
            route(
                'api.users.storage.locations.parts.move',
                ['location' => $firstLocation->id, 'newLocation' => $secondLocation->id]
            ),
            [
                $firstUserPart,
                $secondUserPart
            ]
        );

        $this->assertEmpty($firstLocation->fresh()->parts);
        $this->assertCount(2, $secondLocation->fresh()->parts);
        $this->assertEquals($firstPart->name, $secondLocation->parts[0]->name);
        $this->assertEquals($secondPart->name, $secondLocation->parts[1]->name);
    }

    /** @test */
    public function an_authenticated_user_can_sort_parts_by_name()
    {
        $this->signIn();

        $location = create('App\StorageLocation');
        $partC = create('App\Part', ['name' => 'C Part']);
        $partA = create('App\Part', ['name' => 'A Part']);
        $partB = create('App\Part', ['name' => 'B Part']);
        $userPartC = create('App\UserPart', ['part_num' => $partC->part_num]);
        $userPartA = create('App\UserPart', ['part_num' => $partA->part_num]);
        $userPartB = create('App\UserPart', ['part_num' => $partB->part_num]);
        $location->togglePart($userPartC);
        $location->togglePart($userPartA);
        $location->togglePart($userPartB);

        $this->get(route('api.users.storage.locations.parts', ['location' => $location->id, 'sort' => 'name']))
            ->assertSeeInOrder([$partA->name, $partB->name, $partC->name]);
    }

    /** @test */
    public function an_authenticated_user_can_sort_parts_by_name_in_descending_order()
    {
        $this->signIn();

        $location = create('App\StorageLocation');
        $partC = create('App\Part', ['name' => 'C Part']);
        $partA = create('App\Part', ['name' => 'A Part']);
        $partB = create('App\Part', ['name' => 'B Part']);
        $userPartC = create('App\UserPart', ['part_num' => $partC->part_num]);
        $userPartA = create('App\UserPart', ['part_num' => $partA->part_num]);
        $userPartB = create('App\UserPart', ['part_num' => $partB->part_num]);
        $location->togglePart($userPartC);
        $location->togglePart($userPartA);
        $location->togglePart($userPartB);

        $this->get(route('api.users.storage.locations.parts', ['location' => $location->id, 'sortdesc' => 'name']))
            ->assertSeeInOrder([$partC->name, $partB->name, $partA->name]);
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_part_name()
    {
        $this->signIn();

        $location = create('App\StorageLocation');
        $firstPart = create('App\Part', ['name' => 'First Part']);
        $secondPart = create('App\Part', ['name' => 'Second Part']);
        $userPart = create('App\UserPart', ['part_num' => $secondPart->part_num]);
        $location->togglePart($userPart);

        $response = $this->get(route('api.users.storage.locations.parts', ['location' => $location->id, 'name' => $secondPart->name]));

        $this->assertTrue(checkNameExists($response, $secondPart->name));
        $this->assertFalse(checkNameExists($response, $firstPart->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_part_num()
    {
        $this->signIn();

        $location = create('App\StorageLocation');
        $firstPart = create('App\Part', ['name' => 'First Part']);
        $secondPart = create('App\Part', ['name' => 'Second Part']);
        $userPart = create('App\UserPart', ['part_num' => $secondPart->part_num]);
        $location->togglePart($userPart);

        $response = $this->get(route('api.users.storage.locations.parts', ['location' => $location->id, 'part_num' => $secondPart->part_num]));

        $this->assertTrue(checkNameExists($response, $secondPart->name));
        $this->assertFalse(checkNameExists($response, $firstPart->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_part_category_id()
    {
        $this->signIn();

        $location = create('App\StorageLocation');
        $category = create('App\PartCategory');
        $firstPart = create('App\Part', ['name' => 'First Part']);
        $secondPart = create('App\Part', ['name' => 'Second Part', 'part_category_id' => $category->id]);
        $userPart = create('App\UserPart', ['part_num' => $secondPart->part_num]);
        $location->togglePart($userPart);

        $response = $this->get(route('api.users.storage.locations.parts', ['location' => $location->id, 'category_id' => $category->id]));

        $this->assertTrue(checkNameExists($response, $secondPart->name));
        $this->assertFalse(checkNameExists($response, $firstPart->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_part_category_label()
    {
        $this->signIn();

        $location = create('App\StorageLocation');
        $firstCategory = create('App\PartCategory', ['name' => 'First Category']);
        $secondCategory = create('App\PartCategory', ['name' => 'Second Category']);
        $firstPart = create('App\Part', ['name' => 'First Part', 'part_category_id' => $firstCategory->id]);
        $secondPart = create('App\Part', ['name' => 'Second Part', 'part_category_id' => $secondCategory->id]);
        $firstUserPart = create('App\UserPart', ['part_num' => $firstPart->part_num]);
        $secondUserPart = create('App\UserPart', ['part_num' => $secondPart->part_num]);
        $location->togglePart($firstUserPart);
        $location->togglePart($secondUserPart);

        $response = $this->get(route('api.users.storage.locations.parts', ['location' => $location->id, 'category_label' => $secondCategory->name]));

        $this->assertTrue(checkNameExists($response, $secondPart->name));
        $this->assertFalse(checkNameExists($response, $firstPart->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_part_color()
    {
        $this->signIn();

        $location = create('App\StorageLocation');
        $firstColor = create('App\Color', ['name' => 'First Color']);
        $secondColor = create('App\Color', ['name' => 'Second Color']);
        $firstPart = create('App\Part', ['name' => 'First Part']);
        $secondPart = create('App\Part', ['name' => 'Second Part']);
        $firstUserPart = create('App\UserPart', ['part_num' => $firstPart->part_num, 'color_id' => $firstColor->id]);
        $secondUserPart = create('App\UserPart', ['part_num' => $secondPart->part_num, 'color_id' => $secondColor->id]);
        $location->togglePart($firstUserPart);
        $location->togglePart($secondUserPart);

        $response = $this->get(route('api.users.storage.locations.parts', ['location' => $location->id, 'color' => $secondColor->name]));

        $this->assertTrue(checkNameExists($response, $secondPart->name));
        $this->assertFalse(checkNameExists($response, $firstPart->name));
    }

    /** @test */
    public function an_authenticated_user_can_filter_results_by_excluding_parts_assigned_to_other_locations()
    {
        $this->signIn();

        $firstLocation = create('App\StorageLocation', ['name' => 'First Location']);
        $secondLocation = create('App\StorageLocation', ['name' => 'Second Location']);
        $firstPart = create('App\Part', ['name' => 'First Part']);
        $secondPart = create('App\Part', ['name' => 'Second Part']);
        $firstUserPart = create('App\UserPart', ['part_num' => $firstPart->part_num]);
        $secondUserPart = create('App\UserPart', ['part_num' => $secondPart->part_num]);
        $firstLocation->togglePart($firstUserPart);
        $secondLocation->togglePart($secondUserPart);

        $response = $this->get(route('api.users.storage.locations.parts', ['location' => $secondLocation->id, 'exclude_assigned' => $secondLocation->id]));

        $this->assertTrue(checkNameExists($response, $secondPart->name));
        $this->assertFalse(checkNameExists($response, $firstPart->name));
    }
}
