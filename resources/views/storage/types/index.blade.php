@extends('layouts.app')

@section('content')
<div class="flex items-center">
    <div class="w-3/4 lg:w-1/2 mx-auto">
        <div class="rounded shadow">
            <div class="flex font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
                <div>Storage Container Types</div>
                <div class="ml-auto">
                    <a href="{{ route('storage.types.create') }}" class="btn is-small">Add New</a>
                </div>
            </div>
            <div class="bg-white p-3 pb-6 rounded-b">
                @forelse ($types as $type)
                    <div class="py-2 px-1 border rounded mt-3 flex text-secondary-darker">
                        <span>{{ $type->name }}</span>
                        <a href="{{ route('storage.types.edit', $type->id) }}" class="ml-auto text-sm">edit</a>
                    </div>
                @empty
                    <p>There are currently no container types in the database.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
