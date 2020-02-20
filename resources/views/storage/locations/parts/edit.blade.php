@extends('layouts.app')

@section('title')
    Location Parts: {{ $location->nickname ?: $location->name }}
@endsection

@section('content')
<div class="w-full px-3 sm:px-0">
    <div class="bg-white border mb-8 md:mx-auto md:p-6 md:w-2/3 p-3 rounded-lg shadow-md w-full">
        <div>
            <div class="mb-4 flex">
                @if ($allLocations->count())
                    <select-menu
                        parent_classes="w-full"
                        select_classes="text-md w-full md:text-xl"
                        selected_value="{{ $location->id }}"
                        selected_field="id"
                        label_field="location_name"
                        change_endpoint="{{ url('/storage/locations/{location}/parts/edit') }}"
                        :data="{{ $allLocations }}"
                    />
                @else
                    <p class="title text-md md:text-xl">{{ $location->name }}</p>
                @endif
            </div>
            <p class="text-sm md:text-base mb-2 font-normal tracking-wide"><span class="font-semibold">Nickname:</span> {{ $location->nickname ?: 'None' }}</p>
            <p class="text-sm md:text-base mb-2 font-normal tracking-wide"><span class="font-semibold">Type:</span> {{ $location->type->name }}</p>
            <p class="text-sm md:text-base mb-2 font-normal tracking-wide"><span class="font-semibold">Parts in Location:</span> {{ $location->parts->count() }}</p>
        </div>
        <div class="pt-2">
            <a href="{{ route('storage.locations.parts.index', $location->id) }}" 
                class="btn is-primary is-narrow block md:inline"
                data-cy="view-parts-button"
            >View Parts in Location</a>
        </div>
    </div>
    <div class="rounded shadow">
        <div class="flex font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
            <div>Available Parts</div>
        </div>
        <div class="bg-white px-4 pb-6 rounded-b">
            <data-grid-with-filters 
                :use_location=true
                label="Parts" 
                location_id="{{ $location->id }}"
                image_field="image_url" 
                image_label_field="name"
                toggle_end_point="{{ url('/api/users/storage/locations/'.$location->id.'/parts/toggle/{part_num}') }}"
                per_page="100"
                :filters="[
                    {label: 'Name', param: 'name', type: 'text', classes: 'flex-1 ml-3'},
                    {label: 'Part Number', param: 'part_num', type: 'text', classes: 'flex-1 ml-3'},
                    {label: 'Category', param: 'category_label', type: 'text', classes: 'flex-1 ml-3'},
                    {label: 'Exclude Assigned Parts', param: 'exclude_assigned', type: 'checkbox', value: {{ $location->id }}, classes: 'ml-3'},
                    ]"
                :valnames="[
                    {label: 'Name', field: 'name', title: true, sortable: true, sorted: true, sortdesc: false, boolean: false},
                    {label: 'Part Number', field: 'part_num', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Category', field: 'category_label', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    ]"
                :allowedparams="['name', 'part_num', 'part_category_id', 'exclude_assigned', 'category_label']"
                :sort-order="['name']"
                endpoint="{{ route('api.users.storage.locations.parts.edit', $location->id) }}"></data-grid-with-filters>
        </div>
    </div>
</div>
@endsection
