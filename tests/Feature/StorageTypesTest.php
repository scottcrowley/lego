<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StorageTypesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_authenticated_user_can_add_a_new_storage_type()
    {
        $this->signIn();

        $type = makeRaw('App\StorageType');

        $this->post(route('storage.types.store'), $type);

        $this->assertDatabaseHas('storage_types', $type);
    }

    /** @test */
    public function an_authenticated_user_can_update_a_storage_type()
    {
        $this->signIn();

        $type = create('App\StorageType');

        $type->name = 'New Storage Type';

        $type = $type->toArray();

        $this->patch(route('storage.types.update', $type['id']), $type);

        $this->assertDatabaseHas('storage_types', $type);
    }

    /** @test */
    public function an_authenticated_user_can_view_all_types()
    {
        $this->signIn();

        $type = create('App\StorageType');

        $this->get(route('storage.types.index'))
        ->assertSee($type->name);
    }

    /** @test */
    public function an_authenicated_user_can_view_type_create_page()
    {
        $this->signIn();

        $this->get(route('storage.types.create'))
            ->assertSee('Add A New Storage Type');
    }
}
