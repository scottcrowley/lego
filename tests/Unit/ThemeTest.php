<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThemeTest extends TestCase
{
    use RefreshDatabase;

    // /** @test */
    // public function it_requires_a_name()
    // {
    //     $this->signIn();

    //     $theme = makeRaw('App\Theme', ['name' => '']);

    //     $this->post(route('themes.store'), $theme)
    //         ->assertSessionHasErrors('name');
    // }

    // /** @test */
    // public function it_can_have_a_parent()
    // {
    //     $parent = create('App\Theme');

    //     $theme = create('App\Theme', ['parent_id' => $parent->id]);

    //     $themeParent = $theme->parent();

    //     $this->assertEquals($themeParent->id, $parent->id);
    // }

    // /** @test */
    // public function it_can_have_multiple_parents()
    // {
    //     $topParent = create('App\Theme', ['name' => 'top parent']);

    //     $parent = create('App\Theme', ['name' => 'parent', 'parent_id' => $topParent->id]);

    //     $theme = create('App\Theme', ['name' => 'theme', 'parent_id' => $parent->id]);

    //     $this->assertCount(2, $theme->parents);

    //     $this->assertEquals('top parent -> parent', $theme->parentsLabel);
    // }
}
