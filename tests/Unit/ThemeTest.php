<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThemeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_requires_a_name_in_a_new_theme()
    {
        $this->signIn();

        $theme = makeRaw('App\Theme', ['name' => '']);

        $this->post(route('themes.store'), $theme)
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_requires_a_name_when_updating_a_theme()
    {
        $this->signIn();

        $theme = createRaw('App\Theme');

        $theme['name'] = '';

        $this->patch(route('themes.update', $theme['id']), $theme)
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_requires_a_valid_parent_if_provided_in_a_new_theme()
    {
        $this->signIn();

        $theme = makeRaw('App\Theme');

        $theme['parent_id'] = 4;

        $this->post(route('themes.store'), $theme)
            ->assertSessionHasErrors('parent_id');
            
        $parentTheme = create('App\Theme');

        $theme['parent_id'] = $parentTheme->id;
            
        $this->post(route('themes.store'), $theme)
            ->assertRedirect(route('themes.index'));
    }

    /** @test */
    public function it_requires_a_valid_parent_if_provided_when_updating_a_theme()
    {
        $this->signIn();

        $theme = create('App\Theme');

        $theme->parent_id = 4;

        $this->patch(route('themes.update', $theme->id), $theme->toArray())
            ->assertSessionHasErrors('parent_id');
            
        $parentTheme = create('App\Theme');
        
        $theme->parent_id  = $parentTheme->id;
        
        $this->patch(route('themes.update', $theme->id), $theme->toArray())
            ->assertRedirect(route('themes.index'));

        $this->assertEquals($theme->fresh()->parent->name, $parentTheme->name);
    }

    /** @test */
    public function it_can_have_a_parent()
    {
        $parent = create('App\Theme');

        $theme = create('App\Theme', ['parent_id' => $parent->id]);

        $themeParent = $theme->parent;

        $this->assertEquals($themeParent->id, $parent->id);
    }

    /** @test */
    public function it_can_have_multiple_parent_themes()
    {
        $topParent = create('App\Theme', ['name' => 'top parent']);

        $parent = create('App\Theme', ['name' => 'parent', 'parent_id' => $topParent->id]);

        $theme = create('App\Theme', ['name' => 'theme', 'parent_id' => $parent->id]);

        $this->assertCount(2, $theme->parents);

        $this->assertEquals('top parent -> parent', $theme->parentsLabel);
    }
}
