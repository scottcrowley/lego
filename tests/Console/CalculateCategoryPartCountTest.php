<?php

namespace Tests\Console;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CalculateCategoryPartCountTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_calculates_part_count()
    {
        $this->withoutExceptionHandling();

        $category = create('App\PartCategory');

        $parts = create('App\Part', ['part_category_id' => $category->id], 10);

        $this->artisan('lego:category-part-count', ['--no-interaction' => true]);

        $this->assertDatabaseHas('category_part_count', ['part_category_id' => $category->id, 'part_count' => $parts->count()]);

        $this->assertEquals($category->fresh()->part_count, $parts->count());
    }
}
