<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CredentialTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_requires_a_unique_user_id()
    {
        $this->signIn($user = create('App\User'));

        $credentials = create('App\RebrickableCredentials', ['user_id' => $user->id]);

        $this->post(route('credentials.store'), $credentials->toArray())
            ->assertSessionHas('flash');

        $this->assertEquals('danger', session('flash.level'));
    }

    /** @test */
    public function it_requires_an_email_address()
    {
        $this->signIn($user = create('App\User'));

        $credentials = makeRaw('App\RebrickableCredentials', ['user_id' => $user->id, 'email' => '']);

        $this->post(route('credentials.store'), $credentials)
            ->assertSessionHasErrors('email');

        // $this->patch(route('credentials.update'), $credentials)
        //     ->assertSessionHasErrors('email');
    }

    /** @test */
    public function it_requires_a_password()
    {
        $this->signIn($user = create('App\User'));

        $credentials = makeRaw('App\RebrickableCredentials', ['user_id' => $user->id, 'password' => '']);

        $this->post(route('credentials.store'), $credentials)
            ->assertSessionHasErrors('password');

        // $this->patch(route('credentials.store'), $credentials)
        //     ->assertSessionHasErrors('password');
    }

    /** @test */
    public function it_requires_an_api_key()
    {
        $this->signIn($user = create('App\User'));

        $credentials = makeRaw('App\RebrickableCredentials', ['user_id' => $user->id, 'api_key' => '']);

        $this->post(route('credentials.store'), $credentials)
        ->assertSessionHasErrors('api_key');

        $this->patch(route('credentials.store'), $credentials)
        ->assertSessionHasErrors('api_key');
    }
}
