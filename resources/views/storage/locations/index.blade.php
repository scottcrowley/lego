@extends('layouts.app')

@section('title')
    Storage Locations
@endsection

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
            @if ($locations->count())
                <storage-locations :locations="{{ $locations }}"/>
            @else
                <p>There are currently no storage locations in the database.</p>
            @endif
        </div>
    </div>
</div>
@endsection
