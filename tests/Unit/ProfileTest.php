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

        $this->patch(route('profile.update'), $user->toArray())
            ->assertSessionHasErrors('username');
    }

    /** @test */
    public function it_requires_a_name()
    {
        $this->signIn($user = create('App\User', ['name' => '']));

        $this->patch(route('profile.update'), $user->toArray())
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_requires_an_email()
    {
        $this->signIn($user = create('App\User', ['email' => '']));

        $this->patch(route('profile.update'), $user->toArray())
            ->assertSessionHasErrors('email');
    }

    /** @test */
    public function it_requires_all_password_fields_when_current_password_is_given()
    {
        $this->signIn($user = create('App\User'));

        $data = [
            'username' => $user->username,
            'name' => $user->name,
            'email' => $user->email,
            'current_password' => 'secret',
            'password' => '',
            'password_confirmation' => '',
        ];

        $this->patch(route('profile.update'), $data)
            ->assertSessionHasErrors('password');

        $data['password'] = 'somenewpassword';

        $this->patch(route('profile.update'), $data)
            ->assertSessionHasErrors('password');

        $data['password'] = 'secret';

        $this->patch(route('profile.update'), $data)
            ->assertSessionHasErrors('password');
    }
}
