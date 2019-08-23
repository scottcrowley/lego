@extends('layouts.app')

@section('content')
<div class="w-3/4">
    <div class="rounded shadow">
        <div class="flex font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
            <div>Storage Locations</div>
            <div class="ml-auto">
                <a href="{{ route('storage.locations.create') }}" class="btn is-small">Add New</a>
            </div>
        </div>
        <div class="bg-white p-3 pb-6 rounded-b">
            @forelse ($locations as $location)
                <div class="py-2 px-1 border rounded mt-3 flex text-secondary-darker">
                    <span class="flex-1">{{ $location->name.((!is_null($location->nickname)) ? ' ('.$location->nickname.')' : '') }} </span>
                    <a href="{{ route('storage.locations.parts', $location->id) }}" class="text-sm mr-3">add parts</a>
                    <a href="{{ route('storage.locations.copy', $location->id) }}" class="text-sm mr-3">copy</a>
                    <a href="{{ route('storage.locations.edit', $location->id) }}" class="text-sm">edit</a>
                </div>
            @empty
                <p>There are currently no storage locations in the database.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
