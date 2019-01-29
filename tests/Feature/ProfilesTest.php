<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfilesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_must_be_signed_in_to_view()
    {
        $this->get(route('profiles'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function a_user_may_view_their_own_profile()
    {
        $this->signIn($user = create('App\User'));

        $this->get(route('profiles'))
            ->assertSee($user->username)
            ->assertSee($user->name);
    }

    /** @test */
    public function a_user_may_view_edit_page_for_their_own_profile()
    {
        $this->withoutExceptionHandling();
        $this->signIn($user = create('App\User'));

        $this->get(route('profiles.edit'))
            ->assertSee($user->username)
            ->assertSee($user->name);
    }

    /** @test */
    public function a_user_may_update_their_profile()
    {
        $this->signIn($user = create('App\User'));

        $user->name = 'New Name';
        $this->patch(route('profiles.update'), $user->toArray())
            ->assertRedirect(route('profiles'));

        $this->get(route('profiles'))
            ->assertSee('New Name');
    }
}
