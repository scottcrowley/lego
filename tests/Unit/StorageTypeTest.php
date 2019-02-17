<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StorageTypeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_requires_a_name()
    {
        $this->signIn($user = create('App\User'));

        $type = makeRaw('App\StorageType', ['name' => '']);

        $this->post(route('storage.types.store'), $type)
            ->assertSessionHasErrors('name');

        $type = createRaw('App\StorageType');

        $type['name'] = '';

        $this->patch(route('storage.types.update', $type['id']), $type)
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_requires_a_description()
    {
        $this->signIn($user = create('App\User'));

        $type = makeRaw('App\StorageType', ['description' => '']);

        $this->post(route('storage.types.store'), $type)
            ->assertSessionHasErrors('description');
    }
}
