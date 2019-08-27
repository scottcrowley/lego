@extends('layouts.app')

@section('content')
<div class="w-full px-3 sm:px-0">
    <div class="w-2/3 mb-3 mb-8 mx-auto border rounded-lg p-6 bg-white shadow-md">
        <div>
            <p class="title text-2xl mb-4">{{ $location->name }}</p>
            <p class="mb-2 font-hairline tracking-wide"><span class="font-semibold">Nickname:</span> {{ $location->nickname ?: 'None' }}</p>
            <p class="mb-2 font-hairline tracking-wide"><span class="font-semibold">Type:</span> {{ $location->type->name }}</p>
            <p class="mb-2 font-hairline tracking-wide"><span class="font-semibold">Parts in Location:</span> {{ $location->parts->count() }}</p>
        </div>
        <div class="pt-2">
            <a href="{{ route('storage.locations.parts.edit', $location->id) }}" class="btn is-primary is-narrow">Edit Parts in Location</a>
        </div>
    </div>
    <div class="rounded shadow">
        <div class="flex font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
            <div>Storage Location Parts</div>
        </div>
        <div class="bg-white p-4 pb-6 rounded-b">
            <data-grid 
                label="Parts" 
                image_field="image_url" 
                image_label_field="name" 
                per_page="100" 
                :valnames="[
                    {label: 'Name', field: 'name', title: true, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Part Number', field: 'part_num', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Category', field: 'category_label', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'button', text: 'Remove Part', url: '{{ url('/api/users/storage/locations/'.$location->id.'/parts/toggle/{part_num}') }}', successMsg: 'Part association successfully updated!'},
                    ]"
                :allowedparams="['name', 'part_num', 'part_category_id']"
                endpoint="{{ route('api.users.storage.locations.parts', $location->id) }}"></data-grid>
        </div>
    </div>
</div>
@endsection
