@extends('layouts.app')

@section('content')
<div class="w-full md:w-3/4 px-3 sm:px-0">
    <div class="rounded shadow">
        <div class="flex font-medium md:text-lg text-primary-darker bg-primary p-3 rounded-t">
            <div>Storage Locations</div>
            <div class="ml-auto">
                <a href="{{ route('storage.locations.create') }}" class="btn is-small">Add New</a>
            </div>
        </div>
        <div class="bg-white p-3 pb-6 rounded-b">
            @forelse ($locations as $location)
                <div class="text-sm md:text-base py-2 px-1 border rounded mt-3 sm:flex text-secondary-darker">
                    <div class="font-semibold sm:flex-1 sm:font-normal">{{ $location->name.((!is_null($location->nickname)) ? ' ('.$location->nickname.')' : '') }}</div>
                    <div class="flex justify-around sm:block sm:mt-0 mt-2">
                        <a href="{{ route('storage.locations.parts.index', $location->id) }}" class="btn is-small sm:mr-2">parts</a>
                        <a href="{{ route('storage.locations.copy', $location->id) }}" class="btn is-small sm:mr-2">copy</a>
                        <a href="{{ route('storage.locations.edit', $location->id) }}" class="btn is-small">edit</a>
                    </div>
                </div>
            @empty
                <p>There are currently no storage locations in the database.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
