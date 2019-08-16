<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StorageTypesTest extends TestCase
{
    use RefreshDatabase;

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

        $this->patch(route('storage.types.update', $type['id']), $type)
            ->assertRedirect(route('storage.types.index'));

        $this->get(route('storage.types.index'))
            ->assertSee('New Storage Type');

        $this->assertDatabaseHas('storage_types', $type);
    }

    /** @test */
    public function an_authenticated_user_can_delete_a_storage_type()
    {
        $this->signIn();

        $type = create('App\StorageType');

        $this->delete(route('storage.types.delete', $type->id));

        $this->assertDatabaseMissing('storage_types', $type->toArray());
    }

    /** @test */
    public function an_authenticated_user_can_copy_an_existing_storage_type()
    {
        $this->signIn();

        $originalType = create('App\StorageType');

        $this->get(route('storage.types.copy', $originalType->id))
            ->assertSee($originalType->name)
            ->assertSee($originalType->description);
    }
}
