<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PartCategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_access_details_about_all_related_parts()
    {
        $this->signIn();

        $partCategory = create('App\PartCategory');

        create('App\Part', ['part_category_id' => $partCategory->id], 4);

        $this->assertCount(4, $partCategory->fresh()->parts);
    }

    /** @test */
    public function it_can_access_details_about_all_related_part_storage_locations()
    {
        $this->signIn();

        $partCategory = create('App\PartCategory');

        $part = create('App\Part', ['part_category_id' => $partCategory->id]);
        $userPart = create('App\UserPart', ['part_num' => $part->part_num]);

        $storageLocation = create('App\StorageLocation');

        $storageLocation->togglePart($userPart);

        $partLocations = $partCategory->storageLocations();

        $this->assertCount(1, $partLocations);

        $this->assertEquals($storageLocation->name, $partLocations[$partCategory->id]);
    }
}
