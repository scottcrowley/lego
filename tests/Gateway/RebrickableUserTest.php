<?php

namespace Tests\Gateway;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RebrickableUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authorized_user_can_generate_a_token()
    {
        $this->signIn();

        $response = $this->get(route('api.users.token'));

        $this->assertEquals(config('rebrickable.api.token'), $response->getData()->user_token);
    }

    /** @test */
    public function an_authorized_user_can_access_their_rebrickable_profile_details()
    {
        $this->signIn();

        $response = $this->get(route('api.users.profile'));

        $data = $response->getData();

        $this->assertEquals(config('rebrickable.api.email'), $data->email);

        $this->assertArrayHasKey('all_parts', $response->json()['lego']);
    }
}
