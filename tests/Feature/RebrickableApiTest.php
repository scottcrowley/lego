<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RebrickableApiTest extends TestCase
{
    use RefreshDatabase;

    // /** @test */
    // public function it_requires_valid_credentials()
    // {
    //     $this->signIn($user = create('App\User'));

    //     $credentials = makeRaw('App\RebrickableCredentials', ['user_id' => $user->id, 'api_key' => '']);

    //     $this->json('post', route('credentials.store'), $credentials)
    //         ->assertStatus(400);
    // }
}
