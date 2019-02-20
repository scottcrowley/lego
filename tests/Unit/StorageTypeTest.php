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
        $this->signIn();

        $type = makeRaw('App\StorageType', ['name' => '']);

        $this->post(route('storage.types.store'), $type)
        ->assertSessionHasErrors('name');

        $type = create('App\StorageType');

        $type->name = '';

        $this->patch(route('storage.types.update', $type->id), $type->toArray())
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_requires_a_unique_name()
    {
        $this->signIn();

        create('App\StorageType', ['name' => 'Special Container']);

        $type = makeRaw('App\StorageType', ['name' => 'Special Container']);

        $this->post(route('storage.types.store'), $type)
            ->assertSessionHasErrors('name');
    }

    /** @test */
    public function it_requires_a_description()
    {
        $this->signIn();

        $type = makeRaw('App\StorageType', ['description' => '']);

        $this->post(route('storage.types.store'), $type)
            ->assertSessionHasErrors('description');
    }
}
