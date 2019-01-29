<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_requires_a_username()
    {
        $this->signIn($user = create('App\User', ['username' => '']));

        $this->patch(route('profiles.update'), $user->toArray())
            ->assertSessionHasErrors('username');
    }

    /** @test */
    public function it_requires_a_name()
    {
        $this->signIn($user = create('App\User', ['name' => '']));

        $this->patch(route('profiles.update'), $user->toArray())
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_requires_an_email()
    {
        $this->signIn($user = create('App\User', ['email' => '']));

        $this->patch(route('profiles.update'), $user->toArray())
            ->assertSessionHasErrors('email');
    }
}
