<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_must_be_signed_in_to_view()
    {
        $this->get(route('profile'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function a_user_may_view_their_own_profile()
    {
        $this->signIn($user = create('App\User'));

        $this->get(route('profile'))
            ->assertSee(e($user->username))
            ->assertSee(e($user->name));
    }

    /** @test */
    public function a_user_may_view_edit_page_for_their_own_profile()
    {
        $this->signIn($user = create('App\User'));

        $this->get(route('profile.edit'))
            ->assertSee(e($user->username))
            ->assertSee(e($user->name));
    }

    /** @test */
    public function a_user_may_update_their_profile()
    {
        $this->signIn($user = create('App\User'));

        $user->name = 'New Name';
        $this->patch(route('profile.update'), $user->toArray())
            ->assertRedirect(route('profile'));

        $this->get(route('profile'))
            ->assertSee('New Name');
    }

    /** @test */
    public function a_user_can_update_their_password()
    {
        $this->signIn($user = create('App\User'));

        $data = [
            'username' => $user->username,
            'name' => $user->name,
            'email' => $user->email,
            'current_password' => 'secret',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $this->patch(route('profile.update'), $data);

        $this->assertTrue(\Hash::check($data['password'], $user->fresh()->password));
    }
}
