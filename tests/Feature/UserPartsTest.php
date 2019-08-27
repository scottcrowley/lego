<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserPartsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authorized_user_can_retrieve_all_their_owned_parts()
    {
        $this->signIn();

        $part = create('App\Part');

        create('App\UserPart', ['part_num' => $part->part_num]);

        $response = $this->get(route('api.users.parts'));

        $data = $response->getData()->data;

        $this->assertEquals($part->name, $data[0]->name);
    }
}
