<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\RebrickableAPI;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CredentialsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        app()->singleton(RebrickableApi::class, function () {
            return \Mockery::mock(RebrickableApi::class, function ($m) {
                $m->shouldReceive('generateToken')
                    ->andReturn(
                        ['user_token' => 'ad5042154683aff84557c47f73e62f92f']
                    );
            });
        });
    }

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

        create('App\RebrickableCredentials', ['user_id' => $user->id]);

        $credentials = $user->credentials->toArray();

        $credentials['email'] = 'new@email.com';
        $credentials['password'] = 'newpassword';
        $credentials['api_key'] = 'mynewapikey';

        $response = $this->patch(route('credentials.update'), $credentials);

        $this->get(route('profiles.edit'))
            ->assertSee($credentials['email'])
            ->assertSee($credentials['api_key']);
    }

    // /** @test */
    // public function a_user_token_is_generated_when_credentials_are_created()
    // {
    //     $this->signIn($user = create('App\User'));

    //     $credentials = makeRaw('App\RebrickableCredentials', ['user_id' => $user->id]);

    //     $this->post(route('credentials.store'), $credentials);

    //     $this->get(route('profiles.edit'))
    //         ->assertSee('ad5042154683aff84557c47f73e62f92f');
    // }
}
