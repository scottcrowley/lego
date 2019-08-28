@extends('layouts.app')

@section('content')
<div class="w-full px-3 sm:px-0">
    <div class="bg-white border mb-8 md:mx-auto md:p-6 md:w-2/3 p-3 rounded-lg shadow-md w-full">
        <div>
            <div class="mb-4 flex">
                    @if ($allLocations->count())
                        <select-menu
                            parent_classes="w-full"
                            select_classes="text-md md:text-2xl w-full"
                            selected_value="{{ $location->id }}"
                            selected_field="id"
                            label_field="name"
                            change_endpoint="{{ url('/storage/locations/{location}/parts/individual') }}"
                            :data="{{ $allLocations }}"
                        />
                    @else
                        <p class="title text-md md:text-2xl">{{ $location->name }}</p>
                    @endif
                </div>
            <p class="text-sm md:text-base mb-2 font-hairline tracking-wide"><span class="font-semibold">Nickname:</span> {{ $location->nickname ?: 'None' }}</p>
            <p class="text-sm md:text-base mb-2 font-hairline tracking-wide"><span class="font-semibold">Type:</span> {{ $location->type->name }}</p>
            <p class="text-sm md:text-base mb-2 font-hairline tracking-wide"><span class="font-semibold">Parts in Location:</span> {{ $location->parts->unique('part_num')->count() }}</p>
            <p class="text-sm md:text-base mb-2 font-hairline tracking-wide"><span class="font-semibold">Individual Parts in Location:</span> {{ $location->parts->count() }}</p>
            <p class="text-sm md:text-base mb-2 font-hairline tracking-wide"><span class="font-semibold">Total Pieces in Location:</span> {{ $location->parts->sum('quantity') }}</p>
        </div>
        <div class="pt-2">
            <a href="{{ route('storage.locations.parts.edit', $location->id) }}" class="btn is-primary is-narrow block md:inline">Edit Parts in Location</a>
            <a href="{{ route('storage.locations.parts.index', $location->id) }}" class="btn is-primary is-narrow block md:inline mt-1 md:mt-0">View Grouped Parts</a>
        </div>
    </div>
    <div class="rounded shadow">
        <div class="flex font-medium text-lg text-primary-darker bg-primary p-3 rounded-t">
            <div>Storage Location Individual Parts</div>
        </div>
        <div class="bg-white p-4 pb-6 rounded-b">
            <data-grid 
                label="Parts" 
                image_field="ldraw_image_url" 
                image_label_field="image_url" 
                per_page="100" 
                :allow_image_swap=true
                :valnames="[
                    {label: 'Name', field: 'name', title: true, sortable: true, sorted: true, sortdesc: false, boolean: false},
                    {label: 'Part Number', field: 'part_num', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Category', field: 'category_label', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Color', field: 'color_name', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Quantity', field: 'quantity', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'button', text: 'Remove Part', url: '{{ url('/api/users/storage/locations/'.$location->id.'/parts/toggle/{part_num}') }}', successMsg: 'Part association successfully updated!'},
                    ]"
                :allowedparams="['name', 'part_num', 'part_category_id']"
                endpoint="{{ route('api.users.storage.locations.parts.individual', $location->id) }}"></data-grid>
        </div>
    </div>
</div>
@endsection
