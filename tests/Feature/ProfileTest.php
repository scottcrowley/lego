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
        $this->get(route('profiles'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function a_user_can_view_their_own_profile()
    {
        $this->signIn($user = create('App\User'));

        $this->get(route('profiles'))
            ->assertSee($user->name);
    }
}
