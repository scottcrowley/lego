<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CredentialsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_must_be_signed_in_to_view_create_page()
    {
        $this->get(route('credentials.create'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function a_user_must_be_signed_in_to_view_edit_page()
    {
        $this->get(route('credentials.edit'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function a_user_can_create_new_credentials_if_they_do_not_exist()
    {
        $this->signIn($user = create('App\User'));

        $credentials = makeRaw('App\RebrickableCredentials', ['user_id' => $user->id]);

        $this->post(route('credentials.store'), $credentials);

        $this->get(route('profiles.edit'))
            ->assertSee($credentials['email'])
            ->assertSee($credentials['api_key']);
    }

    /** @test */
    public function a_user_can_update_their_credentials()
    {
        $this->signIn($user = create('App\User'));

        $credentials = makeRaw('App\RebrickableCredentials', ['user_id' => $user->id]);

        $credentials['email'] = 'new@email.com';
        $credentials['password'] = 'newpassword';
        $credentials['api_key'] = 'mynewapikey';

        $this->post(route('credentials.update'), $credentials);

        $this->get(route('profiles.edit'))
            ->assertSee($credentials['email'])
            ->assertSee($credentials['api_key']);
    }
}
