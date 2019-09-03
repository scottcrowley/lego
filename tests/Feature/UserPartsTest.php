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

        $color1 = create('App\Color');
        $color2 = create('App\Color');

        $part = create('App\Part');

        create('App\UserPart', ['part_num' => $part->part_num, 'color_id' => $color1]);
        create('App\UserPart', ['part_num' => $part->part_num, 'color_id' => $color2]);

        $response = $this->get(route('api.users.parts.all'));

        $data = $response->getData()->data;

        $this->assertCount(1, $data);
        $this->assertEquals($part->name, $data[0]->name);
    }

    /** @test */
    public function an_authorized_user_can_retrieve_all_their_owned_individual_parts()
    {
        $this->signIn();

        $color1 = create('App\Color');
        $color2 = create('App\Color');

        $part = create('App\Part');

        create('App\UserPart', ['part_num' => $part->part_num, 'color_id' => $color1]);
        create('App\UserPart', ['part_num' => $part->part_num, 'color_id' => $color2]);

        $response = $this->get(route('api.users.parts.individual'));

        $data = $response->getData()->data;

        $this->assertCount(2, $data);
        $this->assertEquals($part->name, $data[0]->name);
        $this->assertEquals($part->name, $data[1]->name);
    }
}
