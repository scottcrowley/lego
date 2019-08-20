<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserSetsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authorized_user_can_retrieve_all_of_their_owned_sets()
    {
        $this->signIn();

        $set = create('App\Set');

        $userSet = create('App\UserSet', ['set_num' => $set->set_num]);

        $response = $this->get(route('api.users.sets'));

        $data = $response->getData()->data;

        $this->assertEquals($set->name, $data[0]->name);
    }
}
