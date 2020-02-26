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
                        select_classes="text-md md:text-xl w-full"
                        selected_value="{{ $location->id }}"
                        selected_field="id"
                        label_field="location_name"
                        change_endpoint="{{ url('/storage/locations/{location}/parts/') }}"
                        :data="{{ $allLocations }}"
                    />
                @else
                    <p class="title text-md md:text-xl">{{ $location->name }}</p>
                @endif
            </div>
            <p class="text-sm md:text-base mb-2 font-normal tracking-wide"><span class="font-semibold">Nickname:</span> {{ $location->nickname ?: 'None' }}</p>
            <p class="text-sm md:text-base mb-2 font-normal tracking-wide"><span class="font-semibold">Description:</span> {{ $location->description }}</p>
            <p class="text-sm md:text-base mb-2 font-normal tracking-wide"><span class="font-semibold">Type:</span> {{ $location->type->name }}</p>
            <p class="text-sm md:text-base mb-2 font-normal tracking-wide"><span class="font-semibold">Parts in Location:</span> {{ $location->parts->unique('part_num')->count() }}</p>
            <p class="text-sm md:text-base mb-2 font-normal tracking-wide"><span class="font-semibold">Individual Parts in Location:</span> {{ $location->parts->count() }}</p>
            <p class="text-sm md:text-base mb-2 font-normal tracking-wide"><span class="font-semibold">Total Pieces in Location:</span> {{ $location->parts->sum('quantity') }}</p>
        </div>
        <div class="pt-2 md:flex">
            <a href="{{ route('storage.locations.parts.edit', $location->id) }}" 
                class="btn is-primary is-narrow block"
                data-cy="edit-parts-button"
            >Edit Parts in Location</a>
            @if ($location->parts->count())
                <a href="{{ route('storage.locations.parts.individual', $location->id) }}" 
                    class="btn is-primary is-narrow block mt-1 ml-0 md:mt-0 md:ml-1"
                    data-cy="individual-parts-button"
                >View Individual Pieces</a>
                <a href="{{ route('storage.locations.parts.move', $location->id) }}" 
                    class="btn is-primary is-narrow block mt-1 ml-0 md:mt-0 md:ml-auto"
                    data-cy="move-parts-button"
                >Move Parts</a>
            @endif
        </div>
    </div>
    <div class="rounded shadow">
        <div class="flex font-medium text-lg text-primary-700 bg-primary-500 p-3 rounded-t">
            <div>Storage Location Parts</div>
        </div>
        <div class="bg-white p-4 pb-6 rounded-b">
            <data-grid 
                label="Parts" 
                image_field="image_url" 
                image_label_field="name" 
                per_page="100" 
                :valnames="[
                    {label: 'Name', field: 'name', title: true, sortable: true, sorted: true, sortdesc: false, boolean: false},
                    {label: 'Part Number', field: 'part_num', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Category', field: 'category_label', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Part Count', field: 'total_part_count', title: false, sortable: false, sorted: false, sortdesc: false, boolean: false},
                    {label: 'button', text: 'Remove Part', url: '{{ url('/api/users/storage/locations/'.$location->id.'/parts/toggle/{part_num}') }}', successMsg: 'Part association successfully updated!'},
                    ]"
                :allowedparams="['name', 'part_num', 'part_category_id']"
                :sort-order="['name']"
                endpoint="{{ route('api.users.storage.locations.parts', $location->id) }}"></data-grid>
        </div>
    </div>
</div>
@endsection
