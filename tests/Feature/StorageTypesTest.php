<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StorageTypesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_authenticated_user_can_add_a_new_storage_type()
    {
        $this->signIn($user = create('App\User'));

        $type = makeRaw('App\StorageType');

        $this->post(route('storage.types.store'), $type);

        $this->assertDatabaseHas('storage_types', $type);
    }

    /** @test */
    public function a_authenticated_user_can_update_a_storage_type()
    {
        $this->signIn($user = create('App\User'));

        $type = create('App\StorageType');

        $type->name = 'New Storage Type';

        $type = $type->toArray();

        $this->patch(route('storage.types.update', $type['id']), $type);

        $this->assertDatabaseHas('storage_types', $type);
    }
}
