<?php

namespace Tests\Unit;

use App\Set;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SetTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_details_on_its_theme()
    {
        $this->signIn();

        $theme = create('App\Theme');

        create('App\Set', ['theme_id' => $theme->id]);

        $set = Set::first();

        $this->assertEquals($theme->name, $set->theme->name);
    }
}
