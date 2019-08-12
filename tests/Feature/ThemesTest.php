<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThemesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_view_all_themes()
    {
        $this->signIn();

        $theme = create('App\Theme');

        $this->get(route('themes.index'))
            ->assertSee($theme->name);
    }

    /** @test */
    public function an_authenticated_user_can_add_a_new_theme()
    {
        $this->signIn();

        $theme = makeRaw('App\Theme');

        $this->post(route('themes.store'), $theme)
            ->assertRedirect(route('themes.index'));

        $this->get(route('themes.index'))
            ->assertSee($theme['name']);
    }

    /** @test */
    public function an_authenticated_user_can_update_a_theme()
    {
        $this->signIn();

        $theme = createRaw('App\Theme');
        
        $theme['name'] = 'New Name';

        $this->patch(route('themes.update', $theme['id']), $theme)
            ->assertRedirect(route('themes.index'));

        $this->get(route('themes.index'))
            ->assertSee($theme['name']);
    }
}
