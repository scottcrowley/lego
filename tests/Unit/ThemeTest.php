<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThemeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_have_a_parent()
    {
        $parent = create('App\Theme');

        $theme = create('App\Theme', ['parent_id' => $parent->id]);

        $themeParent = $theme->parent;

        $this->assertEquals($themeParent->id, $parent->id);
    }
}
