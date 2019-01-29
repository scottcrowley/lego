<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function must_be_signed_in_to_access_dashboard()
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function it_requires_a_rebrickable_credentials()
    {
        $this->signIn($user = create('App\User'));

        $this->get(route('dashboard'))
            ->assertRedirect(route('profiles'));
    }
}
