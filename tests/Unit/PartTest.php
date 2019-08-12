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
    public function it_can_have_access_to_part_category_storage_location_name_and_details()
    {
        $this->signIn();

        $location = create('App\StorageLocation');

        $partCategory = create('App\PartCategory');

        $location->addPartCategory($partCategory);

        $part = create('App\Part', ['part_category_id' => $partCategory->id]);
        
        $this->assertEquals($part->storageLocation->name, $location->name);
    }

    // /** @test */
    // public function it_requires_a_unique_part_num()
    // {
    //     $this->signIn();

    //     $part = makeRaw('App\Part', ['part_num' => '']);

    //     $this->post(route('parts.store'), $part)
    //         ->assertSessionHasErrors('part_num');

    //     $part = create('App\Part');

    //     $newpart = $part->toArray();

    //     $this->post(route('parts.store'), $newpart)
    //         ->assertSessionHasErrors('part_num');
    // }

    // /** @test */
    // public function it_requires_a_name()
    // {
    //     $this->signIn();

    //     $part = makeRaw('App\Part', ['name' => '']);

    //     $this->post(route('parts.store'), $part)
    //         ->assertSessionHasErrors('name');
    // }

    // /** @test */
    // public function it_requires_an_existing_part_category_id()
    // {
    //     $this->signIn();

    //     $part = makeRaw('App\Part', ['part_category_id' => '']);

    //     $this->post(route('parts.store'), $part)
    //         ->assertSessionHasErrors('part_category_id');

    //     $part['part_category_id'] = 50;

    //     $this->post(route('parts.store'), $part)
    //         ->assertSessionHasErrors('part_category_id');
    // }
}
