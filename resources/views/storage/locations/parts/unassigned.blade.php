@extends('layouts.app')

@section('title')
    Unassigned Parts:
@endsection

@section('content')
<div class="w-full px-3 sm:px-0">
    <div class="rounded shadow">
        <div class="flex text-lg text-primary-900 bg-primary-500 p-3 rounded-t">
            <div>Unassigned User Parts</div>
        </div>
        <div class="bg-white p-4 pb-6 rounded-b">
            <data-grid-move-location 
                label="Parts" 
                image_field="image_url" 
                image_label_field="name" 
                per_page="100" 
                :all_move_locations="{{ $allLocations }}"
                move_endpoint="{{ url('/api/users/storage/locations/parts/unassigned') }}"
                :valnames="[
                    {label: 'Name', field: 'name', title: true, sortable: true, sorted: true, sortdesc: false, boolean: false},
                    {label: 'Part Number', field: 'part_num', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    {label: 'Category', field: 'category_label', title: false, sortable: true, sorted: false, sortdesc: false, boolean: false},
                    ]"
                :allowedparams="['name', 'part_num', 'part_category_id', 'only_unassigned']"
                :sort-order="['name']"
                endpoint="{{ route('api.users.storage.locations.unassigned.parts', ['only_unassigned' => 1]) }}"></data-grid-move-location>
        </div>
    </div>
</div>
@endsection
