@extends('layouts.app')

@section('title')
    Move Location Parts: {{ $location->nickname ?: $location->name }}
@endsection

@section('content')
<div class="w-full px-3 sm:px-0">
    <div class="bg-white border mb-8 md:mx-auto md:p-6 md:w-2/3 p-3 rounded-lg shadow-md w-full">
        <div>
            <p class="mb-3 title font-semibold">{{ $location->name }}</p>
            <p class="text-sm md:text-base mb-2 font-normal tracking-wide"><span class="font-semibold">Nickname:</span> {{ $location->nickname ?: 'None' }}</p>
            <p class="text-sm md:text-base mb-2 font-normal tracking-wide"><span class="font-semibold">Description:</span> {{ $location->description }}</p>
            <p class="text-sm md:text-base mb-2 font-normal tracking-wide"><span class="font-semibold">Type:</span> {{ $location->type->name }}</p>
            <p class="text-sm md:text-base mb-2 font-normal tracking-wide"><span class="font-semibold">Parts in Location:</span> {{ $location->parts->unique('part_num')->count() }}</p>
            <p class="text-sm md:text-base mb-2 font-normal tracking-wide"><span class="font-semibold">Individual Parts in Location:</span> {{ $location->parts->count() }}</p>
            <p class="text-sm md:text-base mb-2 font-normal tracking-wide"><span class="font-semibold">Total Pieces in Location:</span> {{ $location->parts->sum('quantity') }}</p>
        </div>
        <div class="pt-2 flex">
            <a href="{{ url()->previous() }}" class="btn is-outlined block ml-auto">Done</a>
        </div>
    </div>
    <div class="rounded shadow">
        <div class="flex text-lg text-primary-900 bg-primary-500 p-3 rounded-t">
            <div>Move Storage Location Parts</div>
        </div>
        <div class="bg-white p-4 pb-6 rounded-b">
            <data-grid-move-location 
                label="Parts" 
                image_field="image_url" 
                image_label_field="name" 
                per_page="100" 
                :all_move_locations="{{ $allLocations }}"
                move_endpoint="{{ url('/api/users/storage/locations/'.$location->id.'/parts/') }}"
                :valnames="[
                    {label: 'Name', field: 'name', title: true, sortable: true, sorted: true, sortdesc: false, boolean: false},
                    {label: 'Part Number', field: 'part_num', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Category', field: 'category_label', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    ]"
                :allowedparams="['name', 'part_num', 'part_category_id']"
                :sort-order="['name']"
                endpoint="{{ route('api.users.storage.locations.parts', $location->id) }}"></data-grid-move-location>
        </div>
    </div>
</div>
@endsection
